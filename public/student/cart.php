<?php
require_once __DIR__ . '/../../classes/Student.php';

$studentObj = new Student();
$student_id = $_SESSION['student_id'] ?? 0;
$cart_items = $studentObj->getCartItems($student_id);
$total = array_sum(array_column($cart_items, 'fee'));
?>

<div class="cart-container">
    <div class="page-header">
        <h1>Shopping Cart</h1>
        <p><?= count($cart_items) ?> item(s) in your cart</p>
    </div>

    <?php if(!empty($cart_items)): ?>
        <div class="cart-grid">
            <div class="cart-items">
                <?php foreach($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="item-info">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p><?= htmlspecialchars($item['description']) ?></p>
                        </div>
                        <div class="item-price">
                            $<?= number_format($item['fee'], 2) ?>
                        </div>
                        <a href="student/process_cart.php?action=remove&cart_id=<?= $item['cart_id'] ?>" class="btn-remove">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
                
                <a href="index.php?view=checkout" class="btn-checkout">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart fa-3x"></i>
            <p>Your cart is empty</p>
            <a href="index.php?view=it_classes" class="btn-browse">Browse Classes</a>
        </div>
    <?php endif; ?>
</div>