<div class="schedule-form-wrapper">
    <div class="form-header-area">
        <h1>Class Schedules</h1>
        <p>Create and manage class schedules</p>
    </div>

    <div class="schedule-card">
        <div class="card-top-bar">
            <h3>Create New Class Schedule</h3>
            <a href="index.php?view=schedule" class="close-btn">&times;</a>
        </div>

        <form action="schedule/process_schedule.php" method="POST" class="main-form">
            <div class="input-grid">
                <!-- Room -->
                <div class="field-box">
                    <label>Room <span>*</span></label>
                    <select name="room_id" required>
                        <option value="">Select Room</option>
                        <?php foreach($rooms as $r): ?>
                            <option value="<?= $r['room_id'] ?>"><?= htmlspecialchars($r['room_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Trainer -->
                <div class="field-box">
                    <label>Trainer <span>*</span></label>
                    <select name="trainer_id" required>
                        <option value="">Select Trainer</option>
                        <?php foreach($trainers as $t): ?>
                            <option value="<?= $t['trainer_id'] ?>"><?= htmlspecialchars($t['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Subject -->
                <div class="field-box">
                    <label>Subject <span>*</span></label>
                    <select name="subject_id" required>
                        <option value="">Select Subject</option>
                        <?php foreach($subjects as $s): ?>
                            <option value="<?= $s['subject_id'] ?>"><?= htmlspecialchars($s['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Branch -->
                <div class="field-box">
                    <label>Branch <span>*</span></label>
                    <select name="branch_id" required>
                        <option value="">Select Branch</option>
                        <?php foreach($branches as $b): ?>
                            <option value="<?= $b['branch_id'] ?>"><?= htmlspecialchars($b['branch_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Day of Week - Full Width -->
                <div class="field-box full-span">
                    <label>Day of Week <span>*</span></label>
                    <select name="day_of_week" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>

                <!-- Start Time -->
                <div class="field-box">
                    <label>Start Time <span>*</span></label>
                    <input type="time" name="start_time" required>
                </div>

                <!-- End Time -->
                <div class="field-box">
                    <label>End Time <span>*</span></label>
                    <input type="time" name="end_time" required>
                </div>
            </div>

            <!-- Status Section -->
            <div class="status-selection">
                <label>Status <span>*</span></label>
                <div class="pill-group">
                    <label class="pill">
                        <input type="radio" name="status" value="Active" checked>
                        <span class="pill-btn active-pill">Active</span>
                    </label>
                    <label class="pill">
                        <input type="radio" name="status" value="Coming Soon">
                        <span class="pill-btn coming-pill">Coming Soon</span>
                    </label>
                    <label class="pill">
                        <input type="radio" name="status" value="Completed">
                        <span class="pill-btn completed-pill">Completed</span>
                    </label>
                </div>
            </div>

            <!-- Form Buttons -->
            <div class="form-bottom">
                <button type="button" class="btn-cancel-flat" onclick="window.location.href='index.php?view=schedule'">Cancel</button>
                <button type="submit" name="create_schedule" class="btn-create-purple">
                    <i class="fas fa-plus"></i> Create Schedule
                </button>
            </div>
        </form>
    </div>
</div>