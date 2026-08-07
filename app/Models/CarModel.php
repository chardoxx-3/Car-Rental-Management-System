<?php
namespace App\Models;

use CodeIgniter\Model;

class CarModel extends Model
{
    protected $table = 'cars';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'brand', 'model', 'year', 'color', 'plate_number', 'capacity',
        'transmission', 'daily_rate', 'status', 'image', 'description',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
protected $validationRules = [
    'brand' => 'required',
    'model' => 'required',
    'year' => 'required|numeric|min_length[4]|max_length[4]',
    'color' => 'required',
    'plate_number' => 'required',
    'capacity' => 'required|numeric',
    'transmission' => 'required|in_list[automatic,manual]',
    'daily_rate' => 'required|numeric',
    'status' => 'required|in_list[available,unavailable,maintenance]'
];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getAvailableCars()
    {
        return $this->where('status', 'available')
                    ->orderBy('brand', 'ASC')
                    ->orderBy('model', 'ASC')
                    ->findAll();
    }

public function isCarAvailable($carId, $startDate, $endDate)
{
    // Check if car exists and is available
    $car = $this->where('id', $carId)
                ->where('status', 'available')
                ->first();

    if (!$car) {
        return false;
    }

    // Check for overlapping reservations
    $reservationModel = new ReservationModel();
    
    // Create a new instance of the model's builder
    $builder = $reservationModel->builder();
    
    $overlappingReservations = $builder->where('car_id', $carId)
        ->where('status !=', 'cancelled')
        ->groupStart()
        ->where('start_date <=', $endDate)
        ->where('end_date >=', $startDate)
        ->groupEnd()
        ->countAllResults();

    return $overlappingReservations === 0;
}

    public function getCarWithDetails($carId)
    {
        $car = $this->find($carId);
        if (!$car) {
            return null;
        }

        // Get reservation statistics
        $reservationModel = new ReservationModel();
        $car['total_reservations'] = $reservationModel->where('car_id', $carId)
            ->where('status !=', 'cancelled')
            ->countAllResults();
        $car['completed_reservations'] = $reservationModel->where('car_id', $carId)
            ->where('status', 'completed')
            ->countAllResults();

        return $car;
    }

    public function updateCarStatus($carId, $status)
    {
        $validStatuses = ['available', 'unavailable', 'maintenance'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        return $this->update($carId, ['status' => $status]);
    }

    public function getCarsByStatus($status)
    {
        $validStatuses = ['available', 'unavailable', 'maintenance'];
        if (!in_array($status, $validStatuses)) {
            return [];
        }

        return $this->where('status', $status)
                    ->orderBy('brand', 'ASC')
                    ->orderBy('model', 'ASC')
                    ->findAll();
    }

    public function searchCars($searchTerm, $status = null)
    {
        $builder = $this->groupStart()
                    ->like('brand', $searchTerm)
                    ->orLike('model', $searchTerm)
                    ->orLike('plate_number', $searchTerm)
                    ->orLike('color', $searchTerm)
                    ->groupEnd();

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->orderBy('brand', 'ASC')
                    ->orderBy('model', 'ASC')
                    ->findAll();
    }

    public function getCarStatistics()
    {
        $totalCars = $this->countAll();
        $availableCars = $this->where('status', 'available')->countAllResults();
        $unavailableCars = $this->where('status', 'unavailable')->countAllResults();
        $maintenanceCars = $this->where('status', 'maintenance')->countAllResults();

        // Get most popular cars based on reservations
        $db = db_connect();
        $popularCars = $db->table('reservations r')
            ->select('c.brand, c.model, c.id, COUNT(r.id) as reservation_count')
            ->join('cars c', 'c.id = r.car_id')
            ->where('r.status !=', 'cancelled')
            ->groupBy('r.car_id')
            ->orderBy('reservation_count', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return [
            'total_cars' => $totalCars,
            'available_cars' => $availableCars,
            'unavailable_cars' => $unavailableCars,
            'maintenance_cars' => $maintenanceCars,
            'popular_cars' => $popularCars
        ];
    }

    public function getDailyRatesRange()
    {
        $result = $this->selectMin('daily_rate', 'min_rate')
                      ->selectMax('daily_rate', 'max_rate')
                      ->first();
        return [
            'min_rate' => $result['min_rate'],
            'max_rate' => $result['max_rate']
        ];
    }

    public function getCarsByFilters($filters = [])
    {
        $builder = $this;

        if (!empty($filters['brand'])) {
            $builder->like('brand', $filters['brand']);
        }

        if (!empty($filters['model'])) {
            $builder->like('model', $filters['model']);
        }

        if (!empty($filters['transmission'])) {
            $builder->where('transmission', $filters['transmission']);
        }

        if (!empty($filters['min_capacity'])) {
            $builder->where('capacity >=', $filters['min_capacity']);
        }

        if (!empty($filters['max_daily_rate'])) {
            $builder->where('daily_rate <=', $filters['max_daily_rate']);
        }

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        } else {
            $builder->where('status', 'available');
        }

        return $builder->orderBy('brand', 'ASC')
                      ->orderBy('model', 'ASC')
                      ->findAll();
    }

    public function getCarUtilization($carId, $startDate, $endDate)
    {
        $reservationModel = new ReservationModel();
        
        $totalDays = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;
        $reservedDays = 0;

        $reservations = $reservationModel->where('car_id', $carId)
            ->where('status !=', 'cancelled')
            ->where('start_date <=', $endDate)
            ->where('end_date >=', $startDate)
            ->findAll();

        foreach ($reservations as $reservation) {
            $resStart = max(strtotime($reservation['start_date']), strtotime($startDate));
            $resEnd = min(strtotime($reservation['end_date']), strtotime($endDate));
            $reservedDays += ($resEnd - $resStart) / (60 * 60 * 24) + 1;
        }

        $utilizationRate = $totalDays > 0 ? ($reservedDays / $totalDays) * 100 : 0;

        return [
            'total_days' => $totalDays,
            'reserved_days' => $reservedDays,
            'utilization_rate' => round($utilizationRate, 2)
        ];
    }
}