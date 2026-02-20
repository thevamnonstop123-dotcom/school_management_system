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

<style>
.cart-container {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
}

.cart-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-top: 24px;
}

.cart-items {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-info {
    flex: 1;
}

.item-info h3 {
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.item-info p {
    font-size: 13px;
    color: #64748b;
}

.item-price {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
}

.btn-remove {
    color: #ef4444;
    text-decoration: none;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.2s;
}

.btn-remove:hover {
    background: #fee2e2;
}

.cart-summary {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    height: fit-content;
}

.cart-summary h3 {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
    color: #64748b;
}

.summary-row.total {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
    margin-top: 12px;
}

.btn-checkout {
    display: block;
    text-align: center;
    padding: 14px;
    background: #6366f1;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    margin-top: 20px;
    transition: all 0.2s;
}

.btn-checkout:hover {
    background: #4f46e5;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(99,102,241,0.4);
}

.empty-cart {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 16px;
    color: #94a3b8;
}

.empty-cart i {
    color: #cbd5e1;
    margin-bottom: 16px;
}

.empty-cart p {
    font-size: 16px;
    margin-bottom: 20px;
}

.btn-browse {
    display: inline-block;
    padding: 12px 30px;
    background: #6366f1;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-browse:hover {
    background: #4f46e5;
    transform: translateY(-2px);
}
</style>