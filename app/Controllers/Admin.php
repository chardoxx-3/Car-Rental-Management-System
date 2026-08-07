<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CarModel;
use App\Models\ReservationModel;
use App\Models\PaymentModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $carModel;
    protected $reservationModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->carModel = new CarModel();
        $this->reservationModel = new ReservationModel();
        $this->paymentModel = new PaymentModel();
        
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login');
        }
    }

    public function dashboard()
    {
        // Get current month and previous month for comparison
        $currentMonth = date('Y-m');
        $previousMonth = date('Y-m', strtotime('-1 month'));
        
        // Calculate percentage changes
        $currentRevenue = $this->paymentModel->selectSum('amount')
            ->where('status', 'completed')
            ->where('YEAR(payment_date)', date('Y'))
            ->where('MONTH(payment_date)', date('m'))
            ->get()->getRow()->amount ?? 0;
            
        $previousRevenue = $this->paymentModel->selectSum('amount')
            ->where('status', 'completed')
            ->where('YEAR(payment_date)', date('Y', strtotime('-1 month')))
            ->where('MONTH(payment_date)', date('m', strtotime('-1 month')))
            ->get()->getRow()->amount ?? 0;
            
        $revenueChange = $previousRevenue > 0 ? (($currentRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
        
        // Calculate reservations change
        $currentReservations = $this->reservationModel
            ->where('YEAR(created_at)', date('Y'))
            ->where('MONTH(created_at)', date('m'))
            ->countAllResults();
            
        $previousReservations = $this->reservationModel
            ->where('YEAR(created_at)', date('Y', strtotime('-1 month')))
            ->where('MONTH(created_at)', date('m', strtotime('-1 month')))
            ->countAllResults();
            
        $reservationsChange = $previousReservations > 0 ? (($currentReservations - $previousReservations) / $previousReservations) * 100 : 0;
        
        // Calculate customers change
        $currentCustomers = $this->userModel->where('role', 'customer')
            ->where('YEAR(created_at)', date('Y'))
            ->where('MONTH(created_at)', date('m'))
            ->countAllResults();
            
        $previousCustomers = $this->userModel->where('role', 'customer')
            ->where('YEAR(created_at)', date('Y', strtotime('-1 month')))
            ->where('MONTH(created_at)', date('m', strtotime('-1 month')))
            ->countAllResults();
            
        $customersChange = $previousCustomers > 0 ? (($currentCustomers - $previousCustomers) / $previousCustomers) * 100 : 0;
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity();
        
        $data = [
            'title' => 'Admin Dashboard',
            'stats' => [
                'total_cars' => $this->carModel->countAll(),
                'available_cars' => $this->carModel->where('status', 'available')->countAllResults(),
                'total_customers' => $this->userModel->where('role', 'customer')->countAllResults(),
                'total_reservations' => $this->reservationModel->countAll(),
                'pending_reservations' => $this->reservationModel->where('status', 'pending')->countAllResults(),
                'total_revenue' => $this->paymentModel->selectSum('amount')->where('status', 'completed')->get()->getRow()->amount ?? 0,
                'revenue_change' => $revenueChange,
                'reservations_change' => $reservationsChange,
                'customers_change' => $customersChange
            ],
            'recent_activity' => $recentActivity
        ];
        return view('admin/dashboard', $data);
    }
    
    public function getRevenueData()
    {
        $days = $this->request->getGet('days') ?? 30;
        
        // Calculate start date based on days parameter
        $startDate = date('Y-m-d', strtotime("-$days days"));
        $endDate = date('Y-m-d');
        
        // Get revenue data for the period
        $revenueData = $this->paymentModel->select("DATE(payment_date) as date, SUM(amount) as revenue")
            ->where('status', 'completed')
            ->where('payment_date >=', $startDate)
            ->where('payment_date <=', $endDate)
            ->groupBy('DATE(payment_date)')
            ->orderBy('date', 'ASC')
            ->get()
            ->getResultArray();
        
        // Format data for chart
        $labels = [];
        $values = [];
        
        // Create a complete date range
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $labels[] = date('M j', strtotime($currentDate));
            
            // Find revenue for this date
            $revenue = 0;
            foreach ($revenueData as $data) {
                if ($data['date'] == $currentDate) {
                    $revenue = $data['revenue'];
                    break;
                }
            }
            
            $values[] = $revenue;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        return $this->response->setJSON([
            'labels' => $labels,
            'values' => $values
        ]);
    }
    
    private function getRecentActivity()
    {
        $activity = [];
        
        // Get recent reservations
        $recentReservations = $this->reservationModel
            ->select('reservations.*, users.name as customer_name, cars.brand, cars.model')
            ->join('users', 'users.id = reservations.user_id')
            ->join('cars', 'cars.id = reservations.car_id')
            ->orderBy('reservations.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();
            
        foreach ($recentReservations as $reservation) {
            $timeAgo = $this->getTimeAgo($reservation['created_at']);
            
            $activity[] = [
                'title' => 'New reservation',
                'description' => $reservation['brand'] . ' ' . $reservation['model'] . ' • ' . $reservation['customer_name'],
                'time_ago' => $timeAgo,
                'icon' => 'fa-calendar-check',
                'color' => 'var(--success)',
                'bg_color' => 'rgba(16, 185, 129, 0.1)'
            ];
        }
        
        // Get recent car additions
        $recentCars = $this->carModel
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();
            
        foreach ($recentCars as $car) {
            $timeAgo = $this->getTimeAgo($car['created_at']);
            
            $activity[] = [
                'title' => 'New car added',
                'description' => $car['brand'] . ' ' . $car['model'] . ' ' . $car['year'],
                'time_ago' => $timeAgo,
                'icon' => 'fa-car',
                'color' => 'var(--primary)',
                'bg_color' => 'rgba(37, 99, 235, 0.1)'
            ];
        }
        
        // Sort by creation date (newest first)
        usort($activity, function($a, $b) {
            return strtotime($b['time_ago']) - strtotime($a['time_ago']);
        });
        
        // Return only the 4 most recent activities
        return array_slice($activity, 0, 4);
    }
    
    private function getTimeAgo($datetime)
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } else {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        }
    }

public function manageCars()
{
    $pager = \Config\Services::pager();
    $perPage = 10; // Cars per page
    
    // Sort by created_at descending (newest first)
    $cars = $this->carModel->orderBy('created_at', 'DESC')->paginate($perPage);
    $pager = $this->carModel->pager;
    
    $data = [
        'title' => 'Manage Cars',
        'cars' => $cars,
        'pager' => $pager
    ];
    return view('admin/cars_management', $data);
}
    public function addCar()
    {
        $data = [
            'title' => 'Add New Car'
        ];
        return view('admin/add_car', $data);
    }

    public function storeCar()
    {
        $rules = [
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required|numeric',
            'color' => 'required',
            'plate_number' => 'required|is_unique[cars.plate_number]',
            'capacity' => 'required|numeric',
            'transmission' => 'required',
            'daily_rate' => 'required|numeric',
            'status' => 'required'
        ];

        if ($this->validate($rules)) {
            $carData = [
                'brand' => $this->request->getPost('brand'),
                'model' => $this->request->getPost('model'),
                'year' => $this->request->getPost('year'),
                'color' => $this->request->getPost('color'),
                'plate_number' => $this->request->getPost('plate_number'),
                'capacity' => $this->request->getPost('capacity'),
                'transmission' => $this->request->getPost('transmission'),
                'daily_rate' => $this->request->getPost('daily_rate'),
                'status' => $this->request->getPost('status'),
                'description' => $this->request->getPost('description'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Handle image upload
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move('./uploads/cars', $newName);
                $carData['image'] = $newName;
            }

            if ($this->carModel->save($carData)) {
                session()->setFlashdata('success', 'Car added successfully');
                return redirect()->to('/admin/manageCars');
            } else {
                session()->setFlashdata('error', 'Failed to add car');
            }
        } else {
            session()->setFlashdata('error', $this->validator->listErrors());
        }

        return redirect()->back()->withInput();
    }

public function editCar($carId)
{
    $car = $this->carModel->find($carId);
    
    if (!$car) {
        session()->setFlashdata('error', 'Car not found');
        return redirect()->to('/admin/manageCars');
    }

    // Get car statistics from models
    $reservationModel = new \App\Models\ReservationModel();
    $paymentModel = new \App\Models\PaymentModel();
    
    // Get total reservations for this car
    $totalReservations = $reservationModel->where('car_id', $carId)
        ->where('status !=', 'cancelled')
        ->countAllResults();
    
    // Get total revenue from this car
    $totalRevenue = $paymentModel->getRevenueByCar($carId);
    
    // Calculate utilization rate (last 30 days)
    $startDate = date('Y-m-d', strtotime('-30 days'));
    $endDate = date('Y-m-d');
    $utilizationData = $this->carModel->getCarUtilization($carId, $startDate, $endDate);
    $utilizationRate = $utilizationData['utilization_rate'] ?? 0;

    $data = [
        'title' => 'Edit Car',
        'car' => $car,
        'carStats' => [
            'total_reservations' => $totalReservations,
            'total_revenue' => $totalRevenue,
            'utilization_rate' => $utilizationRate
        ]
    ];
    return view('admin/edit_car', $data);
}

public function updateCar($carId)
{
    // Custom validation rules for update
    $rules = [
        'brand' => 'required',
        'model' => 'required',
        'year' => 'required|numeric',
        'color' => 'required',
        'plate_number' => "required|is_unique[cars.plate_number,id,{$carId}]",
        'capacity' => 'required|numeric',
        'transmission' => 'required',
        'daily_rate' => 'required|numeric',
        'status' => 'required'
    ];

    if ($this->validate($rules)) {
        $carData = [
            'id' => $carId,
            'brand' => $this->request->getPost('brand'),
            'model' => $this->request->getPost('model'),
            'year' => $this->request->getPost('year'),
            'color' => $this->request->getPost('color'),
            'plate_number' => $this->request->getPost('plate_number'),
            'capacity' => $this->request->getPost('capacity'),
            'transmission' => $this->request->getPost('transmission'),
            'daily_rate' => $this->request->getPost('daily_rate'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move('./uploads/cars', $newName);
            $carData['image'] = $newName;
        }

        if ($this->carModel->save($carData)) {
            session()->setFlashdata('success', 'Car updated successfully');
            return redirect()->to('/admin/manageCars');
        } else {
            session()->setFlashdata('error', 'Failed to update car');
        }
    } else {
        session()->setFlashdata('error', $this->validator->listErrors());
    }

    return redirect()->back()->withInput();
}

    public function deleteCar($carId)
    {
        $car = $this->carModel->find($carId);
        
        if (!$car) {
            session()->setFlashdata('error', 'Car not found');
            return redirect()->to('/admin/manageCars');
        }

        // Check if car has active reservations
        $activeReservations = $this->reservationModel->where('car_id', $carId)
            ->whereIn('status', ['pending', 'confirmed', 'ongoing'])
            ->countAllResults();

        if ($activeReservations > 0) {
            session()->setFlashdata('error', 'Cannot delete car with active reservations');
            return redirect()->to('/admin/manageCars');
        }

        if ($this->carModel->delete($carId)) {
            session()->setFlashdata('success', 'Car deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete car');
        }

        return redirect()->to('/admin/manageCars');
    }

public function manageCustomers()
{
    $pager = \Config\Services::pager();
    $perPage = 10;
    
    // Get filter parameters
    $search = $this->request->getGet('search');
    $sort = $this->request->getGet('sort') ?? 'newest';
    
    // Build query
    $userModel = new UserModel();
    $reservationModel = new ReservationModel();
    
    $builder = $userModel->where('role', 'customer');
    
    // Apply search filter
    if (!empty($search)) {
        $builder->groupStart()
            ->like('name', $search)
            ->orLike('email', $search)
            ->orLike('phone', $search)
            ->groupEnd();
    }
    
    // Apply sorting
    switch ($sort) {
        case 'oldest':
            $builder->orderBy('created_at', 'ASC');
            break;
        case 'name':
            $builder->orderBy('name', 'ASC');
            break;
        case 'activity':
            // For activity-based sorting, we'll sort by creation date as a simple approach
            $builder->orderBy('created_at', 'DESC');
            break;
        default: // newest
            $builder->orderBy('created_at', 'DESC');
            break;
    }
    
    // Get paginated results
    $customers = $builder->paginate($perPage);
    $pager = $builder->pager;
    
    // Calculate stats
    $totalCustomers = $userModel->where('role', 'customer')->countAllResults();
    $activeReservations = $reservationModel->whereIn('status', ['pending', 'confirmed', 'ongoing'])->countAllResults();
    
    $totalReservations = $reservationModel->countAllResults();
    $avgReservationsPerCustomer = $totalCustomers > 0 ? round($totalReservations / $totalCustomers, 1) : 0;
    
    $newCustomersThisMonth = $userModel->where('role', 'customer')
        ->where('YEAR(created_at)', date('Y'))
        ->where('MONTH(created_at)', date('m'))
        ->countAllResults();
    
    $data = [
        'title' => 'Manage Customers',
        'customers' => $customers,
        'pager' => $pager,
        'totalCustomers' => $totalCustomers,
        'activeReservations' => $activeReservations,
        'avgReservationsPerCustomer' => $avgReservationsPerCustomer,
        'newCustomersThisMonth' => $newCustomersThisMonth,
        'currentFilters' => [
            'search' => $search,
            'sort' => $sort
        ],
        'userModel' => $userModel
    ];
    
    return view('admin/customers_management', $data);
}

public function getCustomerDetails($customerId)
{
    $userModel = new UserModel();
    $reservationModel = new ReservationModel();
    
    try {
        $customer = $userModel->find($customerId);
        
        if (!$customer || $customer['role'] !== 'customer') {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Customer not found'
            ]);
        }
        
        // Get customer stats
        $stats = $userModel->getCustomerStats($customerId);
        
        // Get recent reservations with proper status
        $recentReservations = $reservationModel->select('reservations.*, cars.brand, cars.model, cars.image')
            ->join('cars', 'cars.id = reservations.car_id')
            ->where('reservations.user_id', $customerId)
            ->orderBy('reservations.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'customer' => [
                'id' => $customer['id'],
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'address' => $customer['address'],
                'created_at' => $customer['created_at']
            ],
            'stats' => $stats,
            'recentReservations' => $recentReservations
        ]);
        
    } catch (\Exception $e) {
        log_message('error', 'Error loading customer details: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false, 
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

public function manageReservations()
{
    $pager = \Config\Services::pager();
    $perPage = 10; // Reservations per page
    
    // Get filter parameters
    $search = $this->request->getGet('search');
    $status = $this->request->getGet('status');
    $dateFilter = $this->request->getGet('date_filter');
    
    // Build query
    $reservationModel = $this->reservationModel
        ->select('reservations.*, users.name as customer_name, users.email, cars.brand, cars.model, cars.plate_number')
        ->join('users', 'users.id = reservations.user_id')
        ->join('cars', 'cars.id = reservations.car_id');
    
    // Apply search filter
    if (!empty($search)) {
        $reservationModel->groupStart()
            ->like('users.name', $search)
            ->orLike('users.email', $search)
            ->orLike('cars.brand', $search)
            ->orLike('cars.model', $search)
            ->orLike('cars.plate_number', $search)
            ->orLike('reservations.id', $search)
            ->groupEnd();
    }
    
    // Apply status filter
    if (!empty($status) && $status !== 'all') {
        $reservationModel->where('reservations.status', $status);
    }
    
    // Apply date filter
    if (!empty($dateFilter)) {
        $today = date('Y-m-d');
        switch ($dateFilter) {
            case 'today':
                $reservationModel->where('DATE(reservations.created_at)', $today);
                break;
            case 'week':
                $startOfWeek = date('Y-m-d', strtotime('monday this week'));
                $reservationModel->where('reservations.created_at >=', $startOfWeek);
                break;
            case 'month':
                $startOfMonth = date('Y-m-01');
                $reservationModel->where('reservations.created_at >=', $startOfMonth);
                break;
        }
    }
    
    // Get paginated results
    $reservations = $reservationModel->orderBy('reservations.created_at', 'DESC')
        ->paginate($perPage);
    
    $pager = $reservationModel->pager;
    
    // Get status counts for the overview cards
    $statusCounts = $this->getReservationStatusCounts($search, $dateFilter);
    
    $data = [
        'title' => 'Manage Reservations',
        'reservations' => $reservations,
        'pager' => $pager,
        'statusCounts' => $statusCounts,
        'currentFilters' => [
            'search' => $search,
            'status' => $status,
            'date_filter' => $dateFilter
        ]
    ];
    
    return view('admin/reservations_management', $data);
}

private function getReservationStatusCounts($search = null, $dateFilter = null)
{
    $counts = [
        'total' => 0,
        'pending' => 0,
        'confirmed' => 0,
        'ongoing' => 0,
        'completed' => 0,
        'cancelled' => 0
    ];
    
    // Get total count with filters
    $totalQuery = $this->reservationModel
        ->join('users', 'users.id = reservations.user_id')
        ->join('cars', 'cars.id = reservations.car_id');
    
    // Apply the same filters as main query
    if (!empty($search)) {
        $totalQuery->groupStart()
            ->like('users.name', $search)
            ->orLike('users.email', $search)
            ->orLike('cars.brand', $search)
            ->orLike('cars.model', $search)
            ->orLike('cars.plate_number', $search)
            ->orLike('reservations.id', $search)
            ->groupEnd();
    }
    
    if (!empty($dateFilter)) {
        $today = date('Y-m-d');
        switch ($dateFilter) {
            case 'today':
                $totalQuery->where('DATE(reservations.created_at)', $today);
                break;
            case 'week':
                $startOfWeek = date('Y-m-d', strtotime('monday this week'));
                $totalQuery->where('reservations.created_at >=', $startOfWeek);
                break;
            case 'month':
                $startOfMonth = date('Y-m-01');
                $totalQuery->where('reservations.created_at >=', $startOfMonth);
                break;
        }
    }
    
    // Get total count
    $counts['total'] = $totalQuery->countAllResults();
    
    // Get counts for each status - create new query for each status
    $statuses = ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled'];
    foreach ($statuses as $status) {
        $statusQuery = $this->reservationModel
            ->join('users', 'users.id = reservations.user_id')
            ->join('cars', 'cars.id = reservations.car_id')
            ->where('reservations.status', $status);
        
        // Apply the same filters
        if (!empty($search)) {
            $statusQuery->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('cars.brand', $search)
                ->orLike('cars.model', $search)
                ->orLike('cars.plate_number', $search)
                ->orLike('reservations.id', $search)
                ->groupEnd();
        }
        
        if (!empty($dateFilter)) {
            $today = date('Y-m-d');
            switch ($dateFilter) {
                case 'today':
                    $statusQuery->where('DATE(reservations.created_at)', $today);
                    break;
                case 'week':
                    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
                    $statusQuery->where('reservations.created_at >=', $startOfWeek);
                    break;
                case 'month':
                    $startOfMonth = date('Y-m-01');
                    $statusQuery->where('reservations.created_at >=', $startOfMonth);
                    break;
            }
        }
        
        $counts[$status] = $statusQuery->countAllResults();
    }
    
    return $counts;
}

    public function updateReservationStatus($reservationId)
    {
        $status = $this->request->getPost('status');
        $reservation = $this->reservationModel->find($reservationId);
        
        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/admin/manageReservations');
        }

        if ($this->reservationModel->update($reservationId, ['status' => $status])) {
            session()->setFlashdata('success', 'Reservation status updated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update reservation status');
        }

        return redirect()->to('/admin/manageReservations');
    }

// In Admin.php controller, update the managePayments() method:
public function managePayments()
{
    $pager = \Config\Services::pager();
    $perPage = 10;
    
    // Get filter parameters
    $search = $this->request->getGet('search');
    $status = $this->request->getGet('status');
    $method = $this->request->getGet('method');
    
    // Build base query
    $paymentModel = $this->paymentModel;
    
    // Start with all payments with details
    $allPayments = $paymentModel->getPaymentsWithDetails();
    
    // Apply filters
    $filteredPayments = $allPayments;
    
    if (!empty($search)) {
        $filteredPayments = array_filter($filteredPayments, function($payment) use ($search) {
            return stripos($payment['transaction_id'] ?? '', $search) !== false ||
                   stripos($payment['customer_name'] ?? '', $search) !== false ||
                   stripos($payment['email'] ?? '', $search) !== false ||
                   stripos($payment['brand'] ?? '', $search) !== false ||
                   stripos($payment['model'] ?? '', $search) !== false ||
                   stripos((string)$payment['amount'], $search) !== false;
        });
    }
    
    if (!empty($status) && $status !== 'all') {
        $filteredPayments = array_filter($filteredPayments, function($payment) use ($status) {
            return $payment['status'] === $status;
        });
    }
    
    if (!empty($method) && $method !== 'all') {
        $filteredPayments = array_filter($filteredPayments, function($payment) use ($method) {
            return $payment['payment_method'] === $method;
        });
    }
    
    // Reset array keys
    $filteredPayments = array_values($filteredPayments);
    
    // Manual pagination
    $totalPayments = count($filteredPayments);
    $currentPage = $this->request->getGet('page') ?? 1;
    $offset = ($currentPage - 1) * $perPage;
    $payments = array_slice($filteredPayments, $offset, $perPage);
    
    // Create manual pager configuration
    $pager->makeLinks($currentPage, $perPage, $totalPayments);
    
    // Calculate statistics from ALL payments (not filtered)
    $paymentStats = $this->paymentModel->getPaymentStats();
    $monthlyStats = $this->paymentModel->getPaymentStats(date('Y-m-01'), date('Y-m-t'));
    
    // Calculate additional stats
    $totalTransactions = $paymentStats['total_payments'];
    $avgTransaction = $totalTransactions > 0 ? $paymentStats['total_revenue'] / $totalTransactions : 0;
    $successRate = $totalTransactions > 0 ? ($paymentStats['completed_payments'] / $totalTransactions) * 100 : 0;
    $refundCount = $this->paymentModel->where('status', 'refunded')->countAllResults();
    
    // ✅ FIXED: Get payment method distribution for ALL TIME (not just current month)
    $paymentMethods = $this->paymentModel->select('payment_method, COUNT(*) as count, SUM(amount) as total_amount')
        ->where('status', 'completed')
        ->groupBy('payment_method')
        ->orderBy('total_amount', 'DESC')  // Optional: order by highest revenue
        ->get()
        ->getResultArray();

    $data = [
        'title' => 'Manage Payments',
        'payments' => $payments,
        'pager' => $pager,
        'totalPayments' => $totalPayments,
        'totalRevenue' => $paymentStats['total_revenue'] ?? 0,
        'monthlyRevenue' => $monthlyStats['total_revenue'] ?? 0,
        'pendingAmount' => $this->calculatePendingAmount(),
        'failedAmount' => $this->calculateFailedAmount(),
        'paymentMethods' => $paymentMethods,  // Now shows all-time methods
        'avgTransaction' => $avgTransaction,
        'successRate' => round($successRate, 1),
        'refundCount' => $refundCount,
        'totalTransactions' => $totalTransactions,
        'currentFilters' => [
            'search' => $search,
            'status' => $status,
            'method' => $method
        ]
    ];
    
    return view('admin/payments_management', $data);
}

private function calculatePendingAmount()
{
    $result = $this->paymentModel->selectSum('amount')
        ->where('status', 'pending')
        ->get()
        ->getRow();
    
    return $result->amount ?? 0;
}

private function calculateFailedAmount()
{
    $result = $this->paymentModel->selectSum('amount')
        ->where('status', 'failed')
        ->get()
        ->getRow();
    
    return $result->amount ?? 0;
}

// In your Admin.php controller, update the reports method:

public function reports()
{
    $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
    $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
    $reportType = $this->request->getGet('report_type') ?? 'overview';

    // Get real data from models
    $reservationModel = new ReservationModel();
    $paymentModel = new PaymentModel();
    $carModel = new CarModel();
    $userModel = new UserModel();

    $reportsData = $reservationModel->getReports($startDate, $endDate);
    
    // Get payment statistics
    $paymentStats = $paymentModel->getPaymentStats($startDate, $endDate);
    
    // Get car statistics
    $carStats = $carModel->getCarStatistics();
    
    // Get ACTIVE CUSTOMERS count (customers with reservations in date range)
    $activeCustomers = $userModel->where('role', 'customer')
        ->whereIn('id', function($builder) use ($startDate, $endDate) {
            return $builder->select('user_id')
                ->from('reservations')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->where('status !=', 'cancelled');
        })
        ->countAllResults();

    // Calculate revenue change for the view
    $revenueChange = $this->calculateRevenueChange();

    $data = [
        'title' => 'Analytics & Reports',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'report_type' => $reportType,
        'revenue_change' => $revenueChange,
        'active_customers' => $activeCustomers, // Add this
        'reports' => array_merge($reportsData, [
            'payment_stats' => $paymentStats,
            'car_stats' => $carStats,
            'active_customers_count' => $activeCustomers // Add this too
        ])
    ];
    
    return view('admin/reports', $data);
}

private function calculateRevenueChange()
{
    $paymentModel = new PaymentModel();
    
    $currentMonthRevenue = $paymentModel->selectSum('amount')
        ->where('status', 'completed')
        ->where('YEAR(payment_date)', date('Y'))
        ->where('MONTH(payment_date)', date('m'))
        ->get()->getRow()->amount ?? 0;
        
    $previousMonthRevenue = $paymentModel->selectSum('amount')
        ->where('status', 'completed')
        ->where('YEAR(payment_date)', date('Y', strtotime('-1 month')))
        ->where('MONTH(payment_date)', date('m', strtotime('-1 month')))
        ->get()->getRow()->amount ?? 0;
    
    if ($previousMonthRevenue > 0) {
        return round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1);
    }
    
    return $currentMonthRevenue > 0 ? 100 : 0;
}

    public function viewReservation($reservationId)
{
    $reservation = $this->reservationModel->getReservationWithDetails($reservationId);
    
    if (!$reservation) {
        session()->setFlashdata('error', 'Reservation not found');
        return redirect()->to('/admin/manageReservations');
    }

    // Get payment information
    $payment = $this->paymentModel->getPaymentByReservation($reservationId);

    $data = [
        'title' => 'View Reservation',
        'reservation' => $reservation,
        'payment' => $payment
    ];
    
    return view('admin/reservation_view', $data);
}

// Add this method to your Admin controller
public function printReceipt($paymentId)
{
    // Get payment details with all necessary information
    $payment = $this->paymentModel->getPaymentWithDetails($paymentId);
    
    if (!$payment) {
        session()->setFlashdata('error', 'Payment not found');
        return redirect()->to('/admin/managePayments');
    }

    // Get reservation details
    $reservation = $this->reservationModel->find($payment['reservation_id']);
    
    // Calculate rental days
    $start = strtotime($reservation['start_date']);
    $end = strtotime($reservation['end_date']);
    $rentalDays = ($end - $start) / (60 * 60 * 24) + 1;
    
    // Calculate tax (assuming 10% tax rate)
    $taxAmount = $payment['amount'] * 0.1;

    $data = [
        'title' => 'Print Receipt',
        'payment' => $payment,
        'reservation' => $reservation,
        'rentalDays' => $rentalDays,
        'taxAmount' => $taxAmount
    ];
    
    return view('admin/print_receipt', $data);
}

// Add this method to your Admin controller
public function printReport()
{
    $reportType = $this->request->getGet('type') ?? 'overview';
    $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
    $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
    
    // Get data based on report type
    $data = [
        'title' => ucfirst($reportType) . ' Report',
        'report_type' => $reportType,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'print_mode' => true
    ];
    
    // Add specific data based on report type
    switch ($reportType) {
        case 'car-management':
            $carModel = new CarModel();
            $data['car_stats'] = $carModel->getCarStatistics();
            $data['popular_cars'] = $carModel->getCarsByStatus('available');
            break;
            
        case 'reservations':
            $reservationModel = new ReservationModel();
            $data['reservation_stats'] = $reservationModel->getReservationStats($startDate, $endDate);
            $data['active_reservations'] = $reservationModel->getActiveReservations();
            break;
            
        case 'customers':
            $userModel = new UserModel();
            $data['customer_stats'] = $userModel->getCustomers();
            $data['new_customers'] = $userModel->where('role', 'customer')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->findAll();
            break;
            
        case 'payments':
            $paymentModel = new PaymentModel();
            $data['payment_stats'] = $paymentModel->getPaymentStats($startDate, $endDate);
            $data['payment_methods'] = $paymentModel->getPaymentsByStatus('completed');
            break;
    }
    
    return view('admin/print_reports/' . $reportType . '_report', $data);
}

public function printCarManagementReport()
{
    $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
    $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
    
    // Get car statistics
    $carModel = new CarModel();
    $carStats = $carModel->getCarStatistics();
    
    // Get all cars for the detailed list
    $popularCars = $carModel->orderBy('brand', 'ASC')
                           ->orderBy('model', 'ASC')
                           ->findAll();

    $data = [
        'title' => 'Car Management Report',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'car_stats' => $carStats,
        'popular_cars' => $popularCars,
        'print_mode' => true
    ];
    
    return view('admin/print_reports/car_management_report', $data);
}
// In Admin.php - printReservationsReport() method
public function printReservationsReport()
{
    $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
    $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
    
    // Get current filters
    $search = $this->request->getGet('search');
    $status = $this->request->getGet('status');
    
    // Get reservation statistics - FIXED: Call the proper method
    $reservationModel = new ReservationModel();
    $reservationStats = $reservationModel->getReservationStats($startDate, $endDate);
    
    // Get active reservations with details
    $activeReservations = $reservationModel
        ->select('reservations.*, users.name as customer_name, users.email, cars.brand, cars.model, cars.plate_number')
        ->join('users', 'users.id = reservations.user_id')
        ->join('cars', 'cars.id = reservations.car_id')
        ->where('reservations.created_at >=', $startDate)
        ->where('reservations.created_at <=', $endDate)
        ->orderBy('reservations.created_at', 'DESC')
        ->findAll();

    $data = [
        'title' => 'Reservations Report',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'reservation_stats' => $reservationStats, // This should now contain the proper data
        'active_reservations' => $activeReservations,
        'current_filters' => [
            'search' => $search,
            'status' => $status
        ],
        'print_mode' => true
    ];
    
    return view('admin/print_reports/reservations_report', $data);
}
public function printCustomersReport()
{
    $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
    $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
    
    $userModel = new UserModel();
    $reservationModel = new ReservationModel();
    
    // Get customer statistics
    $totalCustomers = $userModel->where('role', 'customer')->countAllResults();
    $newCustomers = $userModel->where('role', 'customer')
        ->where('created_at >=', $startDate)
        ->where('created_at <=', $endDate)
        ->countAllResults();
    
    // Get active customers (customers with reservations)
    $activeCustomers = $userModel->where('role', 'customer')
        ->whereIn('id', function($builder) use ($startDate, $endDate) {
            return $builder->select('user_id')
                ->from('reservations')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->where('status !=', 'cancelled');
        })
        ->countAllResults();
    
    // Calculate average reservations per customer
    $totalReservations = $reservationModel->where('created_at >=', $startDate)
        ->where('created_at <=', $endDate)
        ->countAllResults();
    $avgReservations = $totalCustomers > 0 ? round($totalReservations / $totalCustomers, 1) : 0;
    
    // Get new customers for the period
    $newCustomersList = $userModel->where('role', 'customer')
        ->where('created_at >=', $startDate)
        ->where('created_at <=', $endDate)
        ->orderBy('created_at', 'DESC')
        ->findAll();
    
    // Get top customers by spending
    $topCustomers = $userModel->getTopCustomersBySpending($startDate, $endDate);

    $data = [
        'title' => 'Customers Report',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'customer_stats' => [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'avg_reservations' => $avgReservations,
            'new_customers' => $newCustomers,
            'top_customers' => $topCustomers
        ],
        'new_customers' => $newCustomersList,
        'userModel' => $userModel,
        'print_mode' => true
    ];
    
    return view('admin/print_reports/customers_report', $data);
}

public function printPaymentsReport()
{
    $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
    $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');
    
    $paymentModel = new PaymentModel();
    
    // Get payment statistics
    $paymentStats = $paymentModel->getPaymentStats($startDate, $endDate);
    
    // Get payment methods distribution
    $paymentMethods = $paymentModel->select('payment_method, COUNT(*) as count, SUM(amount) as total_amount')
        ->where('payment_date >=', $startDate)
        ->where('payment_date <=', $endDate)
        ->groupBy('payment_method')
        ->get()
        ->getResultArray();
    
    // Get recent payments for the period - FIXED: Use the model directly with where conditions
    $recentPayments = $paymentModel->select('payments.*, users.name as customer_name, users.email, cars.brand, cars.model, reservations.id as reservation_id')
        ->join('reservations', 'reservations.id = payments.reservation_id')
        ->join('users', 'users.id = reservations.user_id')
        ->join('cars', 'cars.id = reservations.car_id')
        ->where('payments.payment_date >=', $startDate)
        ->where('payments.payment_date <=', $endDate)
        ->orderBy('payments.payment_date', 'DESC')
        ->findAll();

    // Calculate additional stats
    $totalTransactions = $paymentStats['total_payments'] ?? 0;
    $avgTransaction = $totalTransactions > 0 ? ($paymentStats['total_revenue'] ?? 0) / $totalTransactions : 0;
    $successRate = $totalTransactions > 0 ? (($paymentStats['completed_payments'] ?? 0) / $totalTransactions) * 100 : 0;
    
    $refundCount = $paymentModel->where('status', 'refunded')
        ->where('payment_date >=', $startDate)
        ->where('payment_date <=', $endDate)
        ->countAllResults();
    
    $pendingAmount = $paymentModel->selectSum('amount')
        ->where('status', 'pending')
        ->where('payment_date >=', $startDate)
        ->where('payment_date <=', $endDate)
        ->get()
        ->getRow()->amount ?? 0;
    
    $failedAmount = $paymentModel->selectSum('amount')
        ->where('status', 'failed')
        ->where('payment_date >=', $startDate)
        ->where('payment_date <=', $endDate)
        ->get()
        ->getRow()->amount ?? 0;

    $data = [
        'title' => 'Payments Report',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'payment_stats' => array_merge($paymentStats, [
            'monthly_revenue' => $paymentStats['total_revenue'] ?? 0,
            'pending_amount' => $pendingAmount,
            'failed_amount' => $failedAmount,
            'avg_transaction' => $avgTransaction,
            'success_rate' => round($successRate, 1),
            'refund_count' => $refundCount,
            'total_transactions' => $totalTransactions
        ]),
        'payment_methods' => $paymentMethods,
        'recent_payments' => $recentPayments, // Add this for the transaction details table
        'print_mode' => true
    ];
    
    return view('admin/print_reports/payments_report', $data);
}

public function getPaymentDetails($paymentId)
{
    $paymentModel = new PaymentModel();
    $payment = $paymentModel->getPaymentWithDetails($paymentId);
    
    if (!$payment) {
        return $this->response->setJSON(['error' => 'Payment not found'])->setStatusCode(404);
    }
    
    return $this->response->setJSON($payment);
}

public function profile()
{
    $userId = session()->get('userId');
    $user = $this->userModel->find($userId);
    
    if (!$user) {
        session()->setFlashdata('error', 'User not found');
        return redirect()->to('/admin/dashboard');
    }

    $data = [
        'title' => 'My Profile',
        'user' => $user
    ];
    
    return view('admin/profile', $data);
}

public function updateProfile()
{
    $userId = session()->get('userId');
    
    $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'phone' => 'required',
        'address' => 'required'
    ];
    
    // Check if email is being changed
    $currentEmail = session()->get('email');
    $newEmail = $this->request->getPost('email');
    
    if ($newEmail != $currentEmail) {
        $validationRules['email'] = 'required|valid_email|is_unique[users.email]';
    }
    
    if ($this->validate($validationRules)) {
        $updateData = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Only update email if it changed
        if ($newEmail != $currentEmail) {
            $updateData['email'] = $newEmail;
        }
        
        if ($this->userModel->update($userId, $updateData)) {
            // Update session data
            session()->set('name', $updateData['name']);
            if (isset($updateData['email'])) {
                session()->set('email', $updateData['email']);
            }
            
            session()->setFlashdata('success', 'Profile updated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update profile');
        }
    } else {
        session()->setFlashdata('error', $this->validator->listErrors());
    }
    
    return redirect()->to('/admin/profile');
}

public function changePassword()
{
    $userId = session()->get('userId');
    
    $validationRules = [
        'current_password' => 'required',
        'new_password' => 'required|min_length[6]',
        'confirm_password' => 'required|matches[new_password]'
    ];
    
    if ($this->validate($validationRules)) {
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        
        // Verify current password
        $user = $this->userModel->find($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Current password is incorrect');
            return redirect()->to('/admin/profile');
        }
        
        // Update password - The model's beforeUpdate hook will hash it automatically
        if ($this->userModel->update($userId, ['password' => $newPassword])) {
            session()->setFlashdata('success', 'Password changed successfully');
        } else {
            session()->setFlashdata('error', 'Failed to change password');
        }
    } else {
        session()->setFlashdata('error', $this->validator->listErrors());
    }
    
    return redirect()->to('/admin/profile');
}
}
