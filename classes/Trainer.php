<?php
    require_once __DIR__ . "/core/Model.php";

    class Trainer extends Model {
        private $table = "trainers";

        public function create($name, $email, $phone, $specialization, $status, $avatar_url) {
            $sql = "INSERT INTO {$this->table}(full_name, email,specialization, status,phone_number,avatar_url)
            VALUES (:full_name, :email, :specialization, :status, :phone_number, :avatar_url)";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':full_name' => $name,
                ':email' => $email,
                ':specialization' => $specialization,
                ':status' => $status,
                ':phone_number' => $phone,
                ':avatar_url' => $avatar_url
            ]);
        }

        public function getAllTrainers() {
            $sql = "SELECT * FROM {$this->table} ORDER BY trainer_id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function delete($id) {
            $sql = "DELETE FROM {$this->table} WHERE trainer_id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        }

        public function getTrainerById($id) {
            $sql = "SELECT * FROM {$this->table} WHERE trainer_id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function update($id, $name, $email, $specialization, $phone, $avatar_url) {
        $sql = "UPDATE {$this->table} 
                SET full_name = :name, 
                    email = :email, 
                    specialization = :specialization,
                    phone_number = :phone,
                    avatar_url = :avatar 
                WHERE trainer_id = :id";
                
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':name'           => $name,
            ':email'          => $email,
            ':specialization' => $specialization,
            ':phone'          => $phone,
            ':avatar'         => $avatar_url,
            ':id'             => $id
        ]);
        }

        public function search($term) {
            $sql = "SELECT * FROM {$this->table}
                    WHERE full_name LIKE :term
                    OR email LIKE :term
                    OR specialization LIKE :term
                    ORDER BY trainer_id DESC";
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%$term%";
            $stmt->execute([':term' => $searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getTrainersWithPagination($offset, $limit) {
        $sql = "SELECT * FROM {$this->table} 
                ORDER BY trainer_id DESC 
                LIMIT :limit OFFSET :offset";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchWithPagination($term, $offset, $limit) {
        $sql = "SELECT * FROM {$this->table}
                WHERE full_name LIKE :term
                OR email LIKE :term
                OR specialization LIKE :term
                ORDER BY trainer_id DESC
                LIMIT :limit OFFSET :offset";
                
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$term%";
        
        $stmt->bindValue(':term', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    }  
?>