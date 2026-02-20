<?php
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Subject.php';


$studentObj = new Student();

$student_id = $_SESSION['student_id'] ?? 0;
$enrolled_classes = $studentObj->getMyEnrolledClasses($student_id);

$currently_taking = [];
$completed_classes = [];

foreach($enrolled_classes as $class) {
    if($class['status'] == 'active') {
        $currently_taking[] = $class;
    } else if($class['status'] == 'completed') {
        $completed_classes[] = $class;
    }
}

$invited_classes = []; // Will come from invitations table later
?>

<div class="my-class-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-left">
            <h1>My Classes</h1>
            <p>Manage your current, completed, and invited classes</p>
        </div>
        <div class="header-right">
            <div class="enrollment-badge">
                <span class="badge-count">4</span>
                <span class="badge-label">Active enrollment</span>
            </div>
        </div>
    </div>

    <!-- Invited Classes Section (if any) -->
    <?php if(!empty($invited_classes)): ?>
    <div class="section-card invited-section">
        <div class="section-header">
            <h2>Classes Invited</h2>
            <span class="section-count"><?= count($invited_classes) ?> invitations</span>
        </div>

        <div class="invited-grid">
            <?php foreach($invited_classes as $class): ?>
            <div class="invited-card">
                <div class="card-left">
                    <div class="class-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="class-info">
                        <h3><?= htmlspecialchars($class['title']) ?></h3>
                        <p class="instructor">
                            <i class="fas fa-chalkboard-teacher"></i> 
                            Instructor: <?= htmlspecialchars($class['instructor']) ?>
                        </p>
                        <div class="class-meta">
                            <span><i class="far fa-calendar"></i> Starts <?= $class['start_date'] ?></span>
                            <span><i class="fas fa-users"></i> <?= $class['students'] ?> students</span>
                        </div>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn-accept">Accept</button>
                    <button class="btn-decline">Decline</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Currently Taking Section -->
    <div class="section-card">
        <div class="section-header">
            <h2>Currently Taking</h2>
            <span class="section-count"><?= count($currently_taking) ?> active courses</span>
        </div>

        <div class="currently-taking-grid">
            <?php foreach($currently_taking as $class): ?>
            <div class="course-card">
                <div class="course-header">
                    <div class="course-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div class="course-title">
                        <h3><?= htmlspecialchars($class['title']) ?></h3>
                        <p class="instructor">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($class['instructor']) ?>
                        </p>
                    </div>
                </div>

                <div class="course-progress">
                    <div class="progress-header">
                        <span class="progress-label">Progress</span>
                        <span class="progress-percentage"><?= $class['progress'] ?>%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?= $class['progress'] ?>%"></div>
                    </div>
                    <div class="progress-details">
                        <span><i class="far fa-clock"></i> <?= $class['weeks_left'] ?> weeks left</span>
                        <span><i class="fas fa-book-open"></i> <?= $class['lessons_completed'] ?>/<?= $class['lessons_total'] ?> lessons</span>
                    </div>
                </div>

                <button class="btn-continue">Continue Learning</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Classes Already Taken Section -->
    <?php if(!empty($completed_classes)): ?>
    <div class="section-card">
        <div class="section-header">
            <h2>Classes Already Taken</h2>
        </div>

        <div class="completed-table-container">
            <table class="completed-table">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Instructor</th>
                        <th>Completed Date</th>
                        <th>Grade</th>
                        <th>Certificate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($completed_classes as $class): ?>
                    <tr>
                        <td class="class-name"><?= htmlspecialchars($class['title']) ?></td>
                        <td><?= htmlspecialchars($class['instructor']) ?></td>
                        <td><?= $class['completed_date'] ?></td>
                        <td><span class="grade-badge"><?= $class['grade'] ?></span></td>
                        <td>
                            <?php if($class['certificate'] == 'Available'): ?>
                                <span class="cert-available">
                                    <i class="fas fa-check-circle"></i> Available
                                </span>
                            <?php else: ?>
                                <span class="cert-unavailable">Unavailable</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" class="btn-view">View Details</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.my-class-container {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header-left h1 {
    font-size: 24px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.header-left p {
    color: #64748b;
    font-size: 14px;
}

.enrollment-badge {
    background: white;
    padding: 12px 24px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.badge-count {
    font-size: 24px;
    font-weight: 700;
    color: #6366f1;
}

.badge-label {
    font-size: 12px;
    color: #64748b;
}

/* Section Cards */
.section-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
}

.section-count {
    font-size: 13px;
    color: #6366f1;
    background: #eef2ff;
    padding: 4px 12px;
    border-radius: 20px;
}

/* Invited Classes */
.invited-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.invited-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.2s;
}

.invited-card:hover {
    background: #f1f5f9;
}

.card-left {
    display: flex;
    gap: 16px;
    align-items: center;
}

.class-icon {
    width: 48px;
    height: 48px;
    background: #eef2ff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6366f1;
    font-size: 20px;
}

.class-info h3 {
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 6px;
}

.class-info .instructor {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 6px;
}

.class-info .instructor i {
    margin-right: 6px;
    font-size: 12px;
    color: #6366f1;
}

.class-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #64748b;
}

.class-meta i {
    margin-right: 4px;
    color: #6366f1;
}

.card-actions {
    display: flex;
    gap: 12px;
}

.btn-accept {
    padding: 8px 24px;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-accept:hover {
    background: #4f46e5;
    transform: translateY(-2px);
}

.btn-decline {
    padding: 8px 24px;
    background: white;
    color: #64748b;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-decline:hover {
    background: #f1f5f9;
}

/* Currently Taking Grid */
.currently-taking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.course-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s;
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
}

.course-header {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
}

.course-icon {
    width: 40px;
    height: 40px;
    background: #eef2ff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6366f1;
    font-size: 18px;
}

.course-title h3 {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.course-title .instructor {
    font-size: 12px;
    color: #64748b;
}

.course-title .instructor i {
    margin-right: 4px;
    font-size: 10px;
    color: #6366f1;
}

/* Progress Bar */
.course-progress {
    margin-bottom: 16px;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 6px;
}

.progress-percentage {
    font-weight: 600;
    color: #6366f1;
}

.progress-bar-container {
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    margin-bottom: 8px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: #6366f1;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.progress-details {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #94a3b8;
}

.progress-details i {
    margin-right: 4px;
    font-size: 10px;
    color: #6366f1;
}

.btn-continue {
    width: 100%;
    padding: 10px;
    background: white;
    color: #6366f1;
    border: 1px solid #6366f1;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-continue:hover {
    background: #6366f1;
    color: white;
}

/* Completed Classes Table */
.completed-table-container {
    overflow-x: auto;
}

.completed-table {
    width: 100%;
    border-collapse: collapse;
}

.completed-table th {
    text-align: left;
    padding: 12px 8px;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    border-bottom: 1px solid #e2e8f0;
}

.completed-table td {
    padding: 16px 8px;
    font-size: 14px;
    color: #1e293b;
    border-bottom: 1px solid #f1f5f9;
}

.completed-table .class-name {
    font-weight: 500;
}

.grade-badge {
    background: #dcfce7;
    color: #16a34a;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.cert-available {
    color: #16a34a;
    font-size: 13px;
}

.cert-available i {
    margin-right: 4px;
}

.cert-unavailable {
    color: #94a3b8;
    font-size: 13px;
}

.btn-view {
    color: #6366f1;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

.btn-view:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 1024px) {
    .currently-taking-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .invited-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .card-actions {
        width: 100%;
    }
    
    .card-actions button {
        flex: 1;
    }
    
    .currently-taking-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
}
</style>