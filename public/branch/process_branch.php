<?php
require_once '../../classes/Branch.php';

$branchObj = new Branch();

// Check if the form was submitted
if (isset($_POST['add_branch'])) {
    $name     = $_POST['branch_name'];
    $location = $_POST['location'];
    $phone    = $_POST['phone'];
    $status   = $_POST['status'];

    if ($branchObj->create($name, $location, $phone, $status)) {
        header("Location: branch_list.php?msg=added");
    } else {
        header("Location: create_branch.php?msg=error");
    }
    exit();
}

if(isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];

    if($branchObj->delete($id)) {
        header("Location: branch_list.php?msg=deleted");
    } else {
        header("Location: branch_list.php?msg=error");
    }
    exit();
}


if (isset($_POST['update_branch'])) {
    $id       = $_POST['branch_id'];
    $name     = $_POST['branch_name'];
    $location = $_POST['location'];
    $phone    = $_POST['phone'];
    $status   = $_POST['status'];

    if ($branchObj->update($id, $name, $location, $phone, $status)) {
        header("Location: branch_list.php?msg=updated");
    } else {
        header("Location: edit_branch.php?id=$id&msg=error");
    }
    exit();
}