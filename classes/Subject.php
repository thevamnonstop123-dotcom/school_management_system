<?php
require_once __DIR__ . '/core/Model.php';

class Subject extends Model {
    protected $table = "subjects";

    // Basic CRUD
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

    /**
     * Get courses with schedule and enrollment data for table display
     */
    public function getCoursesForTable($offset, $limit) {
        $sql = "SELECT s.*, 
                       sch.start_time, 
                       sch.end_time, 
                       sch.day_of_week,
                       COUNT(e.enrollment_id) as student_count
                FROM {$this->table} s
                LEFT JOIN schedules sch ON s.subject_id = sch.subject_id
                LEFT JOIN enrollments e ON s.subject_id = e.subject_id
                GROUP BY s.subject_id
                ORDER BY s.created_at DESC 
                LIMIT :offset, :limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search courses with schedule and enrollment data
     */
    public function searchCoursesForTable($search, $offset, $limit) {
        $sql = "SELECT s.*, 
                       sch.start_time, 
                       sch.end_time, 
                       sch.day_of_week,
                       COUNT(e.enrollment_id) as student_count
                FROM {$this->table} s
                LEFT JOIN schedules sch ON s.subject_id = sch.subject_id
                LEFT JOIN enrollments e ON s.subject_id = e.subject_id
                WHERE s.title LIKE :search OR s.description LIKE :search
                GROUP BY s.subject_id
                ORDER BY s.created_at DESC 
                LIMIT :offset, :limit";
        
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count search results
     */
    public function countSearchCourses($search) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE title LIKE :search OR description LIKE :search";
        
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Get total number of courses
     */
    public function getTotalCourses() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Get enrollment count for a course
     */
    public function getEnrollmentCount($subject_id) {
        $sql = "SELECT COUNT(*) as total FROM enrollments WHERE subject_id = :subject_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':subject_id' => $subject_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get total students across all courses
     */
    public function getTotalStudentsAcrossCourses() {
        $sql = "SELECT COUNT(DISTINCT student_id) as total FROM enrollments";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Get most popular course
     */
    public function getMostPopularCourse() {
        $sql = "SELECT s.title, COUNT(e.enrollment_id) as student_count
                FROM {$this->table} s
                LEFT JOIN enrollments e ON s.subject_id = e.subject_id
                GROUP BY s.subject_id
                ORDER BY student_count DESC
                LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
 * Get course details with schedule information
 */
public function getCourseWithSchedules($course_id) {
    $sql = "SELECT s.*, 
                   sch.schedule_id,
                   sch.day_of_week,
                   sch.start_time,
                   sch.end_time,
                   sch.status as schedule_status,
                   r.room_name,
                   t.full_name as trainer_name,
                   t.specialization as trainer_specialization,
                   b.branch_name
            FROM {$this->table} s
            LEFT JOIN schedules sch ON s.subject_id = sch.subject_id
            LEFT JOIN rooms r ON sch.room_id = r.room_id
            LEFT JOIN trainers t ON sch.trainer_id = t.trainer_id
            LEFT JOIN branches b ON sch.branch_id = b.branch_id
            WHERE s.subject_id = :course_id
            ORDER BY sch.day_of_week, sch.start_time";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':course_id' => $course_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get enrolled students for a course
 */
public function getEnrolledStudents($course_id) {
    $sql = "SELECT stu.student_id,
                   stu.sid_code,
                   stu.first_name,
                   stu.last_name,
                   stu.email,
                   stu.profile_image,
                   e.enrollment_date,
                   e.status as enrollment_status,
                   e.progress
            FROM enrollments e
            JOIN students stu ON e.student_id = stu.student_id
            WHERE e.subject_id = :course_id
            ORDER BY e.enrollment_date DESC";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':course_id' => $course_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get course statistics
 */
public function getCourseStats($course_id) {
    $sql = "SELECT 
                COUNT(DISTINCT e.student_id) as total_students,
                COUNT(DISTINCT sch.schedule_id) as total_schedules,
                AVG(e.progress) as avg_progress,
                SUM(CASE WHEN e.status = 'active' THEN 1 ELSE 0 END) as active_enrollments,
                SUM(CASE WHEN e.status = 'completed' THEN 1 ELSE 0 END) as completed_enrollments
            FROM {$this->table} s
            LEFT JOIN enrollments e ON s.subject_id = e.subject_id
            LEFT JOIN schedules sch ON s.subject_id = sch.subject_id
            WHERE s.subject_id = :course_id
            GROUP BY s.subject_id";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':course_id' => $course_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}