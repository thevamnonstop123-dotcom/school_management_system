<?php

$current_view = $_GET['view'] ?? 'dashboard';

function is_active($view, $current_view) {
    if ($view === 'branches' && in_array($current_view, ['branches', 'create_branch', 'edit_branch'])) {
        return 'active';
    }
    return ($view === $current_view) ? 'active' : '';
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

            <li class="<?= is_active('branches', $current_view); ?>">
                <a href="index.php?view=branches">
                    <i class="fas fa-code-branch"></i> <span>Branches</span>
                </a>
            </li>

            <li class="<?= is_active('reports', $current_view); ?>">
                <a href="index.php?view=reports">
                    <i class="fas fa-chart-bar"></i> <span>Reports</span>
                </a>
            </li>

            <li class="<?= is_active('settings', $current_view); ?>">
                <a href="index.php?view=settings">
                    <i class="fas fa-cog"></i> <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="user-profile">
        <div class="user-details">
            <img src="../../assets/images/mylogo.png" alt="Admin">
            <div class="user-info">
                <span class="user-name">Admin User</span>
                <span class="user-role">Administrator</span>
            </div>
        </div>
        <a href="auth/logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</aside>