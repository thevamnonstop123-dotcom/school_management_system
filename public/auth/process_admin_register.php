<?php
session_start();
require_once "../../classes/Admin.php";

$admin = new Admin();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_admin'])) {
    
    // Get form data
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone_number'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $admin_role = $_POST['admin_role'] ?? '';
    
    // Validation
    $errors = [];
    
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($password)) $errors[] = "Password is required";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";
    if (empty($admin_role)) $errors[] = "Admin role is required";
    
    // Check if email already exists
    $existingAdmin = $admin->getByEmail($email);
    if ($existingAdmin) {
        $errors[] = "Email already registered";
    }
    
    // If errors, redirect back with error messages
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: admin_register.php");
        exit();
    }
    
    // Prepare data for registration
    $data = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone_number' => $phone,
        'password' => $password,
        'admin_role' => $admin_role
    ];
    
    // Register admin
    if ($admin->register($data)) {
        $_SESSION['success_message'] = "Admin account created successfully! You can now login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['register_errors'] = ["Registration failed. Please try again."];
        header("Location: admin_register.php");
        exit();
    }
}

// If accessed directly without POST
header("Location: admin_register.php");
exit();
?>