<?php require_once '../../classes/Trainer.php';

if (isset($_GET['id'])) {
    $trainerObj = new Trainer();
    $trainerId = $_GET['id'];

    if ($trainerObj->delete($trainerId)) {
        $_SESSION['flash_message'] = "Trainer removed successfully!";
        $_SESSION['flash_type'] = "delete";
        header("Location: trainer.php");
        exit();
    } else {
        echo "Error Could not delete trainer.";
    }
} else {
    header("Location: trainer.php");
    exit();
}

