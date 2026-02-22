<?php
$action = $_GET['action'] ?? 'list';
$search = $_GET['search'] ?? '';

if ($action == 'list') {
    $limit = 9;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $allCourses = $subjectObj->searchCoursesForTable($search, $offset, $limit);
        $totalCourses = $subjectObj->countSearchCourses($search);
    } else {
        $allCourses = $subjectObj->getCoursesForTable($offset, $limit);
        $totalCourses = $subjectObj->getTotalCourses();
    }

    $totalPages = ceil($totalCourses / $limit);
}
?>

<div class="container">
    <main class="main-content">
        <!-- Header -->
        <header class="page-header">
            <div class="header-left">
                <h1>Courses</h1>
                <p>View all courses available in the system</p>
            </div>
            <div class="header-actions">
                <button class="btn-export" onclick="exportCourses()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </header>

        <!-- Search Bar -->
        <div class="search-filter-bar">
            <form action="index.php" method="GET" class="search-box">
                <input type="hidden" name="view" value="courses">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search courses by title...">
            </form>
        </div>

        <!-- Courses Grid -->
        <div class="courses-grid">
            <?php if (!empty($allCourses)): ?>
                <?php foreach ($allCourses as $course): 
                    $startTime = !empty($course['start_time']) ? date('h:i A', strtotime($course['start_time'])) : 'TBA';
                    $endTime = !empty($course['end_time']) ? date('h:i A', strtotime($course['end_time'])) : 'TBA';
                    $dayOfWeek = $course['day_of_week'] ?? '';
                    $studentCount = $course['student_count'] ?? 0;
                ?>
                <div class="course-card">
                    <div class="course-image">
                        <?php if (!empty($course['image_path']) && $course['image_path'] != 'default_subject.png'): ?>
                            <img src="../../assets/images/subjects/<?= $course['image_path'] ?>" 
                                 alt="<?= htmlspecialchars($course['title']) ?>">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class="fas fa-book"></i>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($dayOfWeek)): ?>
                            <span class="course-day-badge"><?= substr($dayOfWeek, 0, 3) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="course-content">
                        <h3><?= htmlspecialchars($course['title']) ?></h3>
                        <p class="course-description">
                            <?= htmlspecialchars(substr($course['description'] ?? 'No description', 0, 80)) ?>
                            <?= strlen($course['description'] ?? '') > 80 ? '...' : '' ?>
                        </p>
                        
                        <div class="course-meta">
                            <span class="course-fee">
                                <i class="fas fa-tag"></i> $<?= number_format($course['fee'], 2) ?>
                            </span>
                            <span class="course-time">
                                <i class="fas fa-clock"></i> <?= $startTime ?> - <?= $endTime ?>
                            </span>
                        </div>
                        
                        <div class="course-footer">
                            <span class="student-count">
                                <i class="fas fa-users"></i> <?= $studentCount ?> 
                                <?= $studentCount == 1 ? 'student' : 'students' ?>
                            </span>
                            <a href="index.php?view=course_details&id=<?= $course['subject_id'] ?>" class="btn-view">
                                View Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-book-open fa-4x"></i>
                    <p>No courses found</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($allCourses) && $totalPages > 1): ?>
            <div class="pagination-footer">
                <div class="pagination-info">
                    Showing <?= ($offset + 1) ?> to <?= min($totalCourses, $offset + $limit) ?> of <?= $totalCourses ?> courses
                </div>
                <div class="pagination-controls">
                    <a href="index.php?view=courses&page=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>" 
                       class="page-nav-btn <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="index.php?view=courses&page=<?= $i ?>&search=<?= urlencode($search) ?>" 
                           class="page-num <?= ($page == $i) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <a href="index.php?view=courses&page=<?= min($totalPages, $page + 1) ?>&search=<?= urlencode($search) ?>" 
                       class="page-nav-btn <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<script>
function exportCourses() {
    window.location.href = 'courses/export_courses.php?search=<?= urlencode($search ?? '') ?>';
}
</script>