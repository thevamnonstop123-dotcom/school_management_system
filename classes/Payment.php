<?php
require_once __DIR__ . "/core/Model.php";

class Payment extends Model {
    private $table = "payments";

    /**
     * Create a new payment record
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (student_id, subject_id, total_amount, course_fee, registration_fee, status, payment_date) 
                VALUES 
                (:student_id, :subject_id, :total_amount, :course_fee, :registration_fee, :status, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':student_id' => $data['student_id'],
            ':subject_id' => $data['subject_id'],
            ':total_amount' => $data['total_amount'],
            ':course_fee' => $data['course_fee'],
            ':registration_fee' => $data['registration_fee'] ?? 0,
            ':status' => $data['status']
        ]);
    }

    /**
     * Get payments by student ID
     */
    public function getByStudentId($student_id) {
        $sql = "SELECT p.*, s.title as subject_name 
                FROM {$this->table} p
                JOIN subjects s ON p.subject_id = s.subject_id
                WHERE p.student_id = :student_id
                ORDER BY p.payment_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get pending payments (for admin)
     */
    public function getPendingPayments() {
        $sql = "SELECT p.*, s.title as subject_name, 
                    stu.first_name, stu.last_name, stu.email,
                    p.total_amount as amount  
                FROM {$this->table} p
                JOIN subjects s ON p.subject_id = s.subject_id
                JOIN students stu ON p.student_id = stu.student_id
                WHERE p.status = 'pending'
                ORDER BY p.payment_date ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update payment status (confirm/reject)
     */
    public function updateStatus($payment_id, $status, $admin_notes = '') {
        $sql = "UPDATE {$this->table} 
                SET status = :status, admin_notes = :admin_notes 
                WHERE payment_id = :payment_id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':payment_id' => $payment_id,
            ':status' => $status,
            ':admin_notes' => $admin_notes
        ]);
    }

    /**
     * Get payment by ID
     */
    public function getById($payment_id) {
        $sql = "SELECT p.*, s.title as subject_name, 
                    stu.first_name, stu.last_name, stu.email, stu.phone, stu.address,
                    p.total_amount as amount
                FROM {$this->table} p
                JOIN subjects s ON p.subject_id = s.subject_id
                JOIN students stu ON p.student_id = stu.student_id
                WHERE p.payment_id = :payment_id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':payment_id' => $payment_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get payment statistics for dashboard
     */
    public function getStats() {
        $stats = [];
        
        // Total revenue
        $sql = "SELECT SUM(amount) as total FROM {$this->table} WHERE status = 'confirmed'";
        $stmt = $this->conn->query($sql);
        $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Pending payments count
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'pending'";
        $stmt = $this->conn->query($sql);
        $stats['pending_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
        
        // This month's revenue
        $sql = "SELECT SUM(amount) as total FROM {$this->table} 
                WHERE status = 'confirmed' 
                AND MONTH(payment_date) = MONTH(CURRENT_DATE())";
        $stmt = $this->conn->query($sql);
        $stats['monthly_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        return $stats;
    }
}