<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'reservation_id', 'amount', 'payment_method', 'status',
        'transaction_id', 'payment_date', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'reservation_id' => 'required|numeric',
        'amount' => 'required|numeric',
        'payment_method' => 'required|in_list[cash,credit_card,debit_card,online]',
        'status' => 'required|in_list[pending,completed,failed,refunded]',
        'transaction_id' => 'permit_empty'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

// In PaymentModel.php, ensure this method exists:
public function getPaymentsWithDetails()
{
    return $this->select('payments.*, reservations.id as reservation_id, 
                         users.name as customer_name, users.email, 
                         cars.brand, cars.model, cars.plate_number')
                ->join('reservations', 'reservations.id = payments.reservation_id')
                ->join('users', 'users.id = reservations.user_id')
                ->join('cars', 'cars.id = reservations.car_id')
                ->orderBy('payments.created_at', 'DESC')
                ->findAll();
}

    public function getPaymentByReservation($reservationId)
    {
        return $this->where('reservation_id', $reservationId)->first();
    }

    public function getPaymentWithDetails($paymentId)
    {
        return $this->select('payments.*, reservations.start_date, reservations.end_date, reservations.total_cost, users.name as customer_name, users.email, users.phone, cars.brand, cars.model, cars.plate_number')
                    ->join('reservations', 'reservations.id = payments.reservation_id')
                    ->join('users', 'users.id = reservations.user_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->where('payments.id', $paymentId)
                    ->first();
    }

    public function getUserPayments($userId)
    {
        return $this->select('payments.*, reservations.start_date, reservations.end_date, cars.brand, cars.model')
                    ->join('reservations', 'reservations.id = payments.reservation_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->where('reservations.user_id', $userId)
                    ->orderBy('payments.created_at', 'DESC')
                    ->findAll();
    }

    public function getPaymentsByStatus($status)
    {
        return $this->select('payments.*, users.name as customer_name, cars.brand, cars.model')
                    ->join('reservations', 'reservations.id = payments.reservation_id')
                    ->join('users', 'users.id = reservations.user_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->where('payments.status', $status)
                    ->orderBy('payments.created_at', 'DESC')
                    ->findAll();
    }

    public function getPaymentStats($startDate = null, $endDate = null)
    {
        $builder = $this;

        if ($startDate && $endDate) {
            $builder->where('payment_date >=', $startDate)
                    ->where('payment_date <=', $endDate);
        }

        $totalPayments = $builder->countAllResults();
        $completedPayments = $builder->where('status', 'completed')->countAllResults();
        $pendingPayments = $builder->where('status', 'pending')->countAllResults();
        $failedPayments = $builder->where('status', 'failed')->countAllResults();

        // Calculate total revenue
        $revenue = $this->selectSum('amount')
                       ->where('status', 'completed');
        
        if ($startDate && $endDate) {
            $revenue->where('payment_date >=', $startDate)
                    ->where('payment_date <=', $endDate);
        }

        $totalRevenue = $revenue->get()->getRow()->amount ?? 0;

        // Payment method distribution
        $paymentMethods = $this->select('payment_method, COUNT(*) as count, SUM(amount) as total')
                              ->where('status', 'completed');
        
        if ($startDate && $endDate) {
            $paymentMethods->where('payment_date >=', $startDate)
                          ->where('payment_date <=', $endDate);
        }

        $paymentMethods = $paymentMethods->groupBy('payment_method')
                                        ->get()
                                        ->getResultArray();

        return [
            'total_payments' => $totalPayments,
            'completed_payments' => $completedPayments,
            'pending_payments' => $pendingPayments,
            'failed_payments' => $failedPayments,
            'total_revenue' => $totalRevenue,
            'payment_methods' => $paymentMethods
        ];
    }

    public function getMonthlyRevenue($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $db = db_connect();
        
        return $db->table('payments')
            ->select("DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as revenue")
            ->where('status', 'completed')
            ->where('YEAR(payment_date)', $year)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function processPayment($reservationId, $amount, $paymentMethod, $transactionId = null)
    {
        $paymentData = [
            'reservation_id' => $reservationId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'payment_date' => date('Y-m-d H:i:s')
        ];

        if ($this->save($paymentData)) {
            // Update reservation status
            $reservationModel = new ReservationModel();
            $reservationModel->update($reservationId, ['status' => 'confirmed']);
            
            return $this->getInsertID();
        }

        return false;
    }

    public function refundPayment($paymentId)
    {
        $payment = $this->find($paymentId);
        if (!$payment) {
            return false;
        }

        // Create refund record (you might want a separate refunds table in a real system)
        $refundData = [
            'reservation_id' => $payment['reservation_id'],
            'amount' => $payment['amount'],
            'payment_method' => $payment['payment_method'],
            'status' => 'refunded',
            'transaction_id' => 'REFUND_' . $payment['transaction_id'],
            'payment_date' => date('Y-m-d H:i:s')
        ];

        if ($this->save($refundData)) {
            // Update original payment status
            $this->update($paymentId, ['status' => 'refunded']);
            
            // Update reservation status
            $reservationModel = new ReservationModel();
            $reservationModel->update($payment['reservation_id'], ['status' => 'cancelled']);
            
            return true;
        }

        return false;
    }

    public function getDailyRevenue($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $result = $this->selectSum('amount')
                      ->where('status', 'completed')
                      ->where('DATE(payment_date)', $date)
                      ->get()
                      ->getRow();

        return $result->amount ?? 0;
    }

    public function searchPayments($searchTerm)
    {
        return $this->select('payments.*, users.name as customer_name, cars.brand, cars.model, reservations.id as reservation_id')
                    ->join('reservations', 'reservations.id = payments.reservation_id')
                    ->join('users', 'users.id = reservations.user_id')
                    ->join('cars', 'cars.id = reservations.car_id')
                    ->groupStart()
                    ->like('users.name', $searchTerm)
                    ->orLike('cars.brand', $searchTerm)
                    ->orLike('cars.model', $searchTerm)
                    ->orLike('payments.transaction_id', $searchTerm)
                    ->groupEnd()
                    ->orderBy('payments.created_at', 'DESC')
                    ->findAll();
    }

    public function getRevenueByCar($carId, $startDate = null, $endDate = null)
    {
        $builder = $this->selectSum('payments.amount', 'total_revenue')
                       ->join('reservations', 'reservations.id = payments.reservation_id')
                       ->where('reservations.car_id', $carId)
                       ->where('payments.status', 'completed');

        if ($startDate && $endDate) {
            $builder->where('payments.payment_date >=', $startDate)
                    ->where('payments.payment_date <=', $endDate);
        }

        $result = $builder->get()->getRow();
        return $result->total_revenue ?? 0;
    }
}