<?php
session_start();
require_once "../../classes/User.php"; 

$userModel = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $userModel->login($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role']; 
        $_SESSION['user_name'] = $user['full_name'];

        header("Location: ../index.php?view=trainers");
        exit();
    } else {
        header("Location: login.php?error=invalid");
        exit();
    }
}