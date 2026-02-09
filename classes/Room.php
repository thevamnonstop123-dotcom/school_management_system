<?php 
require_once __DIR__ . '/core/Model.php';

class Room extends Model {
    private $table = "rooms";

    public function create($name, $branch, $capacity) {
        $sql = "INSERT INTO {$this->table} (room_name, branch, capacity) VALUES (:name, :branch, :capacity)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => htmlspecialchars(strip_tags($name)),
            ':branch' => htmlspecialchars(strip_tags($branch)),
            ':capacity' => (int)$capacity
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY room_id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE room_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $branch, $capacity) {
        $sql = "UPDATE {$this->table} SET room_name = :name, branch = :branch, capacity = :capacity WHERE room_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => (int)$id,
            ':name' => htmlspecialchars(strip_tags($name)),
            ':branch' => htmlspecialchars(strip_tags($branch)),
            ':capacity' => (int)$capacity
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE room_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}