<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'email', 'password', 'phone', 'address', 
        'role', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'phone' => 'required',
        'address' => 'required',
        'role' => 'required|in_list[customer,admin]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['data']['password']);
        }
        return $data;
    }

    public function verifyCredentials($email, $password)
    {
        $user = $this->where('email', $email)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getCustomers()
    {
        return $this->where('role', 'customer')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getCustomerById($id)
    {
        return $this->where('role', 'customer')
                    ->where('id', $id)
                    ->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function updateUser($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteUser($id)
    {
        // Check if user has active reservations
        $reservationModel = new ReservationModel();
        $activeReservations = $reservationModel->where('user_id', $id)
            ->whereIn('status', ['pending', 'confirmed', 'ongoing'])
            ->countAllResults();

        if ($activeReservations > 0) {
            return false; // Cannot delete user with active reservations
        }

        return $this->delete($id);
    }

    public function getCustomerStats($customerId)
    {
        $reservationModel = new ReservationModel();
        $paymentModel = new PaymentModel();

        $totalReservations = $reservationModel->where('user_id', $customerId)->countAllResults();
        $completedReservations = $reservationModel->where('user_id', $customerId)
            ->where('status', 'completed')
            ->countAllResults();
        $totalSpent = $paymentModel->selectSum('amount')
            ->whereIn('reservation_id', function($builder) use ($customerId) {
                return $builder->select('id')
                    ->from('reservations')
                    ->where('user_id', $customerId);
            })
            ->get()
            ->getRow()->amount;

        return [
            'total_reservations' => $totalReservations,
            'completed_reservations' => $completedReservations,
            'total_spent' => $totalSpent ?? 0
        ];
    }

    public function searchCustomers($searchTerm)
    {
        return $this->where('role', 'customer')
                    ->groupStart()
                    ->like('name', $searchTerm)
                    ->orLike('email', $searchTerm)
                    ->orLike('phone', $searchTerm)
                    ->groupEnd()
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getTopCustomersBySpending($startDate = null, $endDate = null)
{
    $db = db_connect();
    
    $builder = $db->table('reservations r')
        ->select('u.name, u.email, u.id, COUNT(r.id) as reservation_count, SUM(r.total_cost) as total_spent')
        ->join('users u', 'u.id = r.user_id')
        ->where('r.status', 'completed')
        ->groupBy('r.user_id')
        ->orderBy('total_spent', 'DESC');
    
    if ($startDate && $endDate) {
        $builder->where('r.created_at >=', $startDate)
               ->where('r.created_at <=', $endDate);
    }
    
    return $builder->get()->getResultArray();
}
}