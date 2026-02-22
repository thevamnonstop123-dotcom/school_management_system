<?php
session_start();

if(!isset($_SESSION["user_id"]) && !isset($_SESSION["student_id"])) {
    header("Location: auth/login.php");
    exit();
}

$admin_roles = ['Administrator', 'Super Admin', 'Manager'];
$isAdmin = (isset($_SESSION["user_role"]) && in_array($_SESSION["user_role"], $admin_roles));
$isStudent = (isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "student");

$view = $_GET['view'] ?? 'trainer';
$valid_views = ['branches', 'create_branch', 'edit_branch', 'subjects', 'edit_subject', 
                'schedule', 'create_schedule', 'edit_schedule', 'trainers', 'rooms', 
                'edit_room', 'payment_confirm', 'students', 'courses', 
                'it_classes', 'cart', 'my_class', 'student_schedule', 'checkout', 'profile'];

require_once '../classes/Branch.php';
require_once '../classes/Trainer.php';
require_once '../classes/Room.php';
require_once '../classes/Subject.php';
require_once '../classes/Schedule.php';
require_once '../classes/Student.php';
require_once '../classes/Payment.php';

$branchObj = new Branch();
$trainerObj = new Trainer();
$roomObj = new Room();
$subjectObj = new Subject();
$scheduleObj = new Schedule();
$studentObj = new Student();
$paymentObj = new Payment();

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<div class="main-content">
<?php

    $view_map = [
        // Admin views
        'branches' => 'branch/branch_list.php',
        'create_branch' => 'branch/create_branch.php',
        'edit_branch' => 'branch/edit_branch.php',
        'subjects' => 'subject/subjects.php',
        'edit_subject' => 'subject/subjects.php',
        'schedule' => 'schedule/schedule_list.php',
        'students' => 'students/students.php',
        'courses' => 'courses/courses.php',
        'create_schedule' => 'schedule/create_schedule.php',
        'edit_schedule' => 'schedule/edit_schedule.php',
        'trainers' => 'trainer/trainer.php',
        'rooms' => 'rooms/rooms.php',
        'edit_room' => 'rooms/edit_room.php',
        'payment_confirm' => 'reports/payment_confirm.php',
        
        // Student views
        'it_classes' => 'student/it_classes.php',
        'cart' => 'student/cart.php',
        'my_class' => 'student/my_class.php',
        'student_schedule' => 'student/schedule.php',
        'checkout' => 'student/checkout.php',
        'profile' => 'student/profile.php'
    ];

    switch($view) {
        // Admin data preparation
        case 'subjects':
        case 'edit_subject':
            if ($view == 'edit_subject' && isset($_GET['id'])) {
                $subject_data = $subjectObj->getById($_GET['id']);
            }
            $all_subjects = $subjectObj->getAll();
            break;
            
        case 'create_schedule':
        case 'edit_schedule':
            $rooms = $roomObj->getAll();
            $trainers = $trainerObj->getAllTrainers();
            $subjects = $subjectObj->getAll();
            $branches = $branchObj->getAll();
            
            if ($view == 'edit_schedule' && isset($_GET['id'])) {
                $schedule_data = $scheduleObj->getById($_GET['id']);
            }
            break;
            
        case 'trainers':
            $trainers = $trainerObj->getAllTrainers();
            break;
            
        case 'rooms':
            $all_rooms = $roomObj->getAll();
            break;
            
        case 'it_classes':
        case 'cart':
        case 'my_class':
        case 'student_schedule':
        case 'checkout':
        case 'profile':
            break;
    }
    
    // Include the view file if it exists in map
    if (isset($view_map[$view])) {
        include $view_map[$view];
    } else {
        if ($isStudent) {
            header("Location: index.php?view=my_class");
        } else {
            header("Location: index.php?view=trainers");
        }
        exit();
    }
?>
</div>

<?php include 'partials/footer.php'; ?>