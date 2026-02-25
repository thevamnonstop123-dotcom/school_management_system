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

        public function countSearchResults($term) {
            $sql = "SELECT COUNT(*) as total FROM {$this->table}
                    WHERE full_name LIKE :term
                    OR email LIKE :term
                    OR specialization LIKE :term";
            
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%$term%";
            $stmt->execute([':term' => $searchTerm]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
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

        /**
     * Check if trainer has any schedules
     */
    public function hasSchedules($trainer_id) {
        $sql = "SELECT COUNT(*) as count FROM schedules WHERE trainer_id = :trainer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':trainer_id' => $trainer_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get schedules for a trainer
     */
    public function getTrainerSchedules($trainer_id, $limit = 3) {
        $sql = "SELECT s.*, sub.title as subject_name, s.day_of_week, s.start_time
                FROM schedules s
                JOIN subjects sub ON s.subject_id = sub.subject_id
                WHERE s.trainer_id = :trainer_id
                LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':trainer_id', $trainer_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete trainer avatar file
     */
    public function deleteAvatarFile($avatar_url) {
        if (!empty($avatar_url) && $avatar_url != 'default-avatar.png') {
            $avatarPath = __DIR__ . '/../public/assets/images/trainers/' . $avatar_url;
            if (file_exists($avatarPath)) {
                return unlink($avatarPath);
            }
        }
        return true;
    }
 }  
?>