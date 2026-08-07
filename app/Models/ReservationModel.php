<?php
namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id', 'car_id', 'start_date', 'end_date', 'total_cost',
        'status', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'car_id' => 'required|numeric',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date',
        'total_cost' => 'required|numeric',
        'status' => 'required|in_list[pending,confirmed,ongoing,completed,cancelled]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getUserReservations($userId)
    {
        return $this->select('reservations.*, cars.brand, cars.model, cars.plate_number, cars.image')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->where('reservations.user_id', $userId)
                    ->orderBy('reservations.created_at', 'DESC')
                    ->findAll();
    }

public function getAllReservationsWithDetails()
{
    return $this->select('reservations.*, users.name as customer_name, users.email, cars.brand, cars.model, cars.plate_number')
        ->join('users', 'users.id = reservations.user_id')
        ->join('cars', 'cars.id = reservations.car_id')
        ->orderBy('reservations.created_at', 'DESC')
        ->findAll();
}

public function getReservationWithDetails($reservationId)
{
    return $this->select('reservations.*, users.name as customer_name, users.email, users.phone, cars.*')
        ->join('users', 'users.id = reservations.user_id')
        ->join('cars', 'cars.id = reservations.car_id')
        ->where('reservations.id', $reservationId)
        ->first();
}

    public function calculateTotalCost($carId, $startDate, $endDate)
    {
        $carModel = new CarModel();
        $car = $carModel->find($carId);
        
        if (!$car) {
            return 0;
        }

        $start = strtotime($startDate);
        $end = strtotime($endDate);
        $days = ($end - $start) / (60 * 60 * 24) + 1;

        return $car['daily_rate'] * $days;
    }

    public function getReservationsByStatus($status)
    {
        return $this->select('reservations.*, users.name as customer_name, cars.brand, cars.model')
                    ->join('users', 'users.id = reservations.user_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->where('reservations.status', $status)
                    ->orderBy('reservations.created_at', 'DESC')
                    ->findAll();
    }

    public function getActiveReservations()
    {
        return $this->select('reservations.*, users.name as customer_name, cars.brand, cars.model, cars.plate_number')
                    ->join('users', 'users.id = reservations.user_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->whereIn('reservations.status', ['confirmed', 'ongoing'])
                    ->orderBy('reservations.start_date', 'ASC')
                    ->findAll();
    }

    public function getUpcomingReservations($days = 7)
    {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));

        return $this->select('reservations.*, users.name as customer_name, cars.brand, cars.model, cars.plate_number')
                    ->join('users', 'users.id = reservations.user_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->where('reservations.start_date >=', $today)
                    ->where('reservations.start_date <=', $futureDate)
                    ->whereIn('reservations.status', ['confirmed'])
                    ->orderBy('reservations.start_date', 'ASC')
                    ->findAll();
    }

    public function getOverdueReservations()
    {
        $today = date('Y-m-d');

        return $this->select('reservations.*, users.name as customer_name, cars.brand, cars.model, cars.plate_number')
                    ->join('users', 'users.id = reservations.user_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->where('reservations.end_date <', $today)
                    ->whereIn('reservations.status', ['confirmed', 'ongoing'])
                    ->orderBy('reservations.end_date', 'ASC')
                    ->findAll();
    }

    public function updateReservationStatus($reservationId, $status)
    {
        $validStatuses = ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        return $this->update($reservationId, ['status' => $status]);
    }

    public function getReservationStats($startDate = null, $endDate = null)
    {
        $builder = $this;

        if ($startDate && $endDate) {
            $builder->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate);
        }

        $totalReservations = $builder->countAllResults();
        $pendingReservations = $builder->where('status', 'pending')->countAllResults();
        $confirmedReservations = $builder->where('status', 'confirmed')->countAllResults();
        $completedReservations = $builder->where('status', 'completed')->countAllResults();
        $cancelledReservations = $builder->where('status', 'cancelled')->countAllResults();

        // Calculate revenue
        $revenue = $this->selectSum('total_cost')
                       ->where('status', 'completed');
        
        if ($startDate && $endDate) {
            $revenue->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate);
        }

        $totalRevenue = $revenue->get()->getRow()->total_cost ?? 0;

        return [
            'total_reservations' => $totalReservations,
            'pending_reservations' => $pendingReservations,
            'confirmed_reservations' => $confirmedReservations,
            'completed_reservations' => $completedReservations,
            'cancelled_reservations' => $cancelledReservations,
            'total_revenue' => $totalRevenue
        ];
    }

    public function getReports($startDate, $endDate)
    {
        $db = db_connect();

        // Monthly revenue
        $monthlyRevenue = $db->table('reservations')
            ->select("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_cost) as revenue")
            ->where('status', 'completed')
            ->where('created_at >=', $startDate)
            ->where('created_at <=', $endDate)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();

        // Popular cars
        $popularCars = $db->table('reservations r')
            ->select('c.brand, c.model, c.id, COUNT(r.id) as reservation_count, SUM(r.total_cost) as total_revenue')
            ->join('cars c', 'c.id = r.car_id')
            ->where('r.status', 'completed')
            ->where('r.created_at >=', $startDate)
            ->where('r.created_at <=', $endDate)
            ->groupBy('r.car_id')
            ->orderBy('reservation_count', 'DESC')
            ->get()
            ->getResultArray();

        // Customer activity
        $topCustomers = $db->table('reservations r')
            ->select('u.name, u.email, u.id, COUNT(r.id) as reservation_count, SUM(r.total_cost) as total_spent')
            ->join('users u', 'u.id = r.user_id')
            ->where('r.status', 'completed')
            ->where('r.created_at >=', $startDate)
            ->where('r.created_at <=', $endDate)
            ->groupBy('r.user_id')
            ->orderBy('total_spent', 'DESC')
            ->get()
            ->getResultArray();

        return [
            'monthly_revenue' => $monthlyRevenue,
            'popular_cars' => $popularCars,
            'top_customers' => $topCustomers
        ];
    }

    public function checkDateConflict($carId, $startDate, $endDate, $excludeReservationId = null)
    {
        $builder = $this->where('car_id', $carId)
            ->where('status !=', 'cancelled')
            ->groupStart()
            ->where('start_date <=', $endDate)
            ->where('end_date >=', $startDate)
            ->groupEnd();

        if ($excludeReservationId) {
            $builder->where('id !=', $excludeReservationId);
        }

        return $builder->countAllResults() > 0;
    }

    public function getReservationDurationStats()
    {
        $db = db_connect();
        
        return $db->table('reservations')
            ->select('AVG(DATEDIFF(end_date, start_date) + 1) as avg_duration, 
                     MIN(DATEDIFF(end_date, start_date) + 1) as min_duration, 
                     MAX(DATEDIFF(end_date, start_date) + 1) as max_duration')
            ->where('status', 'completed')
            ->get()
            ->getRowArray();
    }
}