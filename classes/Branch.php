<?php 
require_once __DIR__ . '/core/Model.php';

class Branch extends Model {
    private $table = "branches";

    public function create($name, $location, $phone, $status) {
        $sql = "INSERT INTO {$this->table} (branch_name, location, phone, status) VALUES (:name, :location, :phone, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':location' => $location,
            ':phone' => $phone,
            ':status' => $status
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY branch_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE branch_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $location, $phone, $status) {
        $sql = "UPDATE {$this->table} SET branch_name = :name, location = :location, phone = :phone, status = :status WHERE branch_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => (int)$id,
            ':name' => $name,
            ':location' => $location,
            ':phone' => $phone,
            ':status' => $status
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE branch_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    public function search($term) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE branch_name LIKE :term 
                OR location LIKE :term 
                OR phone LIKE :term 
                ORDER BY branch_id DESC";
        
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$term%";
        $stmt->execute([':term' => $searchTerm]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStats() {
        $sqlTotal = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmtTotal = $this->conn->query($sqlTotal);
        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['count'];

        $sqlActive = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'Active'";
        $stmtActive = $this->conn->query($sqlActive);
        $active = $stmtActive->fetch(PDO::FETCH_ASSOC)['count'];

        $sqlMonth = "SELECT COUNT(*) as count FROM {$this->table} WHERE MONTH(created_at) = MONTH(CURRENT_DATE())";
        $stmtMonth = $this->conn->query($sqlMonth);
        $month = $stmtMonth->fetch(PDO::FETCH_ASSOC)['count'];

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'this_month' => $month
        ];
    }
}