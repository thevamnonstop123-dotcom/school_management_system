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

<style>
/* Add this new style */
.week-display {
    background: white;
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 24px;
    display: inline-block;
    font-size: 14px;
    color: #1e293b;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.week-display i {
    color: #6366f1;
    margin-right: 8px;
}

.empty-day-figma {
    background: #f9fafb;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    color: #9ca3af;
    font-size: 13px;
    border: 1px dashed #e5e7eb;
}

.schedule-figma-container {
    padding: 30px;
    background: #f5f7fa;
    min-height: 100vh;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Header */
.schedule-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header-title h1 {
    font-size: 24px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 6px;
}

.header-title p {
    color: #6b7280;
    font-size: 14px;
}

.export-btn {
    padding: 10px 20px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #374151;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: all 0.2s;
}

.export-btn:hover {
    border-color: #6366f1;
    color: #6366f1;
}

/* Schedule Grid - EXACT Figma Style */
.schedule-figma-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 16px;
    margin-bottom: 30px;
}

.day-column-figma {
    background: transparent;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.day-header-figma {
    font-weight: 600;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
    padding-left: 4px;
}

/* Class Card - EXACT Figma Style */
.class-card-figma {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transition: all 0.2s;
    border: 1px solid #f3f4f6;
}

.class-card-figma:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.class-time-figma {
    font-size: 12px;
    font-weight: 500;
    color: #6366f1;
    margin-bottom: 8px;
    letter-spacing: 0.3px;
}

.class-title-figma {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.class-instructor-figma,
.class-room-figma {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.class-instructor-figma i,
.class-room-figma i {
    font-size: 11px;
    color: #9ca3af;
    width: 14px;
}

.class-status-figma {
    margin-top: 10px;
    font-size: 11px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    padding-top: 8px;
    border-top: 1px solid #f3f4f6;
}

.class-status-figma.active {
    color: #10b981;
}

.class-status-figma.coming_soon {
    color: #f59e0b;
}

/* Status Dots */
.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-dot.active {
    background: #10b981;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.status-dot.coming-soon {
    background: #f59e0b;
    box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
}

.status-dot.completed {
    background: #9ca3af;
    box-shadow: 0 0 0 2px rgba(156, 163, 175, 0.2);
}

/* Empty Slot */
.empty-slot-figma {
    height: 140px;
    background: transparent;
}

/* Legend */
.legend-figma {
    display: flex;
    gap: 24px;
    padding: 16px 0;
}

.legend-item-figma {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #4b5563;
}

/* Responsive */
@media (max-width: 1200px) {
    .schedule-figma-grid {
        grid-template-columns: repeat(7, 280px);
        overflow-x: auto;
        padding-bottom: 20px;
    }
    
    .schedule-figma-container {
        overflow-x: auto;
    }
}

@media (max-width: 768px) {
    .schedule-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .legend-figma {
        flex-wrap: wrap;
    }
}
</style>