<?php
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit();
}

$isAdmin = (isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "admin");

require_once '../classes/Branch.php';
require_once '../classes/Trainer.php';
require_once '../classes/Room.php';
require_once '../classes/Subject.php';
require_once '../classes/Schedule.php';

$branchObj = new Branch();
$trainerObj = new Trainer();
$roomObj = new Room();
$subjectObj = new Subject();
$scheduleObj = new Schedule();


include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<div class="main-content">
    <?php
    $view = $_GET['view'] ?? 'trainer';

    switch($view) {
    case 'branches':
        include 'branch/branch_list.php';
        break;
    case 'subjects':
        $all_subjects = $subjectObj->getAll();
        include 'subject/subjects.php';
        break;

    case 'edit_subject':
        if(isset($_GET['id'])) {
            $subject_data = $subjectObj->getById($_GET['id']);
            $all_subjects = $subjectObj->getAll(); 
            include 'subject/subjects.php';
        }
        break;
    case 'schedule':
        $search = $_GET['search'] ?? '';
        $limit = 6;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        if (!empty($search)) {
            $all_schedules = $scheduleObj->searchWithPagination($search, $offset, $limit);
            $totalSchedules = $scheduleObj->countSearch($search);
        } else {
            $all_schedules = $scheduleObj->getSchedulesWithPagination($offset, $limit);
            $totalSchedules = $scheduleObj->getTotalCount();
        }
        
        $totalPages = ceil($totalSchedules / $limit);
        include 'schedule/schedule_list.php';
        break;
    case 'create_schedule':
        $rooms = $roomObj->getAll();
        $trainers = $trainerObj->getAllTrainers();
        $subjects = $subjectObj->getAll();
        $branches = $branchObj->getAll();
        include 'schedule/create_schedule.php';
        break;
    case 'edit_schedule':
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $schedule_data = $scheduleObj->getById($id);
            $rooms = $roomObj->getAll();
            $trainers = $trainerObj->getAllTrainers();
            $subjects = $subjectObj->getAll();
            $branches = $branchObj->getAll();
            
            include 'schedule/edit_schedule.php';
        }
        break;
    case 'create_branch':
        include 'branch/create_branch.php';
        break;
    case 'edit_branch':
        include 'branch/edit_branch.php';
        break;
    case 'trainers':
            $trainers = $trainerObj->getAllTrainers();
            include 'trainer/trainer.php';
            break;
    case 'rooms':
                $all_rooms = $roomObj->getAll();
                include 'rooms/rooms.php';
            break;
    case 'edit_room':
                include 'rooms/edit_room.php';
        break;
    default:
            echo "<h1>Welcome</h1><p>Please select a menu.</p>";
        break;
    }
    ?>
</div>

<?php include 'partials/footer.php'; ?>