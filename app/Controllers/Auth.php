<?php
namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to($this->getDashboardRedirect());
        }

        $data = [
            'title' => 'Login - Car Rental'
        ];
        return view('auth/login', $data);
    }

    public function processLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyCredentials($email, $password);

        if ($user) {
            $sessionData = [
                'userId' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];
            session()->set($sessionData);

            return redirect()->to($this->getDashboardRedirect());
        } else {
            session()->setFlashdata('error', 'Invalid email or password');
            return redirect()->back()->withInput();
        }
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to($this->getDashboardRedirect());
        }

        $data = [
            'title' => 'Register - Car Rental'
        ];
        return view('auth/register', $data);
    }

    public function processRegister()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'phone' => 'required',
            'address' => 'required'
        ];

        if ($this->validate($rules)) {
            $userData = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'phone' => $this->request->getPost('phone'),
                'address' => $this->request->getPost('address'),
                'role' => 'customer',
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->userModel->save($userData)) {
                session()->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to('/auth/login');
            } else {
                session()->setFlashdata('error', 'Registration failed. Please try again.');
            }
        } else {
            session()->setFlashdata('error', $this->validator->listErrors());
        }

        return redirect()->back()->withInput();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }

    private function getDashboardRedirect()
    {
        $role = session()->get('role');
        if ($role === 'admin') {
            return '/admin/dashboard';
        } else {
            return '/customer/dashboard';
        }
    }
}