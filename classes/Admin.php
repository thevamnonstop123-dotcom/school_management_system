<?php
require_once __DIR__ . "/core/Model.php";

class Admin extends Model {
    private $table = "admins";

    /**
     * Register a new admin
     */
    public function register($data) {
        $sql = "INSERT INTO {$this->table} 
                (first_name, last_name, email, phone_number, admin_role, password) 
                VALUES 
                (:first_name, :last_name, :email, :phone_number, :admin_role, :password)";
        
        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':phone_number' => $data['phone_number'],
            ':admin_role' => $data['admin_role'],
            ':password' => $hashedPassword
        ]);
    }

    /**
     * Login admin
     */
    public function login($email, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }

    /**
     * Get admin by email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get admin by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE admin_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all admins (for management)
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY admin_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update admin
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                first_name = :first_name,
                last_name = :last_name,
                email = :email,
                phone_number = :phone_number,
                admin_role = :admin_role
                WHERE admin_id = :admin_id";
        
        $stmt = $this->conn->prepare($sql);
        $data[':admin_id'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Delete admin
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE admin_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Update password
     */
    public function updatePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE {$this->table} SET password = :password WHERE admin_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':password' => $hashed
        ]);
    }
}