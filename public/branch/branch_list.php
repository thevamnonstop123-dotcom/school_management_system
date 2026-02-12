<?php
require_once __DIR__ . '/../../classes/Branch.php';

$branchObj = new Branch();

$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $branches = $branchObj->search($search);
} else {
    $branches = $branchObj->getAll();
}

$stats = $branchObj->getStats();
?>

<div class="main-content">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'added'): ?>
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> Branch created successfully!
            </div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div style="background: #e0f2fe; color: #075985; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i> Branch updated successfully!
            </div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fas fa-trash"></i> Branch deleted successfully.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="page-header">
        <div class="header-left">
            <h1>Branch Management</h1>
            <p>Manage all your branch locations</p>
        </div>
       
        <?php if($isAdmin): ?>
            <a href="index.php?view=create_branch" class="btn-add">
                <i class="fas fa-plus"></i> Add Branch
            </a>
        <?php endif; ?>
    </div>

    <nav class="breadcrumb">
        <i class="fas fa-home"></i> 
        <a href="index.php">Dashboard</a> 
            <?php if($isAdmin): ?>
                <i class="fas fa-chevron-right separator"></i> 
                <a href="index.php?view=branches">Branches</a>
            <?php endif; ?>
        <i class="fas fa-chevron-right separator"></i> 
        <span class="active">Branch List</span>
    </nav>

    <div class="stats-grid">
        <?php if($isAdmin): ?>
            <div class="stat-card">
                <div class="stat-content">
                    <span class="stat-label">Total Branches</span>
                    <h2 class="stat-number"><?= $stats['total']; ?></h2>
                </div>
                <div class="stat-icon-box blue-bg"><i class="fas fa-building"></i></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-content">
                    <span class="stat-label">Active Branches</span>
                    <h2 class="stat-number text-success"><?= $stats['active']; ?></h2>
                </div>
                <div class="stat-icon-box green-bg"><i class="fas fa-check-circle"></i></div>
            </div>
            
            <div class="stat-card">
                <div class="stat-content">
                    <span class="stat-label">Inactive Branches</span>
                    <h2 class="stat-number text-danger"><?= $stats['inactive']; ?></h2>
                </div>
                <div class="stat-icon-box red-bg"><i class="fas fa-exclamation-circle"></i></div>
            </div>

            <div class="stat-card">
                <div class="stat-content">
                    <span class="stat-label">This Month</span>
                    <h2 class="stat-number"><?= $stats['this_month'] ?? 0; ?></h2>
                </div>
                <div class="stat-icon-box purple-bg">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="table-container card">
        <div class="table-header">
            <h3>All Branches</h3>
            <form action="index.php" method="GET" class="search-box">
                <input type="hidden" name="view" value="branches">
                <i class="fas fa-search"></i>
                <input type="text" name="search" id="branchSearch" placeholder="Search branches..." value="<?= htmlspecialchars($search); ?>">
                <button type="submit" style="display:none">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Branch Name</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Status</th>
                    
                    <?php if($isAdmin): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($branches)): ?>
                    <?php foreach ($branches as $branch): ?>
                    <tr>
                        <td class="id-col"><?= str_pad($branch['branch_id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td class="name-col"><strong><?= htmlspecialchars($branch['branch_name']); ?></strong></td>
                        <td class="loc-col"><?= htmlspecialchars($branch['location']); ?></td>
                        <td class="contact-col"><?= htmlspecialchars($branch['phone']); ?></td>
                        <td>
                            <span class="status-badge <?= strtolower($branch['status'] ?? 'active'); ?>">
                                <?= htmlspecialchars($branch['status'] ?? 'Active'); ?>
                            </span>
                        </td>
                        <td class="actions-col">
                            <?php  if($isAdmin): ?>
                                <a href="index.php?view=edit_branch&id=<?= $branch['branch_id']; ?>" class="edit-link">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="branch/process_branch.php?delete_id=<?= $branch['branch_id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this branch?')">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 20px;">No branches found matching your search.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="table-footer">
            <div class="footer-info">
                Showing <?= count($branches); ?> results
            </div>
            <div class="pagination">
                <button class="btn-page" disabled>Previous</button>
                <button class="btn-page active">1</button>
                <button class="btn-page">Next</button>
            </div>
        </div>
    </div> 
</div>