<?php
require_once __DIR__ . '/../../classes/Student.php';

$studentObj = new Student();

$student_id = $_SESSION['student_id'] ?? 0;
$schedule_data = $studentObj->formatScheduleForDisplay($student_id);

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
foreach($days as $day) {
    if(!isset($schedule_data[$day])) {
        $schedule_data[$day] = [];
    }
}

$current_week = date('F j, Y');
?>

<div class="schedule-figma-container">
    <!-- Header -->
    <div class="schedule-header">
        <div class="header-title">
            <h1>Class Schedule</h1>
            <p>View your weekly class schedule and upcoming sessions</p>
        </div>
        <button class="export-btn" onclick="exportSchedule()">
            <i class="fas fa-download"></i> Export Schedule
        </button>
    </div>

    <!-- Week Display -->
    <div class="week-display">
        <i class="fas fa-calendar-alt"></i> Week of <?= $current_week ?>
    </div>

    <!-- Schedule Grid -->
    <div class="schedule-figma-grid">
        <?php foreach($days as $day): ?>
            <div class="day-column-figma">
                <div class="day-header-figma"><?= $day ?></div>
                
                <?php if(!empty($schedule_data[$day])): ?>
                    <?php foreach($schedule_data[$day] as $class): ?>
                        <!-- Class Card with Real Data -->
                        <div class="class-card-figma">
                            <div class="class-time-figma"><?= $class['time'] ?></div>
                            <div class="class-title-figma"><?= htmlspecialchars($class['course']) ?></div>
                            <div class="class-instructor-figma">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($class['instructor']) ?>
                            </div>
                            <div class="class-room-figma">
                                <i class="fas fa-door-open"></i> <?= htmlspecialchars($class['room']) ?>
                            </div>
                            <div class="class-status-figma <?= $class['status'] ?>">
                                <?php if($class['status'] == 'active'): ?>
                                    <span class="status-dot active"></span> Active
                                <?php elseif($class['status'] == 'coming_soon'): ?>
                                    <span class="status-dot coming-soon"></span> Coming Soon
                                <?php elseif($class['status'] == 'completed'): ?>
                                    <span class="status-dot completed"></span> Completed
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty day - show placeholder -->
                    <div class="empty-day-figma">
                        <p>No classes</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Legend -->
    <div class="legend-figma">
        <div class="legend-item-figma">
            <span class="status-dot active"></span>
            <span>Active Classes</span>
        </div>
        <div class="legend-item-figma">
            <span class="status-dot coming-soon"></span>
            <span>Coming Soon</span>
        </div>
        <div class="legend-item-figma">
            <span class="status-dot completed"></span>
            <span>Completed</span>
        </div>
    </div>
</div>

<script>
function exportSchedule() {
    alert('Exporting schedule...');
}
</script>
