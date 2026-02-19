<?php
require_once "../../classes/Student.php";

$student = new Student();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sid_code = $_POST['sid_code'];

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2. Handle Image Upload
    $profile_image = "default_student.png";
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../../assets/images/students/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $profile_image = $sid_code . "." . $file_extension;
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_dir . $profile_image);
    }

    // 3. CRITICAL: Define $data BEFORE calling create()
    $data = [
        'sid_code' => $sid_code,
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'dob' => $_POST['dob'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'password' => $hashed_password,
        'profile_image' => $profile_image
    ];

    if ($student->create($data)) {
        header("Location: login.php?msg=Registration successful!");
        exit();
    } else {
        echo "Error: Could not complete registration.";
    }
}