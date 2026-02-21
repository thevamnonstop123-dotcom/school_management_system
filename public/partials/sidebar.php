<?php
$current_view = $_GET['view'] ?? 'dashboard';
$user_role = $_SESSION['user_role'] ?? 'admin'; // Get user role from session

function is_active($menu_item, $current_view) {
    $menu_groups = [
        'dashboard' => ['dashboard'],
        'trainers'  => ['trainers', 'create_trainer', 'edit_trainer'],
        'students'  => ['students', 'create_student', 'edit_student'],
        'rooms'     => ['rooms', 'edit_room'],
        'schedule' => ['schedule', 'create_schedule', 'edit_schedule'],
        'subjects'  => ['subjects', 'edit_subject'],
        'branches'  => ['branches', 'create_branch', 'edit_branch'],
        'reports' => ['payment_confirm'],
        
        // Student menu groups
        'it_classes' => ['it_classes'],
        'my_class' => ['my_class'],
        'student_schedule' => ['student_schedule'],
        'profile' => ['profile']
    ];

    if (isset($menu_groups[$menu_item]) && in_array($current_view, $menu_groups[$menu_item])) {
        return 'active';
    }
    
    return '';
}
?>

<aside class="sidebar">
    <a href="javascript:void(0)" class="close-sidebar" id="closeSidebar">&times;</a>
    <div class="logo">
        <i class="fas fa-graduation-cap"></i>
        <div>
            <h3>Training School</h3>
            <p>Management System</p>
        </div>
    </div>
    
    <nav>
        <ul>
            <?php if($user_role == 'student'): ?>
                <li class="<?= is_active('it_classes', $current_view); ?>">
                    <a href="index.php?view=it_classes">
                        <i class="fas fa-laptop-code"></i> <span>IT Classes</span>
                    </a>
                </li>

                <li class="<?= is_active('student_schedule', $current_view); ?>">
                    <a href="index.php?view=student_schedule">
                        <i class="fas fa-calendar-alt"></i> <span>Schedule</span>
                    </a>
                </li>

                <li class="<?= is_active('profile', $current_view); ?>"> 
                    <a href="index.php?view=profile">
                        <i class="fas fa-user-graduate"></i> <span>Student</span>
                    </a>
                </li>

                <li class="<?= is_active('my_class', $current_view); ?>">
                    <a href="index.php?view=my_class">
                        <i class="fas fa-book-open"></i> <span>My Class</span>
                    </a>
                </li>
            <?php else: ?>

                <!-- ADMIN MENU - All items -->
                <li class="<?= is_active('dashboard', $current_view); ?>">
                    <a href="index.php?view=dashboard">
                        <i class="fas fa-th-large"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="<?= is_active('trainers', $current_view); ?>">
                    <a href="index.php?view=trainers">
                        <i class="fas fa-user-tie"></i> <span>Trainers</span>
                    </a>
                </li>

                <li class="<?= is_active('students', $current_view); ?>">
                    <a href="index.php?view=students">
                        <i class="fas fa-users"></i> <span>Students</span>
                    </a>
                </li>

                <li class="<?= is_active('courses', $current_view); ?>">
                    <a href="index.php?view=courses">
                        <i class="fas fa-book"></i> <span>Courses</span>
                    </a>
                </li>

                <li class="<?= is_active('rooms', $current_view); ?>">
                    <a href="index.php?view=rooms">
                        <i class="fas fa-door-open"></i> <span>Rooms</span>
                    </a>
                </li>

                <li class="<?= is_active('schedule', $current_view); ?>">
                    <a href="index.php?view=schedule">
                        <i class="fas fa-calendar-alt"></i> <span>Schedule</span>
                    </a>
                </li>

                <li class="<?= is_active('subjects', $current_view); ?>">
                    <a href="index.php?view=subjects">
                        <i class="fas fa-book-open"></i> <span>Subjects</span>
                    </a>
                </li>

                <li class="<?= is_active('branches', $current_view); ?>">
                    <a href="index.php?view=branches">
                        <i class="fas fa-code-branch"></i> <span>Branches</span>
                    </a>
                </li>

                <li class="<?= is_active('reports', $current_view); ?>">
                    <a href="index.php?view=payment_confirm">
                        <i class="fas fa-chart-bar"></i> <span>Reports</span> 
                    </a>
                </li>

                <li class="<?= is_active('settings', $current_view); ?>">
                    <a href="index.php?view=settings">
                        <i class="fas fa-cog"></i> <span>Settings</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="user-profile">
        <div class="user-details">
            <img src="../../assets/images/mylogo.png" alt="User">
            <div class="user-info">
                <span class="user-name">
                    <?= $_SESSION['student_name'] ?? $_SESSION['user_name'] ?? 'Admin User' ?>
                </span>
                <span class="user-role">
                    <?= $_SESSION['user_role'] ?? 'Administrator' ?>
                </span>
            </div>
        </div>
        <a href="auth/logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</aside>

             