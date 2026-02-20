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

<style>
.payment-figma-container {
    padding: 30px;
    background: #f8fafc;
    min-height: 100vh;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Header */
.page-header {
    margin-bottom: 24px;
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
}

/* Breadcrumb */
.breadcrumb {
    margin-bottom: 24px;
    font-size: 14px;
    color: #64748b;
}

.breadcrumb a {
    color: #6366f1;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb span {
    color: #0f172a;
    font-weight: 500;
}

/* LIST VIEW */
.payments-list-view {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.list-header h2 {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
}

.pending-count {
    background: #eef2ff;
    color: #6366f1;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.payments-table {
    overflow-x: auto;
}

.payments-table table {
    width: 100%;
    border-collapse: collapse;
}

.payments-table th {
    text-align: left;
    padding: 12px 8px;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    border-bottom: 1px solid #e2e8f0;
}

.payments-table td {
    padding: 16px 8px;
    border-bottom: 1px solid #f1f5f9;
}

.student-cell {
    display: flex;
    flex-direction: column;
}

.student-cell strong {
    font-size: 14px;
    color: #0f172a;
}

.student-cell small {
    font-size: 12px;
    color: #64748b;
}

.payments-table .amount {
    font-weight: 600;
    color: #0f172a;
}

.btn-review {
    display: inline-block;
    padding: 6px 16px;
    background: #eef2ff;
    color: #6366f1;
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
}

.btn-review:hover {
    background: #6366f1;
    color: white;
}

.no-data {
    text-align: center;
    padding: 40px !important;
    color: #94a3b8;
}

.no-data i {
    font-size: 40px;
    color: #cbd5e1;
    margin-bottom: 12px;
}

/* SINGLE VIEW - EXACT FIGMA */
.payment-detail-figma {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 24px;
}

/* Left Panel */
.left-panel {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.info-card h2 {
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item .label {
    font-size: 12px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.info-item .value {
    font-size: 14px;
    font-weight: 500;
    color: #0f172a;
}

.payment-id {
    background: #eef2ff;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
    font-weight: 600;
    color: #6366f1;
}

/* Amount Card */
.amount-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.amount-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed #f1f5f9;
}

.amount-row.total {
    margin-top: 8px;
    padding-top: 16px;
    border-top: 2px solid #e2e8f0;
    border-bottom: none;
}

.amount-row.total .value {
    font-size: 20px;
    font-weight: 700;
    color: #6366f1;
}

/* Right Panel */
.right-panel {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.proof-card, .important-card, .actions-card, .notes-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.proof-card h2, .actions-card h2, .notes-card h3 {
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 16px;
}

.proof-content {
    background: #f8fafc;
    border-radius: 8px;
    padding: 16px;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.file-info i {
    font-size: 24px;
    color: #ef4444;
}

.file-info span {
    font-weight: 500;
    color: #0f172a;
}

.upload-date {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 16px;
}

.btn-download {
    display: block;
    text-align: center;
    padding: 12px;
    background: #6366f1;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.2s;
}

.btn-download:hover {
    background: #4f46e5;
}

/* Important Card */
.important-card {
    background: #fff7ed;
    border: 1px solid #ffedd5;
}

.important-card h3 {
    color: #9a3412;
    font-size: 15px;
    margin-bottom: 8px;
}

.important-card p {
    color: #7b341e;
    font-size: 13px;
    line-height: 1.5;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-confirm, .btn-reject {
    display: block;
    text-align: center;
    padding: 14px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-confirm {
    background: #dcfce7;
    color: #166534;
}

.btn-confirm:hover {
    background: #166534;
    color: white;
}

.btn-reject {
    background: #fee2e2;
    color: #991b1b;
}

.btn-reject:hover {
    background: #991b1b;
    color: white;
}

.btn-back {
    display: block;
    text-align: center;
    padding: 14px;
    background: #f1f5f9;
    color: #475569;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.2s;
}

.btn-back:hover {
    background: #e2e8f0;
}

/* Notes Card */
.notes-card textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 12px;
    resize: vertical;
    min-height: 80px;
}

.notes-card textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
}

.btn-save {
    width: 100%;
    padding: 12px;
    background: #f1f5f9;
    color: #475569;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
}

.btn-save:hover {
    background: #e2e8f0;
}

/* Not Found */
.not-found {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.not-found i {
    font-size: 48px;
    color: #ef4444;
    margin-bottom: 16px;
}

.not-found h2 {
    font-size: 20px;
    color: #0f172a;
    margin-bottom: 8px;
}

.not-found p {
    color: #64748b;
    margin-bottom: 24px;
}

/* Responsive */
@media (max-width: 1024px) {
    .payment-detail-figma {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>