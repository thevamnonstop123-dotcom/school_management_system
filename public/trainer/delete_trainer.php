<?php 
session_start();
require_once __DIR__ . '/../../classes/Trainer.php';

if (isset($_GET['id'])) {
    $trainerObj = new Trainer();
    $trainerId = $_GET['id'];

    if ($trainerObj->delete($trainerId)) {
        $_SESSION['flash_message'] = "Trainer removed successfully!";
        $_SESSION['flash_type'] = "delete";
        header("Location: ../index.php?view=trainers");
        exit();
    }
}
header("Location: ../index.php?view=trainers");
exit();