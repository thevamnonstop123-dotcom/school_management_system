<?php
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Payment.php';

$studentObj = new Student();
$paymentObj = new Payment();

$student_id = $_SESSION['student_id'] ?? 0;

if (!$student_id) {
    header("Location: ../auth/login.php");
    exit();
}

$student = $studentObj->getById($student_id);
$payments = $paymentObj->getByStudentId($student_id);
?>

<div class="profile-container">
    <!-- Header -->
    <div class="page-header">
        <h1>Student Profile</h1>
        <p>Manage your personal information and view payment history</p>
    </div>

    <div class="profile-grid">
        <!-- Left Column - Profile Info -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php if($student['profile_image'] && $student['profile_image'] != 'default_student.png'): ?>
                        <img src="../../assets/images/students/<?= htmlspecialchars($student['profile_image']) ?>" alt="Profile">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <?= strtoupper(substr($student['first_name'] ?? 'S', 0, 1) . substr($student['last_name'] ?? 'T', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="profile-title">
                    <h2><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h2>
                    <p class="student-id">Student ID: <?= htmlspecialchars($student['sid_code']) ?></p>
                    <span class="status-badge <?= strtolower($student['status'] ?? 'pending') ?>">
                        <?= $student['status'] ?? 'Pending' ?>
                    </span>
                </div>
            </div>

            <div class="profile-details">
                <h3>Personal Information</h3>
                
                <div class="detail-row">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value"><?= htmlspecialchars($student['email'] ?? 'N/A') ?></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value"><?= htmlspecialchars($student['phone'] ?? 'N/A') ?></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Date of Birth</div>
                    <div class="detail-value"><?= htmlspecialchars($student['dob'] ?? 'N/A') ?></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Address</div>
                    <div class="detail-value"><?= htmlspecialchars($student['address'] ?? 'N/A') ?></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Member Since</div>
                    <div class="detail-value"><?= date('F j, Y', strtotime($student['created_at'] ?? 'now')) ?></div>
                </div>
            </div>

            <a href="index.php?view=edit_profile" class="btn-edit-profile">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>

        <!-- Right Column - Payment History -->
        <div class="payments-card">
            <h3>Payment History</h3>
            
            <?php if(!empty($payments)): ?>
                <div class="payments-list">
                    <?php foreach($payments as $payment): ?>
                        <div class="payment-item">
                            <div class="payment-info">
                                <div class="payment-course"><?= htmlspecialchars($payment['subject_name']) ?></div>
                                <div class="payment-date"><?= date('M j, Y', strtotime($payment['payment_date'])) ?></div>
                            </div>
                            <div class="payment-amount">$<?= number_format($payment['amount'], 2) ?></div>
                            <div class="payment-status">
                                <span class="status-badge <?= strtolower($payment['status']) ?>">
                                    <?= ucfirst($payment['status']) ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-payments">
                    <i class="fas fa-credit-card fa-3x"></i>
                    <p>No payment history found</p>
                    <a href="index.php?view=it_classes" class="btn-browse-courses">Browse Courses</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.profile-container {
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

.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 24px;
}

/* Profile Card */
.profile-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.profile-header {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid #f1f5f9;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    background: #eef2ff;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #6366f1;
    color: white;
    font-size: 24px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-title h2 {
    font-size: 20px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.student-id {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 8px;
}

.profile-details h3 {
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 16px;
}

.detail-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #f8fafc;
}

.detail-label {
    width: 120px;
    font-size: 13px;
    color: #64748b;
}

.detail-value {
    flex: 1;
    font-size: 14px;
    color: #1e293b;
    font-weight: 500;
}

.btn-edit-profile {
    display: block;
    text-align: center;
    padding: 12px;
    background: #6366f1;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 500;
    margin-top: 24px;
    transition: all 0.2s;
}

.btn-edit-profile:hover {
    background: #4f46e5;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(99,102,241,0.4);
}

/* Payments Card */
.payments-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.payments-card h3 {
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

.payments-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.2s;
}

.payment-item:hover {
    background: #f1f5f9;
}

.payment-info {
    flex: 2;
}

.payment-course {
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.payment-date {
    font-size: 12px;
    color: #64748b;
}

.payment-amount {
    flex: 1;
    font-weight: 600;
    color: #0f172a;
    text-align: center;
}

.payment-status {
    flex: 1;
    text-align: right;
}

.no-payments {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.no-payments i {
    color: #cbd5e1;
    margin-bottom: 16px;
}

.no-payments p {
    margin-bottom: 20px;
}

.btn-browse-courses {
    display: inline-block;
    padding: 10px 24px;
    background: #6366f1;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-browse-courses:hover {
    background: #4f46e5;
    transform: translateY(-2px);
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending {
    background: #fff7ed;
    color: #ea580c;
}

.status-badge.active {
    background: #dcfce7;
    color: #16a34a;
}

.status-badge.completed {
    background: #f1f5f9;
    color: #475569;
}

.status-badge.confirmed {
    background: #dcfce7;
    color: #16a34a;
}

/* Responsive */
@media (max-width: 1024px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .payment-item {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .payment-status {
        text-align: center;
    }
}
</style>