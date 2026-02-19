<?php
require_once __DIR__ . '/../../classes/Schedule.php';
$scheduleObj = new Schedule();

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($scheduleObj->deleteSchedule($id)) {
        header("Location: ../index.php?view=schedule&status=deleted");
    } else {
        header("Location: ../index.php?view=schedule&status=error");
    }
    exit();
}


if (isset($_POST['create_schedule'])) {
    $data = [
        'room_id'     => $_POST['room_id'],
        'trainer_id'  => $_POST['trainer_id'],
        'subject_id'  => $_POST['subject_id'],
        'branch_id'   => $_POST['branch_id'],
        'day_of_week' => $_POST['day_of_week'],
        'start_time'  => $_POST['start_time'],
        'end_time'    => $_POST['end_time'],
        'status'      => $_POST['status']
    ];

    if ($scheduleObj->create($data)) {
        header("Location: ../index.php?view=schedule&status=created");
    } else {
        header("Location: ../index.php?view=create_schedule&status=error");
    }
    exit();
}

if (isset($_POST['update_schedule'])) {
    $id = $_POST['schedule_id'];
    $data = [
        'room_id'     => $_POST['room_id'] ?? 0,
        'trainer_id'  => $_POST['trainer_id'] ?? 0,
        'subject_id'  => $_POST['subject_id'] ?? 0,
        'branch_id'   => $_POST['branch_id'] ?? 0,
        'day_of_week' => $_POST['day_of_week'] ?? '',
        'start_time'  => $_POST['start_time'] ?? '',
        'end_time'    => $_POST['end_time'] ?? '',
        'status'      => $_POST['status'] ?? ''
    ];

    if ($scheduleObj->update($id, $data)) {
        header("Location: ../index.php?view=schedule&status=updated");
    } else {
        header("Location: ../index.php?view=edit_schedule&id=$id&status=error");
    }
    exit();
}