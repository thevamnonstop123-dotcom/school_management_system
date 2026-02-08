<?php 
require_once '../../classes/Trainer.php'; 

$trainerObj = new Trainer();

$search = $_GET['search'] ?? '';

$limit = 6; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// if searching, or in the searchbar not empty
if(!empty($search)) {
    $allTrainers = $trainerObj->searchWithPagination($search, $offset, $limit);
    $totalTrainers = count($trainerObj->search($search)); 
} else {
    // If not searching, use standard pagination
    $allTrainers = $trainerObj->getTrainersWithPagination($offset, $limit);
    $totalTrainers = $trainerObj->getTotalCount();
}

$totalPages = ceil($totalTrainers / $limit);

include '../partials/header.php'; 
?>

<div class="container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header>
            <div class="title-section">
                <h1>Trainers Management</h1>
                <p>Manage and view all trainers in the system</p>
            </div>
            <div class="header-actions">
                <button class="btn-export"><i class="fas fa-download"></i> Export</button>
                <button class="btn-add"><i class="fas fa-plus"></i> Add Trainer</button>
            </div>
        </header>

        <section class="table-container">
            <form action="trainer.php" method="GET" class="search-bar">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search...">
            </form>
            
            <?php if(isset($_SESSION['flash_message'])): ?>
                <div class="<?php echo $_SESSION['flash_type'] === 'delete' ? 'delete-message' : 'create-message'; ?>">
                    <?php 
                    echo $_SESSION['flash_message'];
                    unset($_SESSION['flash_message']);
                    unset($_SESSION['flash_type']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="create-message">
                    Trainer added successfully!
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
                        <th>Actions</th>
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
                                        $imageFolder = (strpos($row['avatar_url'], 'default') !== false) ? 'images/' : 'images/trainers/';
                                    ?>
                                    <img src="../../assets/<?php echo $imageFolder . $row['avatar_url']; ?>" alt="Avatar" class="avatar-img">
                                    <div>
                                        <strong><?php echo htmlspecialchars($row['full_name']); ?></strong>
                                        <small><?php echo htmlspecialchars($row['email']); ?></small>
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
                                    <i class="far fa-eye"></i> 
                                    
                                    <a href="edit_trainer.php?id=<?php echo $row['trainer_id']; ?>">
                                        <i class="far fa-edit"></i> 
                                    </a>

                                    <a href="delete_trainer.php?id=<?php echo $row['trainer_id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this trainer?');">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No trainers found in the database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="pagination-controls">
                <a href="?page=<?php echo max(1, $page - 1); ?>&search=<?php echo urlencode($search); ?>" class="page-btn">
                    <i class="fas fa-chevron-left"></i>
                </a>

                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                    class="page-btn <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <a href="?page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo urlencode($search); ?>" class="page-btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </section> 
    </main>
</div>

<div id="addTrainerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Trainer</h2>
            <span class="close-btn">&times;</span>
        </div>
        
        <form action="process_trainer.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Trainer Photo</label>
                <input type="file" name="avatar" accept="image/*">
            </div>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required placeholder="e.g. Michael Johnson">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="michael@training.com">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" required placeholder="09xxxxxxxxx">
            </div>

            <div class="form-group">
                <label>Specialization</label>
                <select name="specialization">
                    <option value="Web Development">Web Development</option>
                    <option value="UI/UX Design">UI/UX Design</option>
                    <option value="Data Science">Data Science</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="submit" name="add_trainer" class="btn-save">Save Trainer</button>
            </div>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>