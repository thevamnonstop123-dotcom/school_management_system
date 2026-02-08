<?php
require_once '../../classes/Trainer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_trainer'])) {
    
    $trainerObj = new Trainer();
    
    $name           = $_POST['full_name']    ?? '';
    $email          = $_POST['email']        ?? '';
    $phone          = $_POST['phone_number'] ?? ''; 
    $specialization = $_POST['specialization'] ?? '';
    $status         = "Active";

    if (empty($name) || empty($phone) || empty($email)) {
        die("Please fill in all required fields.");
    }

    $avatar = "default-avatar.png"; // Start with default

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        
        $fileName = time() . "_" . basename($_FILES['avatar']['name']);
        $targetPath = "../../assets/images/trainers/" . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $avatar = $fileName; // Save the filename to store in the database
        }
    }

    if ($trainerObj->create($name, $email, $phone, $specialization, $status, $avatar)) {
        $_SESSION['flash_message'] = "Trainer added successfully!";
        $_SESSION['flash_type'] = "success";
        header("Location: trainer.php");
        exit();
    } else {
        echo "Error: Could not save the trainer.";
    }
}