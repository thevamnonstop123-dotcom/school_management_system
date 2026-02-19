<?php
require_once __DIR__ . '/core/Model.php';

class Subject extends Model {
    protected $table = "subjects";

    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE subject_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} (title, description, fee, image_path) 
                  VALUES (:title, :description, :fee, :image_path)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function update($data) {
        $query = "UPDATE {$this->table} SET 
                  title = :title, 
                  description = :description, 
                  fee = :fee, 
                  image_path = :image_path 
                  WHERE subject_id = :subject_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE subject_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}