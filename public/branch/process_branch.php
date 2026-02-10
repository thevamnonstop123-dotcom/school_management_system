<?php
require_once __DIR__ . '/../../classes/Branch.php';

$branchObj = new Branch();

if (isset($_POST['add_branch'])) {
    $name     = trim($_POST['branch_name']);
    $location = trim($_POST['location']);
    $phone    = trim($_POST['phone']);
    $status   = $_POST['status'];

    if ($branchObj->create($name, $location, $phone, $status)) {
        header("Location: ../index.php?view=branches&msg=added");
    } else {
        header("Location: ../index.php?view=create_branch&msg=error");
    }
    exit();
}

if(isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];

    if($branchObj->delete($id)) {
        header("Location: ../index.php?view=branches&msg=deleted");
    } else {
        header("Location: ../index.php?view=branches&msg=error");
    }
    exit();
}

if (isset($_POST['update_branch'])) {
    $id       = (int)$_POST['branch_id']; 
    $name     = trim($_POST['branch_name']);
    $location = trim($_POST['location']);
    $phone    = trim($_POST['phone']);
    $status   = $_POST['status'];

    if ($branchObj->update($id, $name, $location, $phone, $status)) {
        header("Location: ../index.php?view=branches&msg=updated");
    } else {
        header("Location: ../index.php?view=edit_branch&id=$id&msg=error");
    }
    exit();
}