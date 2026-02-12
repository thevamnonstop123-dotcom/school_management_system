<?php
session_start();
require_once "../../classes/User.php";

$userModel = new User();

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $userModel->register($name, $email, $password);

    if($user) {
        header("Location: login.php?success=1");
    } else {
        header("Location: register.php?error=failed");
    }
    exit();
}