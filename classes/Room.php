<?php 
require_once __DIR__ . '/core/Model.php';

class Room extends Model {
    private $table = "rooms";

    public function create($name, $branch_id, $capacity) {
        $sql = "INSERT INTO {$this->table} (room_name, branch_id, capacity) VALUES (:name, :branch_id, :capacity)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':branch_id' => $branch_id,
            ':capacity' => (int)$capacity
        ]);
    }

    public function getAll() {
        $sql = "SELECT r.*, b.branch_name 
                FROM {$this->table} r
                LEFT JOIN branches b ON r.branch_id = b.branch_id 
                ORDER BY r.room_id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT r.*, b.branch_name 
                FROM {$this->table} r
                LEFT JOIN branches b ON r.branch_id = b.branch_id 
                WHERE r.room_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $branch_id, $capacity) {
        $sql = "UPDATE {$this->table} SET room_name = :name, branch_id = :branch_id, capacity = :capacity WHERE room_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => (int)$id,
            ':name' => $name,
            ':branch_id' => $branch_id,
            ':capacity' => (int)$capacity
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE room_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}