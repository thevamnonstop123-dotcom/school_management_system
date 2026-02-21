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
                            <div class="payment-amount">$<?= number_format($payment['total_amount'], 2) ?></div>
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
