<?php
$action = $_GET['action'] ?? 'list';
$search = $_GET['search'] ?? '';

if ($action === 'list') {
    $limit = 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    if (!empty($search)) {
        $allTrainers = $trainerObj->searchWithPagination($search, $offset, $limit);
        $totalTrainers = $trainerObj->countSearchResults($search);
    } else {
        $allTrainers = $trainerObj->getTrainersWithPagination($offset, $limit);
        $totalTrainers = $trainerObj->getTotalCount();
    }
    $totalPages = ceil($totalTrainers / $limit);
}

?>

<div class="container">
    <main class="main-content">
        <?php if ($action === 'add'): ?>
            <header>
                <div class="title-section">
                    <h1>Create New Trainer</h1>
                    <p>Add a new trainer to the system</p>
                </div>
            </header>

            <nav class="breadcrumb">
                <a href="index.php?view=dashboard">Dashboard</a>
                <i class="fas fa-chevron-right separator"></i>
                <a href="index.php?view=trainers">Trainers</a>
                <i class="fas fa-chevron-right separator"></i>
                <span class="current">Create Trainer</span>
            </nav>

            <section class="form-card">
                <div class="card-header">
                    <h3>Trainer Information</h3>
                    <p>Please fill in the trainer details below</p>
                </div>

                <form action="trainer/process_trainer.php" method="POST" enctype="multipart/form-data" class="figma-form">

                    <div class="profile-upload-section">
                        <div class="avatar-container">
                            <img src="../../assets/images/default-avatar.png" id="img-preview" alt="Preview">
                            <input type="file" name="avatar" id="avatar-input" accept="image/*" hidden onchange="previewImage(event)">
                            <button type="button" class="btn-upload-circle" onclick="document.getElementById('avatar-input').click()">
                                <i class="fas fa-camera"></i>
                                <span>UPLOAD</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-body">
                        <div class="form-group-full">
                            <label>Trainer Name <span class="required">*</span></label>
                            <input type="text" name="full_name" placeholder="Enter trainer's full name" required>
                            <small class="hint">Maximum 100 characters</small>
                        </div>

                        <div class="form-group-full">
                            <label>Email Address <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="michael@training.com" required>
                        </div>

                        <div class="form-group-full">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="text" name="phone_number" placeholder="09xxxxxxxxx" required>
                        </div>

                        <div class="form-group-full">
                            <label>Specialization</label>
                            <select name="specialization">
                                <option value="Web Development">Web Development</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                                <option value="Data Science">Data Science</option>
                                <option value="Mobile App Development">Mobile App Development</option>
                            </select>
                        </div>

                        <div class="form-group-full">
                            <label>Status</label>
                            <select name="status">
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-footer">
                        <a href="index.php?view=trainers" class="btn-figma-cancel">Cancel</a>
                        <button type="submit" name="add_trainer" class="btn-figma-save">Create Trainer</button>
                    </div>
                </form>
            </section>

         <?php else: ?>
            <header>
                <div class="title-section">
                    <h1>Trainers Management</h1>
                    <p>Manage and view all trainers in the system</p>
                </div>
                <div class="header-actions">
                    <button class="btn-export">
                        <i class="fas fa-download"></i>
                        Export
                    </button>
                    
                    <?php if($isAdmin): ?>
                        <a href="index.php?view=trainers&action=add" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Add Trainer
                        </a>
                    <?php endif; ?>
                </div>
            </header>

            <section class="table-container">
                <form action="index.php" method="GET" class="search-bar">
                    <input type="hidden" name="view" value="trainers">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search trainers by name or specialization...">
                </form>

                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="<?php echo $_SESSION['flash_type'] === 'success' ? 'create-message' : 'delete-message'; ?>">
                        <?php
                        echo $_SESSION['flash_message'];
                        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
                        ?>
                    </div>
                <?php endif; ?>

                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox"></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <?php if($isAdmin): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allTrainers)): ?>
                            <?php foreach ($allTrainers as $row): ?>
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td><?php echo str_pad($row['trainer_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                    <td class="user-cell">
                                        <?php
                                        $avatar_url = $row['avatar_url'];
                                        if (strpos($avatar_url, 'default') === false) {
                                            $imagePath = "../../assets/images/trainers/" . $avatar_url;
                                        } else {
                                            $imagePath = "../../assets/images/" . $avatar_url;
                                        }
                                        ?>
                                        <img src="<?= $imagePath ?>" alt="Avatar" class="avatar-img" loading="lazy">
                                        <div class="name-email">
                                            <strong><?= htmlspecialchars($row['full_name']); ?></strong>
                                            <small><?= htmlspecialchars($row['email']); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="spec-label">
                                            <i class="fas fa-code"></i> <?php echo htmlspecialchars($row['specialization']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($row['status']); ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <?php if($isAdmin) : ?>
                                            <a href="#" class="btn-view" title="View">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            
                                            <a href="trainer/edit_trainer.php?id=<?= $row['trainer_id']; ?>" class="btn-edit" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a href="trainer/delete_trainer.php?id=<?= $row['trainer_id']; ?>"
                                            class="btn-delete" 
                                            onclick="return confirm('Are you sure you want to delete this trainer?');" 
                                            title="Delete">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">No trainers found in the system.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="pagination-footer">
                    <div class="pagination-info">
                        Showing <?php echo ($offset + 1); ?> to <?php echo min($totalTrainers, $offset + $limit); ?> of <?php echo $totalTrainers; ?> trainers
                    </div>

                    <div class="pagination-controls">
                        <a href="index.php?view=trainers&page=<?php echo max(1, $page - 1); ?>&search=<?php echo urlencode($search); ?>"
                            class="page-nav-btn <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="index.php?view=trainers&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
                                class="page-num <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <a href="index.php?view=trainers&page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo urlencode($search); ?>"
                            class="page-nav-btn <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
</div>
