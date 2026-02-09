<?php
session_start(); // for the flash message!
require_once '../../classes/Trainer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_trainer'])) {
    
    $trainerObj = new Trainer();
    
    // 1. Get all the data from the form
    $id             = $_POST['trainer_id']; // The hidden ID
    $name           = $_POST['full_name']    ?? '';
    $email          = $_POST['email']        ?? '';
    $phone          = $_POST['phone_number'] ?? ''; 
    $specialization = $_POST['specialization'] ?? '';
    
    // 2. This is the "Secret Sauce": Start with the OLD image name
    $avatar = $_POST['current_avatar'] ?? 'default-avatar.png';

    // 3. Check for a NEW image (Just like your Create method)
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        
        $fileName = time() . "_" . basename($_FILES['avatar']['name']);
        $targetPath = "../../assets/images/trainers/" . $fileName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $avatar = $fileName; // Overwrite the variable with the NEW filename
            
            //  Cleanup the old file if it's not the default
            $oldFile = "../../assets/images/trainers/" . $_POST['current_avatar'];
            if ($_POST['current_avatar'] !== 'default-avatar.png' && file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
    }

    if ($trainerObj->update($id, $name, $email, $specialization, $phone, $avatar)) {
        $_SESSION['flash_message'] = "Trainer updated successfully!";
        $_SESSION['flash_type'] = "success";
        header("Location: trainer.php");
        exit();
    } else {
        echo "Error: Could not update the trainer.";
    }
}