<?php
require_once __DIR__ . '/../../classes/Student.php';
require_once __DIR__ . '/../../classes/Subject.php';

// THEN create the objects
$studentObj = new Student();
$subjectObj = new Subject();

// THEN get data
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
    <!-- Header -->
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

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="tab-btn active">All</button>
        <button class="tab-btn">Beginner</button>
        <button class="tab-btn">Intermediate</button>
        <button class="tab-btn">Advanced</button>
    </div>

    <!-- Classes Grid -->
    <div class="classes-grid">
        <?php if(!empty($all_subjects)): ?>
            <?php foreach($all_subjects as $subject): ?>
                <div class="class-card">
                    <div class="class-image">
                        <?php if($subject['image_path'] && $subject['image_path'] != 'default_subject.png'): ?>
                            <img src="../../assets/images/subjects/<?= htmlspecialchars($subject['image_path']) ?>" alt="<?= htmlspecialchars($subject['title']) ?>">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="class-content">
                        <h3><?= htmlspecialchars($subject['title']) ?></h3>
                        <p class="class-description"><?= htmlspecialchars($subject['description'] ?? 'No description available') ?></p>
                        
                        <div class="class-meta">
                            <span class="class-level">Beginner</span>
                            <span class="class-duration">12 weeks</span>
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

<style>
.it-classes-container {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.page-header h1 {
    font-size: 24px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.page-header p {
    color: #64748b;
    font-size: 14px;
}

.cart-badge {
    position: relative;
    padding: 10px 16px;
    background: white;
    border-radius: 8px;
    color: #1e293b;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.2s;
}

.cart-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.cart-badge i {
    font-size: 18px;
    color: #6366f1;
}

.cart-count {
    background: #6366f1;
    color: white;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 30px;
    background: white;
    padding: 8px;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.tab-btn {
    padding: 8px 24px;
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.tab-btn:hover {
    background: #f1f5f9;
    color: #334155;
}

.tab-btn.active {
    background: #6366f1;
    color: white;
}

/* Classes Grid */
.classes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.class-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.class-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
}

.class-image {
    height: 160px;
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.class-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
    color: white;
    font-size: 48px;
}

.class-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.class-content h3 {
    font-size: 18px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 8px;
}

.class-description {
    font-size: 14px;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 16px;
    flex: 1;
}

.class-meta {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
}

.class-level {
    padding: 4px 12px;
    background: #eef2ff;
    color: #6366f1;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.class-duration {
    padding: 4px 12px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.class-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.class-price {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
}

.class-price::before {
    content: '$';
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    margin-right: 2px;
}

.btn-add-to-cart {
    padding: 10px 20px;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-add-to-cart:hover {
    background: #4f46e5;
    transform: scale(1.05);
}

.btn-in-cart {
    padding: 10px 20px;
    background: #10b981;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: default;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.no-classes {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px;
    color: #64748b;
    font-size: 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .classes-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-tabs {
        flex-wrap: wrap;
    }
    
    .tab-btn {
        flex: 1;
        text-align: center;
    }
}
</style>