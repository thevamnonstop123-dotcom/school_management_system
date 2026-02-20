<?php
session_start();
require_once "../../classes/User.php";  // For admin/staff
require_once "../../classes/Student.php"; // For students

$userModel = new User();
$studentModel = new Student();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $userModel->login($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role']; 
        
        header("Location: ../index.php?view=trainer");
        exit();
    }
    
    $student = $studentModel->getByEmail($email);
    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
        $_SESSION['user_role'] = 'student'; // Set role to student
        
        header("Location: ../index.php?view=my_class");
        exit();
    }
    
    // If neither works, login failed
    header("Location: login.php?error=Invalid email or password");
    exit();
}