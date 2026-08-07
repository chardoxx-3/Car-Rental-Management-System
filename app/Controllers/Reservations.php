<?php
namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\CarModel;
use App\Models\PaymentModel;

class Reservations extends BaseController
{
    protected $reservationModel;
    protected $carModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->carModel = new CarModel();
        $this->paymentModel = new PaymentModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('userId');
        $role = session()->get('role');

        if ($role === 'admin') {
            $reservations = $this->reservationModel->getAllReservationsWithDetails();
        } else {
            $reservations = $this->reservationModel->getUserReservations($userId);
        }

        $data = [
            'title' => $role === 'admin' ? 'All Reservations' : 'My Reservations',
            'reservations' => $reservations,
            'isAdmin' => $role === 'admin'
        ];

        if ($role === 'admin') {
            return view('admin/reservations_management', $data);
        } else {
            return view('customer/my_reservations', $data);
        }
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $rules = [
            'car_id' => 'required|numeric',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date|after_date[start_date]'
        ];

        if ($this->validate($rules)) {
            $carId = $this->request->getPost('car_id');
            $startDate = $this->request->getPost('start_date');
            $endDate = $this->request->getPost('end_date');
            $userId = session()->get('userId');

            // Check car availability
            if (!$this->carModel->isCarAvailable($carId, $startDate, $endDate)) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Car is not available for the selected dates'
                ]);
            }

            $totalCost = $this->reservationModel->calculateTotalCost($carId, $startDate, $endDate);

            $reservationData = [
                'user_id' => $userId,
                'car_id' => $carId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_cost' => $totalCost,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->reservationModel->save($reservationData)) {
                $reservationId = $this->reservationModel->getInsertID();
                
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Reservation created successfully',
                    'reservation_id' => $reservationId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Failed to create reservation'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false, 
                'message' => $this->validator->listErrors()
            ]);
        }
    }

    public function show($reservationId)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $reservation = $this->reservationModel->getReservationWithDetails($reservationId);
        
        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->back();
        }

        // Check if user has permission to view this reservation
        $userId = session()->get('userId');
        $role = session()->get('role');
        
        if ($role !== 'admin' && $reservation['user_id'] != $userId) {
            session()->setFlashdata('error', 'Access denied');
            return redirect()->back();
        }

        $data = [
            'title' => 'Reservation Details',
            'reservation' => $reservation
        ];

        if ($role === 'admin') {
            return view('admin/reservation_details', $data);
        } else {
            return view('customer/reservation_details', $data);
        }
    }

    public function updateStatus($reservationId)
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        $status = $this->request->getPost('status');
        $validStatuses = ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }

        $reservation = $this->reservationModel->find($reservationId);
        
        if (!$reservation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Reservation not found']);
        }

        if ($this->reservationModel->update($reservationId, ['status' => $status])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Reservation status updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update reservation status']);
        }
    }

    public function cancel($reservationId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $reservation = $this->reservationModel->find($reservationId);
        
        if (!$reservation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Reservation not found']);
        }

        $userId = session()->get('userId');
        $role = session()->get('role');
        
        // Check permission
        if ($role !== 'admin' && $reservation['user_id'] != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Check if cancellation is allowed
        if (!in_array($reservation['status'], ['pending', 'confirmed'])) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Cannot cancel reservation with current status'
            ]);
        }

        if ($this->reservationModel->update($reservationId, ['status' => 'cancelled'])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Reservation cancelled successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to cancel reservation']);
        }
    }

    public function calculateCost()
    {
        $carId = $this->request->getGet('car_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if (!$carId || !$startDate || !$endDate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing parameters']);
        }

        $totalCost = $this->reservationModel->calculateTotalCost($carId, $startDate, $endDate);
        
        return $this->response->setJSON([
            'success' => true,
            'total_cost' => $totalCost,
            'formatted_cost' => number_format($totalCost, 2)
        ]);
    }

    public function getUserReservations()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login first']);
        }

        $userId = session()->get('userId');
        $reservations = $this->reservationModel->getUserReservations($userId);
        
        return $this->response->setJSON(['success' => true, 'reservations' => $reservations]);
    }

    // In ReservationModel.php, update the getReports method:

public function getReports($startDate, $endDate)
{
    $db = db_connect();

    // Monthly revenue with reservation counts
    $monthlyRevenue = $db->table('reservations r')
        ->select("DATE_FORMAT(r.created_at, '%Y-%m') as month, 
                 SUM(r.total_cost) as revenue,
                 COUNT(r.id) as reservation_count")
        ->where('r.status', 'completed')
        ->where('r.created_at >=', $startDate)
        ->where('r.created_at <=', $endDate)
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->get()
        ->getResultArray();

    // Popular cars - FIXED to use proper data
    $popularCars = $db->table('reservations r')
        ->select('c.brand, c.model, c.id, COUNT(r.id) as reservation_count, SUM(r.total_cost) as total_revenue')
        ->join('cars c', 'c.id = r.car_id')
        ->where('r.status', 'completed')
        ->where('r.created_at >=', $startDate)
        ->where('r.created_at <=', $endDate)
        ->groupBy('r.car_id')
        ->orderBy('reservation_count', 'DESC')
        ->orderBy('total_revenue', 'DESC')
        ->get()
        ->getResultArray();

    // Customer activity - FIXED to use proper data
    $topCustomers = $db->table('reservations r')
        ->select('u.name, u.email, u.id, COUNT(r.id) as reservation_count, SUM(r.total_cost) as total_spent')
        ->join('users u', 'u.id = r.user_id')
        ->where('r.status', 'completed')
        ->where('r.created_at >=', $startDate)
        ->where('r.created_at <=', $endDate)
        ->groupBy('r.user_id')
        ->orderBy('total_spent', 'DESC')
        ->orderBy('reservation_count', 'DESC')
        ->get()
        ->getResultArray();

    return [
        'monthly_revenue' => $monthlyRevenue,
        'popular_cars' => $popularCars,
        'top_customers' => $topCustomers
    ];
}
}