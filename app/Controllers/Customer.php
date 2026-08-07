<?php
namespace App\Controllers;

use App\Models\CarModel;
use App\Models\ReservationModel;
use App\Models\PaymentModel;

class Customer extends BaseController
{
    protected $carModel;
    protected $reservationModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->carModel = new CarModel();
        $this->reservationModel = new ReservationModel();
        $this->paymentModel = new PaymentModel();
        
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'customer') {
            return redirect()->to('/auth/login');
        }
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Customer Dashboard',
            'user' => [
                'name' => session()->get('name'),
                'email' => session()->get('email')
            ],
            'cars' => $this->carModel->getAvailableCars()
        ];
        return view('customer/dashboard', $data);
    }

    public function carDetails($carId)
    {
        $car = $this->carModel->find($carId);
        
        if (!$car) {
            session()->setFlashdata('error', 'Car not found');
            return redirect()->to('/customer/dashboard');
        }

        $data = [
            'title' => $car['brand'] . ' ' . $car['model'],
            'car' => $car
        ];
        return view('customer/car_details', $data);
    }

    public function makeReservation($carId)
    {
        $car = $this->carModel->find($carId);
        
        if (!$car) {
            session()->setFlashdata('error', 'Car not found');
            return redirect()->to('/customer/dashboard');
        }

        $data = [
            'title' => 'Make Reservation',
            'car' => $car
        ];
        return view('customer/reservation_form', $data);
    }

public function processReservation()
{
    $rules = [
        'car_id' => 'required',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date'
    ];

    // Custom validation for date comparison
    $validation = \Config\Services::validation();
    $validation->setRules($rules);
    
    if ($validation->withRequest($this->request)->run()) {
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        
        // Manual date comparison
        if (strtotime($endDate) < strtotime($startDate)) {
            session()->setFlashdata('error', 'End date must be after start date');
            return redirect()->back()->withInput();
        }

        $carId = $this->request->getPost('car_id');
        
        // Rest of your existing code...
        if (!$this->carModel->isCarAvailable($carId, $startDate, $endDate)) {
            session()->setFlashdata('error', 'Car is not available for the selected dates');
            return redirect()->back()->withInput();
        }

        $totalCost = $this->reservationModel->calculateTotalCost($carId, $startDate, $endDate);

        $reservationData = [
            'user_id' => session()->get('userId'),
            'car_id' => $carId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_cost' => $totalCost,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->reservationModel->save($reservationData)) {
            $reservationId = $this->reservationModel->getInsertID();
            return redirect()->to("/customer/payment/{$reservationId}");
        } else {
            session()->setFlashdata('error', 'Failed to create reservation');
        }
    } else {
        session()->setFlashdata('error', $validation->getErrors());
    }

    return redirect()->back()->withInput();
}

    public function payment($reservationId)
    {
        $reservation = $this->reservationModel->find($reservationId);
        
        if (!$reservation || $reservation['user_id'] != session()->get('userId')) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/customer/dashboard');
        }

        $car = $this->carModel->find($reservation['car_id']);

        $data = [
            'title' => 'Payment',
            'reservation' => $reservation,
            'car' => $car
        ];
        return view('customer/payment', $data);
    }

    public function processPayment()
    {
        $reservationId = $this->request->getPost('reservation_id');
        $paymentMethod = $this->request->getPost('payment_method');

        $reservation = $this->reservationModel->find($reservationId);
        
        if (!$reservation || $reservation['user_id'] != session()->get('userId')) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/customer/dashboard');
        }

        // Process payment (simulated)
        $paymentData = [
            'reservation_id' => $reservationId,
            'amount' => $reservation['total_cost'],
            'payment_method' => $paymentMethod,
            'status' => 'completed',
            'transaction_id' => 'TXN_' . uniqid(),
            'payment_date' => date('Y-m-d H:i:s')
        ];

        if ($this->paymentModel->save($paymentData)) {
            // Update reservation status
            $this->reservationModel->update($reservationId, ['status' => 'confirmed']);
            
            session()->setFlashdata('success', 'Payment successful! Your reservation has been confirmed.');
            return redirect()->to('/customer/myReservations');
        } else {
            session()->setFlashdata('error', 'Payment failed. Please try again.');
            return redirect()->back();
        }
    }

    public function myReservations()
    {
        $userId = session()->get('userId');
        $reservations = $this->reservationModel->getUserReservations($userId);

        $data = [
            'title' => 'My Reservations',
            'reservations' => $reservations
        ];
        return view('customer/my_reservations', $data);
    }

    public function cancelReservation($reservationId)
    {
        $reservation = $this->reservationModel->find($reservationId);
        
        if (!$reservation || $reservation['user_id'] != session()->get('userId')) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/customer/myReservations');
        }

        if ($reservation['status'] !== 'pending' && $reservation['status'] !== 'confirmed') {
            session()->setFlashdata('error', 'Cannot cancel this reservation');
            return redirect()->to('/customer/myReservations');
        }

        $this->reservationModel->update($reservationId, ['status' => 'cancelled']);
        session()->setFlashdata('success', 'Reservation cancelled successfully');
        
        return redirect()->to('/customer/myReservations');
    }

    public function profile()
{
    $userModel = new \App\Models\UserModel();
    $userId = session()->get('userId');
    $user = $userModel->find($userId);
    
    if (!$user) {
        session()->setFlashdata('error', 'User not found');
        return redirect()->to('/customer/dashboard');
    }

    $data = [
        'title' => 'My Profile',
        'user' => $user
    ];
    
    return view('customer/profile', $data);
}

public function updateProfile()
{
    $userId = session()->get('userId');
    $userModel = new \App\Models\UserModel();
    
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
        
        if ($userModel->update($userId, $updateData)) {
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
    
    return redirect()->to('/customer/profile');
}

public function changePassword()
{
    $userId = session()->get('userId');
    $userModel = new \App\Models\UserModel();
    
    $validationRules = [
        'current_password' => 'required',
        'new_password' => 'required|min_length[6]',
        'confirm_password' => 'required|matches[new_password]'
    ];
    
    if ($this->validate($validationRules)) {
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        
        // Verify current password
        $user = $userModel->find($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Current password is incorrect');
            return redirect()->to('/customer/profile');
        }
        
        // Update password - The model's beforeUpdate hook will hash it automatically
        if ($userModel->update($userId, ['password' => $newPassword])) {
            session()->setFlashdata('success', 'Password changed successfully');
        } else {
            session()->setFlashdata('error', 'Failed to change password');
        }
    } else {
        session()->setFlashdata('error', $this->validator->listErrors());
    }
    
    return redirect()->to('/customer/profile');
}
}