<?php
require_once '../../classes/Branch.php';

$branchObj = new Branch();

// Get the ID from the URL and fetch the branch data
if (!isset($_GET['id'])) {
    header("Location: branch_list.php");
    exit();
}

$id = (int)$_GET['id'];
$branch = $branchObj->getById($id);

// If branch doesn't exist, go back
if (!$branch) {
    header("Location: branch_list.php");
    exit();
}

include '../partials/header.php';
include '../partials/sidebar.php'; 
?>

<div class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Edit Branch</h1>
            <p>Update information for existing branch locations</p>
        </div>
    </div>

    <nav class="breadcrumb">
        <i class="fas fa-home"></i> 
        <a href="../dashboard/index.php">Dashboard</a> 
        <i class="fas fa-chevron-right separator"></i> 
        <a href="branch_list.php">Branches</a> 
        <i class="fas fa-chevron-right separator"></i> 
        <span class="active">Edit Branch</span>
    </nav>

    <div class="form-container card">
        <div class="form-header">
            <div class="icon-circle">
                <i class="fas fa-code-branch"></i> </div>
            <div class="form-title-text">
                <h3>Branch Information</h3>
                <p>Fill in the details for the branch</p>
            </div>
        </div>

        <form action="process_branch.php" method="POST" class="styled-form">
            <input type="hidden" name="branch_id" value="<?= $branch['branch_id']; ?>">

            <div class="form-group">
                <label for="branch_name">Branch Name <span class="required">*</span></label>
                <input type="text" id="branch_name" name="branch_name" value="<?= htmlspecialchars($branch['branch_name']); ?>" placeholder="Enter branch name (e.g., Downtown Office)" required>
                <span class="input-info">Maximum 100 characters</span>
            </div>

            <div class="form-group">
                <label for="location">Branch Address</label>
                <textarea id="location" name="location" rows="3" placeholder="Enter complete address including street, city, state, and postal code"><?= htmlspecialchars($branch['location']); ?></textarea>
                <span class="input-info">Optional - Provide the full address of the branch</span>
            </div>

            <div class="form-group">
                <label for="phone">Phone <span class="required">*</span></label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($branch['phone']); ?>" placeholder="09665500000" required>
                <span class="input-info">Maximum 11 Numbers</span>
            </div>

            <div class="form-group">
                <label for="status">Status <span class="required">*</span></label>
                <select id="status" name="status" required>
                    <option value="Active" <?= $branch['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?= $branch['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="branch_list.php" class="btn-cancel-link">
                    <i class="fas fa-arrow-left"></i> Back to Branches
                </a>
                <div class="action-right">
                    <button type="button" onclick="window.location.href='branch_list.php'" class="btn-cancel">Cancel</button>
                    <button type="submit" name="update_branch" class="btn-primary">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>