<?php
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Subject.php';

$studentObj = new Student();
$subjectObj = new Subject();

$student_id = $_SESSION['student_id'] ?? 0;
$student = $studentObj->getById($student_id);
$cart_items = $studentObj->getCartItems($student_id);
$total = array_sum(array_column($cart_items, 'fee'));
?>

<div class="checkout-container">
    <!-- Header -->
    <div class="page-header">
        <h1>Checkout</h1>
        <p>Complete your course enrolment</p>
    </div>

    <div class="checkout-grid">
        <!-- Left Column - Billing Information -->
        <div class="billing-section">
            <div class="section-card">
                <h2>Billing Information</h2>
                
                <form action="student/process_checkout.php" method="POST" class="billing-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name'] ?? '') ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name'] ?? '') ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($student['address'] ?? '') ?>" readonly>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" placeholder="New York">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" placeholder="NY">
                        </div>
                        <div class="form-group">
                            <label>ZIP Code</label>
                            <input type="text" name="zip" placeholder="10001">
                        </div>
                    </div>

                    <div class="order-summary-mobile">
                        <h3>Order Summary</h3>
                        <?php foreach($cart_items as $item): ?>
                            <div class="summary-item">
                                <span><?= htmlspecialchars($item['title']) ?></span>
                                <span>$<?= number_format($item['fee'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="summary-total">
                            <span>Total:</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>

                    <button type="submit" name="complete_purchase" class="btn-complete">
                        <i class="fas fa-lock"></i> Complete Purchase
                    </button>
                    <p class="secure-text">Secure 256-bit SSL encryption</p>
                </form>
            </div>
        </div>

        <!-- Right Column - Order Summary & What's Included -->
        <div class="order-section">
            <!-- Order Summary -->
            <div class="section-card order-card">
                <h2>Order Summary</h2>
                
                <?php foreach($cart_items as $item): ?>
                <div class="order-item">
                    <div class="item-details">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <p><?= htmlspecialchars($item['description'] ?? 'Comprehensive course') ?></p>
                        <span class="duration">12-week comprehensive course</span>
                    </div>
                    <div class="item-price">$<?= number_format($item['fee'], 2) ?></div>
                </div>
                <?php endforeach; ?>

                <div class="order-total">
                    <span>Total:</span>
                    <span class="total-amount">$<?= number_format($total, 2) ?></span>
                </div>
            </div>

            <!-- What's Included -->
            <div class="section-card included-card">
                <h2>What's Included</h2>
                <ul class="included-list">
                    <li><i class="fas fa-check-circle"></i> 12 weeks of live instruction</li>
                    <li><i class="fas fa-check-circle"></i> Project-based learning</li>
                    <li><i class="fas fa-check-circle"></i> 1-on-1 mentoring sessions</li>
                    <li><i class="fas fa-check-circle"></i> Certificate of completion</li>
                    <li><i class="fas fa-check-circle"></i> Lifetime access to materials</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-container {
    padding: 30px;
    background: #f8fafc;
    min-height: 100vh;
}

.page-header h1 {
    font-size: 24px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.page-header p {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 30px;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 24px;
}

.section-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.section-card h2 {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

/* Billing Form */
.billing-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    color: #1e293b;
    background: #f8fafc;
}

.form-group input:not([readonly]) {
    background: white;
    border-color: #d1d5db;
}

.form-group input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

/* Order Summary */
.order-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
}

.order-item:last-child {
    border-bottom: none;
}

.item-details h3 {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.item-details p {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 4px;
}

.duration {
    font-size: 12px;
    color: #94a3b8;
}

.item-price {
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    margin-top: 16px;
    border-top: 2px solid #e2e8f0;
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
}

.total-amount {
    color: #6366f1;
}

/* What's Included */
.included-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.included-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    color: #475569;
    font-size: 14px;
}

.included-list li i {
    color: #10b981;
    font-size: 16px;
}

/* Mobile Summary (hidden on desktop) */
.order-summary-mobile {
    display: none;
}

.btn-complete {
    width: 100%;
    padding: 14px;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
    margin-top: 20px;
}

.btn-complete:hover {
    background: #4f46e5;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(99,102,241,0.4);
}

.secure-text {
    text-align: center;
    font-size: 12px;
    color: #94a3b8;
    margin-top: 12px;
}

/* Responsive */
@media (max-width: 1024px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    
    .order-summary-mobile {
        display: block;
        margin: 20px 0;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
    }
    
    .order-section {
        display: none;
    }
}
</style>