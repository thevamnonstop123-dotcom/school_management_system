<?php
require_once __DIR__.  "/core/Model.php";
class Student extends Model {
    private $table = "students";

    //  ID: STU + YEAR + SEQUENCE
    public function generateStudentID() {
        $year = date('Y');
        $prefix = "STU" . $year;
        
        // Find the last student added this year
        $sql = "SELECT sid_code FROM {$this->table}
                  WHERE sid_code LIKE :prefix 
                  ORDER BY sid_code DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $searchTerm = $prefix . "%";
        $stmt->bindParam(':prefix', $searchTerm);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $lastNumber = substr($row['sid_code'], 7); // Get the '001' part
            $newNumber = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = "001";
        }

        return $prefix . $newNumber;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (sid_code, first_name, last_name, email, dob, address, phone, password, profile_image, status) 
                VALUES 
                (:sid_code, :first_name, :last_name, :email, :dob, :address, :phone, :password, :profile_image, 'Pending')";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':sid_code', $data['sid_code']);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':dob', $data['dob']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':profile_image', $data['profile_image']);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

        public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE student_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMyClasses($student_id) {
        $sql = "SELECT e.*, s.title, s.fee, t.full_name as trainer_name,
                    sched.day_of_week, sched.start_time, sched.end_time
                FROM enrollments e
                JOIN subjects s ON e.subject_id = s.subject_id
                JOIN schedules sched ON s.subject_id = sched.subject_id
                JOIN trainers t ON sched.trainer_id = t.trainer_id
                WHERE e.student_id = :student_id AND e.status = 'active'
                ORDER BY e.enrollment_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartItems($student_id) {
        $sql = "SELECT c.*, s.title, s.fee, s.description
                FROM cart_items c
                JOIN subjects s ON c.subject_id = s.subject_id
                WHERE c.student_id = :student_id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addToCart($student_id, $subject_id) {
        $check_sql = "SELECT * FROM cart_items WHERE student_id = :student_id AND subject_id = :subject_id";
        $check_stmt = $this->conn->prepare($check_sql);
        $check_stmt->execute([
            ':student_id' => $student_id,
            ':subject_id' => $subject_id
        ]);
        
        if ($check_stmt->rowCount() > 0) {
            return true;
        }

        $sql = "INSERT INTO cart_items (student_id, subject_id) VALUES (:student_id, :subject_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':student_id' => $student_id,
            ':subject_id' => $subject_id
        ]);
    }

    public function removeFromCart($cart_id) {
        $sql = "DELETE FROM cart_items WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':cart_id' => $cart_id]);
    }

    /**
     * Get student's class schedule from database
     */
    public function getStudentSchedule($student_id) {
        $sql = "SELECT 
                    sched.schedule_id,
                    sched.day_of_week,
                    sched.start_time,
                    sched.end_time,
                    sub.title as course_name,
                    sub.description,
                    t.full_name as instructor_name,
                    r.room_name,
                    sched.status
                FROM schedules sched
                JOIN enrollments e ON sched.subject_id = e.subject_id
                JOIN subjects sub ON sched.subject_id = sub.subject_id
                JOIN trainers t ON sched.trainer_id = t.trainer_id
                JOIN rooms r ON sched.room_id = r.room_id
                WHERE e.student_id = :student_id 
                AND e.status = 'active'
                ORDER BY 
                    FIELD(sched.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                    sched.start_time ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
        * Format schedule data for display
    */
    public function formatScheduleForDisplay($student_id) {
        $schedule_data = $this->getStudentSchedule($student_id);
        
        // Initialize empty schedule for all days
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $formatted = [];
        
        foreach($days as $day) {
            $formatted[$day] = [];
        }
    
    // Populate with real data
        foreach($schedule_data as $class) {
            $day = $class['day_of_week'];
            $formatted[$day][] = [
                'time' => date('H:i', strtotime($class['start_time'])) . ' - ' . date('H:i', strtotime($class['end_time'])),
                'course' => $class['course_name'],
                'instructor' => $class['instructor_name'],
                'room' => $class['room_name'],
                'status' => strtolower(str_replace(' ', '_', $class['status']))
            ];
        }
        
        return $formatted;
    }

    /**
     * Get all available subjects (courses) for IT Classes page
     */
    public function getAllSubjects() {
        $sql = "SELECT * FROM subjects ORDER BY title ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get student's enrolled classes for My Class page
     */
    public function getMyEnrolledClasses($student_id) {
        $sql = "SELECT 
                    e.*,
                    sub.title,
                    sub.fee,
                    sub.description,
                    sub.image_path,
                    t.full_name as instructor_name,
                    sched.day_of_week,
                    sched.start_time,
                    sched.end_time,
                    COALESCE(e.progress, 0) as progress,
                    DATEDIFF(sched.end_time, NOW()) as weeks_left
                FROM enrollments e
                JOIN subjects sub ON e.subject_id = sub.subject_id
                LEFT JOIN schedules sched ON sub.subject_id = sched.subject_id
                LEFT JOIN trainers t ON sched.trainer_id = t.trainer_id
                WHERE e.student_id = :student_id
                ORDER BY e.enrollment_date DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function enrollInCourse($student_id, $subject_id) {
    $sql = "INSERT INTO enrollments (student_id, subject_id, status, progress, enrollment_date) 
            VALUES (:student_id, :subject_id, 'active', 0, NOW())";
    
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        ':student_id' => $student_id,
        ':subject_id' => $subject_id
    ]);
}

/**
 * Get students with pagination
 */
public function getStudentsWithPagination($offset, $limit) {
    $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :offset, :limit";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Search students with pagination
 */
public function searchStudents($search, $offset, $limit) {
    $sql = "SELECT * FROM {$this->table} 
            WHERE first_name LIKE :search 
            OR last_name LIKE :search 
            OR email LIKE :search 
            OR sid_code LIKE :search
            ORDER BY created_at DESC 
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
 * Count search results for students
 */
public function countSearchStudents($search) {
    $sql = "SELECT COUNT(*) as total FROM {$this->table} 
            WHERE first_name LIKE :search 
            OR last_name LIKE :search 
            OR email LIKE :search 
            OR sid_code LIKE :search";
    
    $stmt = $this->conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

/**
 * Get total number of students
 */
public function getTotalStudents() {
    $sql = "SELECT COUNT(*) as total FROM {$this->table}";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

/**
 * Get enrollment count for a student
 */
public function getEnrollmentCount($student_id) {
    $sql = "SELECT COUNT(*) as total FROM enrollments WHERE student_id = :student_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

/**
 * Update student status (Active/Inactive/Pending)
 */
public function updateStatus($student_id, $status) {
    $sql = "UPDATE {$this->table} SET status = :status WHERE student_id = :student_id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        ':student_id' => $student_id,
        ':status' => $status
    ]);
}

/**
 * Get monthly enrollment trends for dashboard
 */
public function getMonthlyEnrollments($months = 6) {
    $sql = "SELECT 
                DATE_FORMAT(enrollment_date, '%b') as month,
                COUNT(*) as count
            FROM enrollments
            WHERE enrollment_date >= DATE_SUB(NOW(), INTERVAL :months MONTH)
            GROUP BY MONTH(enrollment_date)
            ORDER BY enrollment_date ASC";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':months', (int)$months, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get recent enrollments for dashboard
 */
public function getRecentEnrollments($limit = 5) {
    $sql = "SELECT 
                e.*,
                stu.first_name,
                stu.last_name,
                sub.title as subject_title,
                p.status as payment_status
            FROM enrollments e
            JOIN students stu ON e.student_id = stu.student_id
            JOIN subjects sub ON e.subject_id = sub.subject_id
            LEFT JOIN payments p ON e.student_id = p.student_id AND e.subject_id = p.subject_id
            ORDER BY e.enrollment_date DESC
            LIMIT :limit";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}