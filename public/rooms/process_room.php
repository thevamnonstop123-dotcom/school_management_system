<?php
require_once '../../classes/Room.php';
$roomObj = new Room();

// Handle CREATE
if (isset($_POST['save_room'])) {
    $name = $_POST['room_name'];
    $branch = $_POST['branch'];
    $capacity = $_POST['capacity'];

    if ($roomObj->create($name, $branch, $capacity)) {
        header("Location: rooms.php?msg=created");
    } else {
        header("Location: rooms.php?msg=error");
    }
    exit();
}

// Handle DELETE
if (isset($_GET['delete_id'])) {
    if ($roomObj->delete($_GET['delete_id'])) {
        header("Location: rooms.php?msg=deleted");
    } else {
        header("Location: rooms.php?msg=error");
    }
    exit();
}

// Handle UPDATE
if (isset($_POST['update_room'])) {
    $id = $_POST['room_id'];
    $name = $_POST['room_name'];
    $branch = $_POST['branch'];
    $capacity = $_POST['capacity'];

    if ($roomObj->update($id, $name, $branch, $capacity)) {
        header("Location: rooms.php?msg=updated");
    } else {
        header("Location: rooms.php?msg=error");
    }
    exit();
}