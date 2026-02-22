<?php
$action = $_GET['action'] ?? 'list';
$search = $_GET['search'] ?? '';

if ($action == 'list') {
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $allStudents = $studentObj->searchStudents($search, $offset, $limit);
        $totalStudents = $studentObj->countSearchStudents($search);
    } else {
        $allStudents = $studentObj->getStudentsWithPagination($offset, $limit);
        $totalStudents = $studentObj->getTotalStudents();
    }

    $totalPages = ceil($totalStudents / $limit);
}
?>

<div class="container">
    <main class="main-content">
        <!-- Header -->
        <header class="page-header">
            <div class="header-left">
                <h1>Students</h1>
                <p>View all students registered in the system</p>
            </div>
            <div class="header-actions">
                <button class="btn-export" onclick="exportStudents()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </header>

        <!-- Search Bar -->
        <div class="search-filter-bar">
            <form action="index.php" method="GET" class="search-box">
                <input type="hidden" name="view" value="students">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search students by name, email or ID...">
            </form>
        </div>

        <!-- Students Table - CLEAN VERSION -->
        <div class="table-card">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date of Birth</th>
                        <th>Enrolled Courses</th>
                        <th>Status</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($allStudents)): ?>
                        <?php foreach ($allStudents as $student): 
                            $enrolledCount = $studentObj->getEnrollmentCount($student['student_id']);
                        ?>
                        <tr>
                            <td class="id-cell"><?= htmlspecialchars($student['sid_code'] ?? 'STU' . str_pad($student['student_id'], 4, '0', STR_PAD_LEFT)) ?></td>
                            <td class="name-cell"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                            <td class="email-cell">
                                <a href="mailto:<?= htmlspecialchars($student['email']) ?>"><?= htmlspecialchars($student['email']) ?></a>
                            </td>
                            <td class="phone-cell"><?= htmlspecialchars($student['phone'] ?? 'N/A') ?></td>
                            <td class="dob-cell"><?= date('M j, Y', strtotime($student['dob'])) ?></td>
                            <td class="enrolled-cell">
                                <span class="course-badge <?= $enrolledCount > 0 ? 'has-courses' : 'no-courses' ?>">
                                    <?= $enrolledCount ?> <?= $enrolledCount == 1 ? 'course' : 'courses' ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?= strtolower($student['status'] ?? 'pending') ?>">
                                    <?= $student['status'] ?? 'Pending' ?>
                                </span>
                            </td>
                            <td class="joined-cell"><?= date('M j, Y', strtotime($student['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-data">
                                <i class="fas fa-users fa-3x"></i>
                                <p>No students found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($allStudents) && $totalPages > 1): ?>
            <div class="pagination-footer">
                <div class="pagination-info">
                    Showing <?= ($offset + 1) ?> to <?= min($totalStudents, $offset + $limit) ?> of <?= $totalStudents ?> students
                </div>
                <div class="pagination-controls">
                    <a href="index.php?view=students&page=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>" 
                       class="page-nav-btn <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="index.php?view=students&page=<?= $i ?>&search=<?= urlencode($search) ?>" 
                           class="page-num <?= ($page == $i) ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <a href="index.php?view=students&page=<?= min($totalPages, $page + 1) ?>&search=<?= urlencode($search) ?>" 
                       class="page-nav-btn <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<script>
function exportStudents() {
    window.location.href = 'students/export_students.php?search=<?= urlencode($search ?? '') ?>';
}
</script>