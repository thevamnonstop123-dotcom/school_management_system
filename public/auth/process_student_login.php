<?php
session_start();
require_once "../../classes/Student.php";

$studentObj = new Student();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $student = $studentObj->getByEmail($email);
    
    if ($student && password_verify($password, $student['password'])) {

        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
        $_SESSION['student_email'] = $student['email'];
        $_SESSION['profile_image'] = $student['profile_image'];
        $_SESSION['user_role'] = 'student'; 
        
        header("Location: ../public/index.php?view=my_class");
        exit();
    } else {
        header("Location: login.php?error=Invalid email or password");
        exit();
    }
}