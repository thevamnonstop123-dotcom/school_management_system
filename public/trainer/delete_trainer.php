<?php 
session_start();
require_once __DIR__ . '/../../classes/Trainer.php';

if (isset($_GET['id'])) {
    $trainerObj = new Trainer();
    $trainerId = $_GET['id'];
    
    // Get trainer info for the message
    $trainer = $trainerObj->getTrainerById($trainerId);
    
    if (!$trainer) {
        $_SESSION['flash_message'] = "Trainer not found.";
        $_SESSION['flash_type'] = "error";
        header("Location: ../index.php?view=trainers");
        exit();
    }
    
    $scheduleCount = $trainerObj->hasSchedules($trainerId);
    
    if ($scheduleCount > 0) {
        $schedules = $trainerObj->getTrainerSchedules($trainerId);
    
        $message = "Cannot delete trainer <strong>" . htmlspecialchars($trainer['full_name']) . "</strong> because they are assigned to $scheduleCount schedule(s).";
        
        if (!empty($schedules)) {
            $message .= "<br><br>Examples:";
            foreach ($schedules as $s) {
                $message .= "<br>• " . htmlspecialchars($s['subject_name'] ?? 'Unknown') . " on " . ($s['day_of_week'] ?? 'TBA');
            }
            if ($scheduleCount > 3) {
                $message .= "<br>• ... and " . ($scheduleCount - 3) . " more";
            }
        }
        
        $message .= "<br><br>Please reassign or delete these schedules first.";
        
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = "error";
        header("Location: ../index.php?view=trainers");
        exit();
    }
    
    // Delete avatar file - USING MODEL METHOD
    $trainerObj->deleteAvatarFile($trainer['avatar_url']);
    
    // No schedules found, safe to delete - USING MODEL METHOD
    if ($trainerObj->delete($trainerId)) {
        $_SESSION['flash_message'] = "Trainer <strong>" . htmlspecialchars($trainer['full_name']) . "</strong> removed successfully!";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Error deleting trainer.";
        $_SESSION['flash_type'] = "error";
    }
} else {
    $_SESSION['flash_message'] = "No trainer ID provided.";
    $_SESSION['flash_type'] = "error";
}

header("Location: ../index.php?view=trainers");
exit();
?>