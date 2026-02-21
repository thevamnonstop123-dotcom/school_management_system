<?php
session_start();
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Payment.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$paymentObj = new Payment();
$studentObj = new Student();

$action = $_GET['action'] ?? '';
$payment_id = $_GET['id'] ?? 0;

if ($action == 'confirm' && $payment_id) {
    // Get payment details
    $payment = $paymentObj->getById($payment_id);
    
    if ($payment) {
        // Update payment status using Payment model
        $paymentObj->updateStatus($payment_id, 'confirmed', 'Payment confirmed by admin');
        
        // Enroll student using Student model
        $studentObj->enrollInCourse($payment['student_id'], $payment['subject_id']);
        
        header("Location: ../index.php?view=payment_confirm&msg=confirmed");
        exit();
    }
}

if ($action == 'reject' && $payment_id) {
    // Update payment status using Payment model
    $paymentObj->updateStatus($payment_id, 'rejected', 'Payment rejected by admin');
    
    header("Location: ../index.php?view=payment_confirm&msg=rejected");
    exit();
}

header("Location: ../index.php?view=payment_confirm");
exit();
?>