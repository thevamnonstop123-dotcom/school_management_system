<?php
require_once __DIR__ . '/core/Model.php';

class Schedule extends Model {
    protected $table = "schedules";

    public function getAllSchedules() {
        $sql = "SELECT s.*, 
                       sub.title as subject_name, 
                       t.full_name as trainer_name, 
                       r.room_name as room_name 
                FROM {$this->table} s
                JOIN subjects sub ON s.subject_id = sub.subject_id
                JOIN trainers t ON s.trainer_id = t.trainer_id
                JOIN rooms r ON s.room_id = r.room_id
                ORDER BY s.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (room_id, trainer_id, subject_id, branch_id, day_of_week, start_time, end_time, status) 
                VALUES (:room_id, :trainer_id, :subject_id, :branch_id, :day_of_week, :start_time, :end_time, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function deleteSchedule($id) {
        $query = "DELETE FROM schedules WHERE schedule_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([":id" => $id]);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE schedule_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                room_id = :room_id, 
                trainer_id = :trainer_id, 
                subject_id = :subject_id, 
                branch_id = :branch_id, 
                day_of_week = :day_of_week, 
                start_time = :start_time, 
                end_time = :end_time, 
                status = :status 
                WHERE schedule_id = :id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function getTotalCount() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getSchedulesWithPagination($offset, $limit) {
        $sql = "SELECT s.*, t.full_name as trainer_name, sub.title, r.room_name 
                FROM {$this->table} s
                LEFT JOIN trainers t ON s.trainer_id = t.trainer_id
                LEFT JOIN subjects sub ON s.subject_id = sub.subject_id
                LEFT JOIN rooms r ON s.room_id = r.room_id
                ORDER BY s.schedule_id DESC 
                LIMIT :limit OFFSET :offset";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchWithPagination($term, $offset, $limit) {
        $sql = "SELECT s.*, t.full_name as trainer_name, sub.title, r.room_name 
                FROM {$this->table} s
                LEFT JOIN trainers t ON s.trainer_id = t.trainer_id
                LEFT JOIN subjects sub ON s.subject_id = sub.subject_id
                LEFT JOIN rooms r ON s.room_id = r.room_id
                WHERE sub.title LIKE :term 
                OR t.full_name LIKE :term
                ORDER BY s.schedule_id DESC
                LIMIT :limit OFFSET :offset";
                
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$term%";
        
        $stmt->bindValue(':term', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSearch($term) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} s
                LEFT JOIN trainers t ON s.trainer_id = t.trainer_id
                LEFT JOIN subjects sub ON s.subject_id = sub.subject_id
                WHERE sub.title LIKE :term OR t.full_name LIKE :term";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':term' => "%$term%"]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}