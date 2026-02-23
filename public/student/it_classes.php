<?php
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Subject.php';

$studentObj = new Student();
$subjectObj = new Subject();

$student_id = $_SESSION['student_id'] ?? 0;
$all_subjects = $subjectObj->getAll();

$cart_items = [];
$cart_subject_ids = [];

if ($student_id) {
    $cart_items = $studentObj->getCartItems($student_id);
    $cart_subject_ids = array_column($cart_items, 'subject_id');
}
?>

<div class="it-classes-container">
    <div class="page-header">
        <div class="header-left">
            <h1>IT Classes</h1>
            <p>Explore and enroll in our comprehensive IT training programs</p>
        </div>
        <div class="header-right">
            <a href="index.php?view=cart" class="cart-badge">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?= count($cart_items) ?></span>
            </a>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="classes-grid">
        <?php if(!empty($all_subjects)): ?>
            <?php foreach($all_subjects as $subject): ?>
                <div class="class-card">
                    <div class="class-image">
                        <?php if($subject['image_path'] && $subject['image_path'] != 'default_subject.png'): ?>
                            <img src="../../assets/images/subjects<?= htmlspecialchars($subject['image_path']) ?>" 
                                    alt="<?= htmlspecialchars($subject['title']) ?>">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="class-content">
                        <h3><?= htmlspecialchars($subject['title']) ?></h3>
                        <p class="class-description"><?= htmlspecialchars($subject['description'] ?? 'No description available') ?></p>
                            <div class="class-stats">
                                <div class="stat-item">
                                    <span class="stat-value">12 weeks</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-value"><i class="fas fa-user-graduate"></i> 24</span>
                                </div>
                            </div>
                        <div class="class-footer">
                            <span class="class-price">$<?= number_format($subject['fee'], 2) ?></span>
                            
                            <?php if(isset($cart_subject_ids) && in_array($subject['subject_id'], $cart_subject_ids)): ?>
                                <button class="btn-in-cart" disabled>
                                    <i class="fas fa-check"></i> In Cart
                                </button>
                            <?php else: ?>
                                <a href="student/process_cart.php?action=add&subject_id=<?= $subject['subject_id'] ?>" class="btn-add-to-cart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-classes">No classes available at the moment.</p>
        <?php endif; ?>
    </div>
</div>