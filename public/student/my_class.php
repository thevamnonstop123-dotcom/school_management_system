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

$invited_classes = [];
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

    <div class="section-card">
        <div class="section-header">
            <h2>Currently Taking</h2>
            <span class="section-count"><?= count($currently_taking) ?> active courses</span>
        </div>

        <div class="currently-taking-grid">
            <?php if(!empty($currently_taking)): ?>
                <?php foreach($currently_taking as $class): ?>
                <div class="course-card">
                    <div class="course-header">
                        <div class="course-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div class="course-title">
                            <h3><?= htmlspecialchars($class['title'] ?? 'Untitled') ?></h3>
                            <p class="instructor">
                                <i class="fas fa-user"></i> 
                                <?= htmlspecialchars($class['instructor_name'] ?? 'Instructor TBA') ?>
                            </p>
                        </div>
                    </div>

                    <div class="course-progress">
                        <div class="progress-header">
                            <span class="progress-label">Progress</span>
                            <span class="progress-percentage"><?= $class['progress'] ?? 0 ?>%</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?= $class['progress'] ?? 0 ?>%"></div>
                        </div>
                        <div class="progress-details">
                            <span><i class="far fa-clock"></i> 
                                <?= isset($class['weeks_left']) ? $class['weeks_left'] . ' weeks left' : 'In progress' ?>
                            </span>
                            <span><i class="fas fa-book-open"></i> 
                                <?= ($class['lessons_completed'] ?? 0) . '/' . ($class['lessons_total'] ?? '10') . ' lessons' ?>
                            </span>
                        </div>
                    </div>

                    <button class="btn-continue">Continue Learning</button>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-classes">
                    <p>You are not enrolled in any courses yet.</p>
                    <a href="index.php?view=it_classes" class="btn-browse">Browse Courses</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

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
