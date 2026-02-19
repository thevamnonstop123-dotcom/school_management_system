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

    // $stmt->execute([
    //     ":sid_code" => $data,
    //     ":first_name" => $data,
    //     ":last_name" => $data,
    //     ":email" => $data
    //     etc....
    // ]);

    if ($stmt->execute()) {
        return true;
    }
    return false;
}
}