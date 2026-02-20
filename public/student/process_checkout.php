<?php
session_start();
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Payment.php';

$studentObj = new Student();
$paymentObj = new Payment();

$student_id = $_SESSION['student_id'] ?? 0;

if (!$student_id) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_purchase'])) {
    
    // Get cart items from Student model
    $cart_items = $studentObj->getCartItems($student_id);
    
    // Create payment record for each item using Payment model
   foreach($cart_items as $item) {
        $payment_data = [
            'student_id' => $student_id,
            'subject_id' => $item['subject_id'],
            'total_amount' => $item['fee'], 
            'course_fee' => $item['fee'],
            'registration_fee' => 0.00,
            'status' => 'pending'
        ];
        
        $paymentObj->create($payment_data);
        
        // Remove from cart
        $studentObj->removeFromCart($item['cart_id']);
    }
    
    header("Location: ../index.php?view=my_class&msg=payment_pending");
    exit();
}

header("Location: ../index.php?view=cart");
exit();
?>