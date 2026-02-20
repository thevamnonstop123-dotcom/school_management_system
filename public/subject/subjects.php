<?php
$isEdit = ($view === 'edit_subject' && isset($subject_data));
$formTitle = $isEdit ? "Edit Subject" : "Add New Subject";
$btnName = $isEdit ? "update_subject" : "create_subject";
?>

<header>
    <h1>Subjects Management</h1>
    <p>Manage your training subjects and fees</p>
</header>

<div class="rooms-grid <?= !$isAdmin ? 'is-staff' : '' ?> admin-layout">
    <div class="card rooms-list-section">
        <div class="list-header">
            <h3>All Subjects</h3>
            <div class="search-box">
                <input type="text" id="subjectSearch" placeholder="Search subjects...">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Icon</th>
                    <th>Subject Details</th>
                    <th>Fee</th>
                    <?php if($isAdmin): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_subjects as $s): ?>
                <tr>
                    <td class="id-cell"><?= str_pad($s['subject_id'], 3, '0', STR_PAD_LEFT) ?></td>
                    <td><div class="subject-icon">JS</div></td>
                    <td>
                        <strong><?= htmlspecialchars($s['title']) ?></strong>
                        <p style="font-size: 12px; color: #64748b; margin: 0;"><?= htmlspecialchars($s['description']) ?></p>
                    </td>
                    <td class="fee-text">$<?= number_format($s['fee'], 2) ?></td>
                    <?php if($isAdmin): ?>
                        <td class="actions-cell">
                            <a href="index.php?view=edit_subject&id=<?= $s['subject_id'] ?>" class="btn-icon-edit">
                                <i class="far fa-edit"></i>
                            </a>
                            <a href="subject/process_subject.php?delete_id=<?= $s['subject_id'] ?>" 
                               class="btn-icon-delete" onclick="return confirm('Delete this subject?')">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if($isAdmin): ?>
    <div class="card add-room-section">
        <h3><?= $formTitle ?></h3>
        <form action="subject/process_subject.php" method="POST" enctype="multipart/form-data">
            <?php if($isEdit): ?>
                <input type="hidden" name="subject_id" value="<?= $subject_data['subject_id'] ?>">
                <input type="hidden" name="old_image" value="<?= $subject_data['image_path'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Subject Title</label>
                <input type="text" name="title" value="<?= $isEdit ? htmlspecialchars($subject_data['title']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Upload Image <?= $isEdit ? "(Optional)" : "" ?></label>
                <input type="file" name="subject_image">
            </div>
            <div class="form-group">
                <label>Course Fee ($)</label>
                <input type="number" step="0.01" name="fee" value="<?= $isEdit ? $subject_data['fee'] : '0.00' ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?= $isEdit ? htmlspecialchars($subject_data['description']) : '' ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" name="<?= $btnName ?>" class="btn-save"><?= $isEdit ? 'Update' : 'Create' ?> Subject</button>
                <?php if($isEdit): ?>
                    <a href="index.php?view=subjects" class="btn-cancel" style="display: block; text-align: center; text-decoration: none; padding: 10px;">Cancel</a>
                <?php else: ?>
                    <button type="reset" class="btn-cancel">Reset</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>