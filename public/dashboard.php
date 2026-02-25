<?php
require_once __DIR__ . "../../classes/Student.php";
require_once __DIR__ . "../../classes/Payment.php";
require_once __DIR__ . "../../classes/Schedule.php";

$studentModel = new Student();
$paymentModel = new Payment();
$scheduleModel = new Schedule();

$totalStudents = $studentModel->getTotalStudents();
$activeClasses = $scheduleModel->getActiveClassesCount();
$payStats      = $paymentModel->getStats();
$totalRevenue  = $payStats['total_revenue'];
$pendingCount  = $payStats['pending_count'];

$revenueBySubject = $paymentModel->getRevenueBySubject();
$recentEnrollments = $studentModel->getRecentEnrollments(4);
$upcomingClasses   = $scheduleModel->getUpcomingClasses(3);
?>

<div class="dashboard-wrapper">
    <header class="dashboard-header">
        <div>
            <h1>Dashboard Overview</h1>
            <p>Welcome back, Admin</p>
        </div>
    </header>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-details">
                <p class="label">Total Students</p>
                <h3><?= number_format($totalStudents) ?></h3>
                <span class="trend-up">+12% from last month</span>
            </div>
            <div class="icon-container bg-blue"><i class="fas fa-user-graduate"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-details">
                <p class="label">Active Classes</p>
                <h3><?= $activeClasses ?></h3>
                <span class="trend-up">+5% from last week</span>
            </div>
            <div class="icon-container bg-green"><i class="fas fa-calendar-alt"></i></div>
        </div>
        
        <div class="stat-card">
            <div class="stat-details">
                <p class="label">Total Revenue</p>
                <h3>$<?= number_format($totalRevenue) ?></h3>
                <span class="trend-up">+8% from last month</span>
            </div>
            <div class="icon-container bg-yellow"><i class="fas fa-dollar-sign"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-details">
                <p class="label">Pending Payments</p>
                <h3><?= $pendingCount ?></h3>
                <span class="trend-danger">Needs attention</span>
            </div>
            <div class="icon-container bg-red"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <div class="dashboard-main-grid">
        <div class="grid-col">
            <div class="content-box">
                <h3>Enrollment Trends</h3>
                <div class="chart-container"><canvas id="trendsChart"></canvas></div>
            </div>

            <div class="content-box">
                <h3>Recent Enrollments</h3>
                <table class="figma-table">
                    <thead>
                        <tr>
                            <th>STUDENT</th>
                            <th>SUBJECT</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentEnrollments as $row): ?>
                        <tr>
                            <td class="student-cell">
                                <div class="student-name-container">
                                    <span class="full-name"><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></span>
                                </div>
                            </td>
                            <td class="subject-name"><?= htmlspecialchars($row['subject_title']) ?></td>
                            <td>
                                <?php 
                                    $statusClass = strtolower($row['payment_status']);
                                    // Map 'Confirmed' from DB to 'Paid' for Figma UI
                                    $displayStatus = ($statusClass == 'confirmed') ? 'Paid' : $row['payment_status'];
                                ?>
                                <span class="status-pill <?= $statusClass ?>">
                                    <?= $displayStatus ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid-col">
            <div class="content-box">
                <h3>Revenue by Subject</h3>
                <div class="chart-container"><canvas id="revenueChart"></canvas></div>
            </div>

            <div class="content-box">
                <h3>Upcoming Classes</h3>
                <div class="upcoming-list">
                    <?php foreach($upcomingClasses as $class): ?>
                    <div class="upcoming-item">
                        <div class="item-icon bg-blue-light"><i class="fas fa-laptop-code"></i></div>
                        <div class="item-info">
                            <strong><?= htmlspecialchars($class['title']) ?></strong>
                            <p>Trainer: <?= htmlspecialchars($class['teacher_name']) ?></p>
                            <small><i class="far fa-clock"></i> <?= $class['day_of_week'] ?> • <?= date('h:i A', strtotime($class['start_time'])) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Revenue Doughnut Chart
    const revData = <?= json_encode($revenueBySubject) ?>;
    new Chart(document.getElementById('revenueChart'), {
        type: 'doughnut',
        data: {
            labels: revData.map(d => d.subject),
            datasets: [{
                data: revData.map(d => d.revenue),
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#a855f7'],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } }
        }
    });

    // 2. Enrollment Trends Line Chart
    new Chart(document.getElementById('trendsChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Enrollments',
                data: [100, 120, 150, 130, 170, 210],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, grid: { display: false } } }
        }
    });
});
</script>
