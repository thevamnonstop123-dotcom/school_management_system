<div class="schedule-container">
    <header class="list-header">
        <div class="header-title">
            <h1>Class Schedules</h1>
            <p>Manage and organize training schedules</p>
        </div>
        <a href="index.php?view=create_schedule" class="btn-create-schedule">
            <i class="fas fa-plus"></i> Create Schedule
        </a>
    </header>

    <div class="card filter-card">
        <h3>Filters</h3>
        <div class="filter-grid">
            
            <div class="filter-group">
                <label>Search</label>
                <form action="index.php" method="GET">
                    <input type="hidden" name="view" value="schedule">
                    
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search schedules..." 
                            value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                </form>
            </div>

            <div class="filter-group">
                <label>Day</label>
                <select><option>All Days</option></select>
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select><option>All Status</option></select>
            </div>
            <div class="filter-group">
                <label>Trainer</label>
                <select><option>All Trainers</option></select>
            </div>
            <div class="filter-group">
                <label>Room</label>
                <select><option>All Rooms</option></select>
            </div>
        </div>
    </div>

    <div class="schedule-container">
    <div class="card table-card">
        <div class="table-header-flex">
            <h3>Class Schedules</h3>
            <div class="header-right">
                <span class="total-badge"><?= count($all_schedules) ?> schedules</span>
                <div class="view-icons">
                    <i class="fas fa-th-large"></i>
                    <i class="fas fa-list active"></i>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>SCHEDULE ID</th>
                    <th>SUBJECT</th>
                    <th>TRAINER</th>
                    <th>ROOM</th>
                    <th>DAY</th>
                    <th>TIME</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
           <tbody>
            <?php foreach($all_schedules as $row): ?>
            <tr>
                <td class="id-cell">#<?= str_pad($row['schedule_id'], 3, '0', STR_PAD_LEFT) ?></td>
                <td class="bold-text"><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['trainer_name']) ?></td>
                <td><?= htmlspecialchars($row['room_name']) ?></td>
                <td><?= $row['day_of_week'] ?></td>
                <td class="time-cell">
                    <?= date("H:i", strtotime($row['start_time'])) ?> - <?= date("H:i", strtotime($row['end_time'])) ?>
                </td>
                <td>
                    <span class="badge status-<?= strtolower(str_replace(' ', '', $row['status'])) ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td class="actions-cell">
                    <a href="index.php?view=edit_schedule&id=<?= $row['schedule_id'] ?>" class="btn-edit">
                        <i class="far fa-edit"></i>
                    </a>
                    
                    <a href="schedule/process_schedule.php?action=delete&id=<?= $row['schedule_id'] ?>" 
                    class="btn-delete" 
                    onclick="return confirm('Are you sure you want to delete this schedule?');">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>

        <div class="table-footer">
            <?php 
                $start_num = $offset + 1;
                $end_num = min($offset + $limit, $totalSchedules);
            ?>
            <p>Showing <?= $start_num ?> to <?= $end_num ?> of <?= $totalSchedules ?> results</p>
            
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="index.php?view=schedule&page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" class="prev">Previous</a>
                <?php else: ?>
                    <button class="prev" disabled>Previous</button>
                <?php endif; ?>

                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="index.php?view=schedule&page=<?= $i ?>&search=<?= urlencode($search) ?>" 
                    class="page-num <?= ($i == $page) ? 'active' : '' ?>">
                    <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if($page < $totalPages): ?>
                    <a href="index.php?view=schedule&page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" class="next">Next</a>
                <?php else: ?>
                    <button class="next" disabled>Next</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>