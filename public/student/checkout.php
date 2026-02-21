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