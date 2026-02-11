<?php
require_once __DIR__ . '/../../classes/Room.php';
$roomObj = new Room();


if (isset($_POST['save_room'])) {
    $name = trim($_POST['room_name']);
    $branch = $_POST['branch'];
    $capacity = (int)$_POST['capacity'];

    if ($roomObj->create($name, $branch, $capacity)) {
        header("Location: ../index.php?view=rooms&msg=created");
    } else {
        header("Location: ../index.php?view=rooms&msg=error");
    }
    exit();
}


if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    if ($roomObj->delete($id)) {
        header("Location: ../index.php?view=rooms&msg=deleted");
    } else {
        header("Location: ../index.php?view=rooms&msg=error");
    }
    exit();
}


if (isset($_POST['update_room'])) {
    $id = (int)$_POST['room_id'];
    $name = trim($_POST['room_name']);
    $branch = $_POST['branch'];
    $capacity = (int)$_POST['capacity'];

    if ($roomObj->update($id, $name, $branch, $capacity)) {
        header("Location: ../index.php?view=rooms&msg=updated");
    } else {
        header("Location: ../index.php?view=edit_room&id=$id&msg=error");
    }
    exit();
}