<?php
session_start();
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Subject.php';

$studentObj = new Student();
$student_id = $_SESSION['student_id'] ?? 0;

if (!$student_id) {
    header("Location: ../auth/login.php");
    exit();
}

$action = $_GET['action'] ?? '';
$subject_id = $_GET['subject_id'] ?? 0;
$cart_id = $_GET['cart_id'] ?? 0;

if ($action == 'add' && $subject_id) {
    // Add to cart
    $result = $studentObj->addToCart($student_id, $subject_id);
    
    if ($result) {
        // Get updated cart count
        $cart_items = $studentObj->getCartItems($student_id);
        $_SESSION['cart_count'] = count($cart_items);
        
        // Redirect back to IT Classes page
        header("Location: ../index.php?view=it_classes&msg=added");
        exit();
    } else {
        header("Location: ../index.php?view=it_classes&msg=error");
        exit();
    }
}

if ($action == 'remove' && $cart_id) {
    $studentObj->removeFromCart($cart_id);
    
    // Update cart count in session
    $cart_items = $studentObj->getCartItems($student_id);
    $_SESSION['cart_count'] = count($cart_items);
    
    header("Location: ../index.php?view=cart&msg=removed");
    exit();
}

header("Location: ../index.php?view=it_classes");
exit();
?>