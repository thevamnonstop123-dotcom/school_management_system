<div class="schedule-form-wrapper">
    <div class="form-header-area">
        <h1>Edit Class Schedule</h1>
        <p>Modify the details for Schedule #<?= str_pad($schedule_data['schedule_id'], 3, '0', STR_PAD_LEFT) ?></p>
    </div>

    <div class="schedule-card">
        <div class="card-top-bar">
            <h3>Update Schedule Information</h3>
            <a href="index.php?view=schedule" class="close-btn">&times;</a>
        </div>

        <form action="schedule/process_schedule.php" method="POST" class="main-form">
            <input type="hidden" name="schedule_id" value="<?= $schedule_data['schedule_id'] ?>">

            <div class="input-grid">
                <div class="field-box">
                    <label>Room <span>*</span></label>
                    <select name="room_id" required>
                        <?php foreach($rooms as $r): ?>
                            <option value="<?= $r['room_id'] ?>" <?= ($r['room_id'] == $schedule_data['room_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['room_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-box">
                    <label>Trainer <span>*</span></label>
                    <select name="trainer_id" required>
                        <?php foreach($trainers as $t): ?>
                            <option value="<?= $t['trainer_id'] ?>" <?= ($t['trainer_id'] == $schedule_data['trainer_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-box">
                    <label>Subject <span>*</span></label>
                    <select name="subject_id" required>
                        <?php foreach($subjects as $s): ?>
                            <option value="<?= $s['subject_id'] ?>" <?= ($s['subject_id'] == $schedule_data['subject_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-box">
                    <label>Branch <span>*</span></label>
                    <select name="branch_id" required>
                        <?php foreach($branches as $b): ?>
                            <option value="<?= $b['branch_id'] ?>" <?= ($b['branch_id'] == $schedule_data['branch_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['branch_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-box full-span">
                    <label>Day of Week <span>*</span></label>
                    <select name="day_of_week" required>
                        <?php 
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach($days as $day): ?>
                            <option value="<?= $day ?>" <?= ($day == $schedule_data['day_of_week']) ? 'selected' : '' ?>><?= $day ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field-box">
                    <label>Start Time <span>*</span></label>
                    <input type="time" name="start_time" value="<?= $schedule_data['start_time'] ?>" required>
                </div>
                <div class="field-box">
                    <label>End Time <span>*</span></label>
                    <input type="time" name="end_time" value="<?= $schedule_data['end_time'] ?>" required>
                </div>
            </div>

            <div class="status-selection">
                <label>Status <span>*</span></label>
                <div class="pill-group">
                    <?php $statuses = ['Active', 'Coming Soon', 'Completed']; ?>
                    <?php foreach($statuses as $status): ?>
                        <label class="pill">
                            <input type="radio" name="status" value="<?= $status ?>" <?= ($status == $schedule_data['status']) ? 'checked' : '' ?>>
                            <span class="pill-btn <?= strtolower(str_replace(' ', '', $status)) ?>-pill">● <?= $status ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-bottom">
                <button type="button" class="btn-cancel-flat" onclick="window.location.href='index.php?view=schedule'">Cancel</button>
                <button type="submit" name="update_schedule" class="btn-create-purple">
                    Update Schedule
                </button>
            </div>
        </form>
    </div>
</div>