<?php 
require_once '../../classes/Trainer.php';
$trainerObj = new Trainer();

$id = $_GET['id'] ?? null;
$trainer = $trainerObj->getTrainerById($id);

// If trainer doesn't exist, go back
if (!$trainer) {
    header("Location: trainer.php");
    exit();
}

include '../partials/header.php';
?>

<div class="container">
    <main class="main-content">
        <h2>Edit Trainer: <?php echo htmlspecialchars($trainer['full_name']); ?></h2>
        
        <form action="update_trainer_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="trainer_id" value="<?php echo $trainer['trainer_id']; ?>">
            <input type="hidden" name="current_avatar" value="<?php echo $trainer['avatar_url']; ?>">
            
            <div class="form-group">
                <label>Change Photo (Leave blank to keep current)</label>
                <input type="file" name="avatar" accept="image/*">
                <p><small>Current: <?php echo htmlspecialchars($trainer['avatar_url']); ?></small></p>
            </div>
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($trainer['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($trainer['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($trainer['phone_number']); ?>" required>
            </div>

            <div class="form-group">
                <label>Specialization</label>
                <select name="specialization">
                    <option value="Web Development" <?php echo ($trainer['specialization'] == 'Web Development') ? 'selected' : ''; ?>>Web Development</option>
                    <option value="UI/UX Design" <?php echo ($trainer['specialization'] == 'UI/UX Design') ? 'selected' : ''; ?>>UI/UX Design</option>
                    <option value="Data Science" <?php echo ($trainer['specialization'] == 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="trainer.php" class="btn-cancel" style="text-decoration: none; padding: 10px; color: #666;">Cancel</a>
                <button type="submit" name="update_trainer" class="btn-save">Update Changes</button>
            </div>
        </form>
    </main>
</div>

<?php include '../partials/footer.php'; ?>