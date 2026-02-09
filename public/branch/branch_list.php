<?php
require_once '../../classes/Branch.php';

$branchObj = new Branch();
$branches = $branchObj->getAll();
$stats = $branchObj->getStats();

include '../partials/header.php';
include '../partials/sidebar.php'; 
?>

<div class="main-content">
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> Branch created successfully!
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-trash"></i> Branch deleted successfully.
        </div>
    <?php endif; ?>
    <div class="page-header">
        <div class="header-left">
            <h1>Branch Management</h1>
            <p>Manage all your branch locations</p>
        </div>
        <a href="create_branch.php" class="btn-add">
            <i class="fas fa-plus"></i> Add Branch
        </a>
    </div>

    <nav class="breadcrumb">
        <i class="fas fa-home"></i> 
        <a href="../dashboard/index.php">Dashboard</a> 
        <i class="fas fa-chevron-right separator"></i> 
        <span>Branches</span> 
        <i class="fas fa-chevron-right separator"></i> 
        <span class="active">Branch List</span>
    </nav>

    <div class="stats-grid">
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
    </div>

    <div class="table-container card">
        <div class="table-header">
            <h3>All Branches</h3>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="branchSearch" placeholder="Search branches...">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Branch Name</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                            <span class="status-badge <?= strtolower($branch['status']); ?>">
                                <?= $branch['status']; ?>
                            </span>
                        </td>
                        <td class="actions-col">
                            <a href="edit_branch.php?id=<?= $branch['branch_id']; ?>" class="edit-link"><i class="far fa-edit"></i></a>
                            <a href="process_branch.php?delete_id=<?= $branch['branch_id']; ?>" class="delete-link" onclick="return confirm('Delete?')"><i class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 20px;">No branches found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="table-footer">
            <div class="footer-info">
                Showing 1 to <?= count($branches); ?> of <?= $stats['total']; ?> results
            </div>
            <div class="pagination">
                <button class="btn-page" disabled>Previous</button>
                <button class="btn-page active">1</button>
                <button class="btn-page">2</button>
                <button class="btn-page">3</button>
                <button class="btn-page">Next</button>
            </div>
        </div>
    </div> 
</div> 
<?php include '../partials/footer.php'; ?>