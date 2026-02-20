<?php
session_start(); 
require_once __DIR__ . '/../../classes/Trainer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_trainer'])) {
    
    $trainerObj = new Trainer();
    
    $id             = $_POST['trainer_id'];
    $name           = $_POST['full_name']    ?? '';
    $email          = $_POST['email']        ?? '';
    $phone          = $_POST['phone_number'] ?? ''; 
    $specialization = $_POST['specialization'] ?? '';
    $avatar         = $_POST['current_avatar'] ?? 'default-avatar.png';

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $fileName = time() . "_" . basename($_FILES['avatar']['name']);
        $targetPath = "../../assets/images/trainers/" . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $avatar = $fileName;
            
            // Clean up old photo
            $oldFile = "../../assets/images/trainers/" . $_POST['current_avatar'];
            if ($_POST['current_avatar'] !== 'default-avatar.png' && file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
    }

    if ($trainerObj->update($id, $name, $email, $specialization, $phone, $avatar)) {
        $_SESSION['flash_message'] = "Trainer updated successfully!";
        $_SESSION['flash_type'] = "success";
        header("Location: ../index.php?view=trainers");
        exit();
    } else {
        echo "Error: Could not update the trainer.";
    }
}