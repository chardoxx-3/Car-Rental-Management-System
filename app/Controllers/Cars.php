<?php
namespace App\Controllers;

use App\Models\CarModel;

class Cars extends BaseController
{
    protected $carModel;

    public function __construct()
    {
        $this->carModel = new CarModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Available Cars',
            'cars' => $this->carModel->getAvailableCars()
        ];
        
        if (session()->get('role') === 'admin') {
            return view('admin/cars_management', $data);
        } else {
            return view('customer/cars_list', $data);
        }
    }

    public function show($carId)
    {
        $car = $this->carModel->find($carId);
        
        if (!$car) {
            if (session()->get('role') === 'admin') {
                return redirect()->to('/admin/manageCars');
            } else {
                return redirect()->to('/customer/dashboard');
            }
        }

        $data = [
            'title' => $car['brand'] . ' ' . $car['model'],
            'car' => $car
        ];

        if (session()->get('role') === 'admin') {
            return view('admin/edit_car', $data);
        } else {
            return view('customer/car_details', $data);
        }
    }

    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'brand' => 'required',
            'model' => 'required',
            'plate_number' => 'required|is_unique[cars.plate_number]',
            'daily_rate' => 'required|numeric',
            'status' => 'required'
        ];

        if ($this->validate($rules)) {
            $carData = [
                'brand' => $this->request->getPost('brand'),
                'model' => $this->request->getPost('model'),
                'plate_number' => $this->request->getPost('plate_number'),
                'daily_rate' => $this->request->getPost('daily_rate'),
                'status' => $this->request->getPost('status'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->carModel->save($carData)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Car added successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add car']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => $this->validator->listErrors()]);
        }
    }

    public function update($carId)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'brand' => 'required',
            'model' => 'required',
            'plate_number' => "required|is_unique[cars.plate_number,id,{$carId}]",
            'daily_rate' => 'required|numeric',
            'status' => 'required'
        ];

        if ($this->validate($rules)) {
            $carData = [
                'id' => $carId,
                'brand' => $this->request->getPost('brand'),
                'model' => $this->request->getPost('model'),
                'plate_number' => $this->request->getPost('plate_number'),
                'daily_rate' => $this->request->getPost('daily_rate'),
                'status' => $this->request->getPost('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->carModel->save($carData)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Car updated successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update car']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => $this->validator->listErrors()]);
        }
    }

    public function delete($carId)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login');
        }

        $car = $this->carModel->find($carId);
        
        if (!$car) {
            return $this->response->setJSON(['success' => false, 'message' => 'Car not found']);
        }

        if ($this->carModel->delete($carId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Car deleted successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete car']);
        }
    }

    public function getAvailableCars()
    {
        $cars = $this->carModel->getAvailableCars();
        return $this->response->setJSON($cars);
    }

    public function checkAvailability($carId)
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $isAvailable = $this->carModel->isCarAvailable($carId, $startDate, $endDate);
        
        return $this->response->setJSON([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Car is available' : 'Car is not available for the selected dates'
        ]);
    }
}