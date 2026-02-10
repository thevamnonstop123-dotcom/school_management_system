<?php
require_once '../classes/Branch.php';
require_once '../classes/Trainer.php';
require_once '../classes/Room.php';

$branchObj = new Branch();
$trainerObj = new Trainer();
$roomObj = new Room();

include 'partials/header.php'; 
include 'partials/sidebar.php'; 
?>

<div class="main-content">
    <?php
    $view = $_GET['view'] ?? 'branches';

    switch($view) {
    case 'branches':
        include 'branch/branch_list.php';
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