<?php
session_start();
require_once "../../classes/Admin.php";  
require_once "../../classes/Student.php"; 

$adminModel = new Admin();
$studentModel = new Student();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $admin = $adminModel->login($email, $password);
    if($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id']  = $admin['admin_id'];
        $_SESSION['user_name'] = $admin['first_name']. ' ' . $admin['last_name'];
        $_SESSION['user_role'] = $admin['admin_role'];

        header("Location: ../index.php?view=dashboard");
        exit();
    }
    
    $student = $studentModel->getByEmail($email);
    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
        
        $_SESSION['user_role'] = 'student';
        
        header("Location: ../index.php?view=my_class");
        exit();
    }
    
    // If neither works, login failed
    header("Location: login.php?error=Invalid email or password");
    exit();
}