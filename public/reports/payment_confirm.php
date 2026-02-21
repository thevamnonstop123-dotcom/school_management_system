<?php
require_once __DIR__ . '/../../classes/Payment.php';
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Subject.php';

$paymentObj = new Payment();
$studentObj = new Student();
$subjectObj = new Subject();

$payment_id = $_GET['id'] ?? 0;

if ($payment_id) {
    // Get real payment data from database
    $payment = $paymentObj->getById($payment_id);
    
    if ($payment) {
        // Get additional student details
        $student = $studentObj->getById($payment['student_id']);
        $subject = $subjectObj->getById($payment['subject_id']);
        $mode = 'single';
    } else {
        // Payment not found
        $mode = 'not_found';
    }
} else {
    // List all pending payments
    $pending_payments = $paymentObj->getPendingPayments();
    $mode = 'list';
}
?>

<div class="payment-figma-container">
    <!-- Header -->
    <div class="page-header">
        <h1>Payment Confirmation</h1>
        <p>Verify and confirm student payment</p>
    </div>

    <?php if($mode == 'list'): ?>
        <!-- LIST VIEW - All Pending Payments -->
        <div class="payments-list-view">
            <div class="list-header">
                <h2>Pending Payments</h2>
                <span class="pending-count"><?= count($pending_payments) ?> pending</span>
            </div>
            
            <div class="payments-table">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pending_payments)): ?>
                            <?php foreach($pending_payments as $p): ?>
                                <tr>
                                    <td>
                                        <div class="student-cell">
                                            <strong><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></strong>
                                            <small><?= htmlspecialchars($p['email']) ?></small>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($p['subject_name']) ?></td>
                                   <td class="value amount">$<?= number_format($p['total_amount'] ?? 0, 2) ?></td>
                                    <td><?= date('M j, Y', strtotime($p['payment_date'])) ?></td>
                                    <td>
                                        <a href="index.php?view=payment_confirm&id=<?= $p['payment_id'] ?>" class="btn-review">
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">
                                    <i class="fas fa-check-circle"></i>
                                    <p>No pending payments</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif($mode == 'single'): ?>
        <!-- SINGLE VIEW - Payment Details (EXACT FIGMA MATCH) -->
        <div class="breadcrumb">
            <a href="index.php?view=dashboard">Dashboard</a> > 
            <a href="index.php?view=payment_confirm">Payments</a> > 
            <span>Confirm Payment</span>
        </div>

        <div class="payment-detail-figma">
            <!-- LEFT COLUMN -->
            <div class="left-panel">
                <!-- Student Information Card -->
                <div class="info-card">
                    <h2>Student Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Full Name</span>
                            <span class="value"><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Email Address</span>
                            <span class="value"><?= htmlspecialchars($payment['email']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Student ID</span>
                            <span class="value">STD-<?= str_pad($payment['student_id'], 4, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Phone Number</span>
                            <span class="value"><?= htmlspecialchars($student['phone'] ?? '+1 (555) 234-5678') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Enrolled Course</span>
                            <span class="value"><?= htmlspecialchars($payment['subject_name']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Enrolled Date</span>
                            <span class="value"><?= date('F j, Y', strtotime($payment['payment_date'])) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Card -->
                <div class="info-card">
                    <h2>Payment Details</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Payment ID</span>
                            <span class="value payment-id">PAY-<?= str_pad($payment['payment_id'], 6, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Transaction Reference</span>
                            <span class="value">TRX-<?= str_pad($payment['payment_id'], 8, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Payment Method</span>
                            <span class="value">Bank Transfer</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Transaction Type</span>
                            <span class="value">One-time Payment</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Payment Date</span>
                            <span class="value"><?= date('F j, Y', strtotime($payment['payment_date'])) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Payment Time</span>
                            <span class="value"><?= date('h:i A', strtotime($payment['payment_date'])) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="label">Bank Name</span>
                            <span class="value">First National Bank</span>
                        </div>
                    </div>
                </div>

                <!-- Total Amount Card -->
                <div class="amount-card">
                    <div class="amount-row">
                        <span class="label">Course Fee</span>
                        <span class="value">$<?= number_format($payment['total_amount'] - 50, 2) ?></span>
                    </div>
                    <div class="amount-row">
                        <span class="label">Registration Fee</span>
                        <span class="value">$50.00</span>
                    </div>
                    <div class="amount-row total">
                        <span class="label">Total Paid</span>
                        <span class="value">$<?= number_format($payment['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="right-panel">
                <!-- Payment Proof Card -->
                <div class="proof-card">
                    <h2>Payment Proof</h2>
                    <div class="proof-content">
                        <div class="file-info">
                            <i class="fas fa-file-pdf"></i>
                            <span>payment_receipt_<?= str_pad($payment['payment_id'], 3, '0', STR_PAD_LEFT) ?>.pdf</span>
                        </div>
                        <p class="upload-date">Uploaded on <?= date('F j, Y', strtotime($payment['payment_date'])) ?> at <?= date('h:i A', strtotime($payment['payment_date'])) ?></p>
                        <a href="#" class="btn-download">
                            <i class="fas fa-download"></i> Download Receipt
                        </a>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="important-card">
                    <h3>Important</h3>
                    <p>Please verify all payment details before confirming. Once confirmed, the student will receive an email notification and be enrolled in the course.</p>
                </div>

                <!-- Confirmation Actions -->
                <div class="actions-card">
                    <h2>Confirmation Actions</h2>
                    <div class="action-buttons">
                        <a href="reports/process_payment.php?action=confirm&id=<?= $payment['payment_id'] ?>" 
                           class="btn-confirm"
                           onclick="return confirm('Confirm this payment? The student will be enrolled.')">
                            <i class="fas fa-check-circle"></i> Confirm Payment
                        </a>
                        <a href="reports/process_payment.php?action=reject&id=<?= $payment['payment_id'] ?>"
                           class="btn-reject"
                           onclick="return confirm('Reject this payment?')">
                            <i class="fas fa-times-circle"></i> Reject Payment
                        </a>
                        <a href="index.php?view=payment_confirm" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- Admin Notes -->
                <div class="notes-card">
                    <h3>Admin Notes</h3>
                    <form action="process_payment_notes.php" method="POST">
                        <input type="hidden" name="payment_id" value="<?= $payment['payment_id'] ?>">
                        <textarea name="admin_notes" placeholder="Add notes or comments about this payment..."><?= htmlspecialchars($payment['admin_notes'] ?? '') ?></textarea>
                        <button type="submit" class="btn-save">Save Notes</button>
                    </form>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Payment Not Found -->
        <div class="not-found">
            <i class="fas fa-exclamation-circle"></i>
            <h2>Payment Not Found</h2>
            <p>The payment you're looking for doesn't exist or has been processed.</p>
            <a href="index.php?view=payment_confirm" class="btn-back">Back to Payments List</a>
        </div>
    <?php endif; ?>
</div>
