<div class="main-content">
    <div class="page-header">
        <div class="header-left">
            <h1>Create New Branch</h1>
            <p>Add a new branch location to your network</p>
        </div>
    </div>

    <nav class="breadcrumb">
        <i class="fas fa-home"></i> 
        <a href="index.php">Dashboard</a> 
        <i class="fas fa-chevron-right separator"></i> 
        <a href="index.php?view=branches">Branches</a> 
        <i class="fas fa-chevron-right separator"></i> 
        <span class="active">Create Branch</span>
    </nav>

    <div class="form-container card">
        <div class="form-header">
            <div class="icon-circle">
                <i class="fas fa-code-branch"></i>
            </div>
            <div class="form-title-text">
                <h3>Branch Information</h3>
                <p>Fill in the details for the new branch</p>
            </div>
        </div>

        <form action="branch/process_branch.php" method="POST" class="styled-form">
            <div class="form-group">
                <label for="branch_name">Branch Name <span class="required">*</span></label>
                <input type="text" id="branch_name" name="branch_name" placeholder="Enter branch name (e.g., Downtown Office)" required>
                <small class="input-info">Maximum 100 characters</small>
            </div>

            <div class="form-group">
                <label for="location">Branch Address</label>
                <textarea id="location" name="location" rows="3" placeholder="Enter complete address"></textarea>
                <small class="input-info">Optional</small>
            </div>

            <div class="form-group">
                <label for="phone">Phone <span class="required">*</span></label>
                <input type="text" id="phone" name="phone" placeholder="09685500000" required>
            </div>

            <div class="form-group">
                <label for="status">Status <span class="required">*</span></label>
                <select id="status" name="status" required>
                    <option value="" disabled selected>Select Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="index.php?view=branches" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Branches
                </a>
                <div class="action-right">
                    <button type="reset" class="btn-cancel">Cancel</button>
                    <button type="submit" name="add_branch" class="btn-primary">
                        <i class="fas fa-plus"></i> Create Branch
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>