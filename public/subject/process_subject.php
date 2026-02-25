<?php
session_start();
require_once __DIR__ . '/../../classes/Subject.php';
$subjectObj = new Subject();

// Handle Delete
if (isset($_GET['delete_id'])) {
    if ($subjectObj->delete($_GET['delete_id'])) {
        header("Location: ../index.php?view=subjects&msg=deleted");
    } else {
        header("Location: ../index.php?view=subjects&msg=error");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageName = $_POST['old_image'] ?? "default_subject.png";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $imageName = $_POST['old_image'] ?? "default_subject.png";
    
    if (!empty($_FILES['subject_image']['name'])) {
            $imageName = time() . "_" . $_FILES['subject_image']['name'];
            $uploadPath = "../../assets/images/subjects" . $imageName;
            
            if(move_uploaded_file($_FILES['subject_image']['tmp_name'], $uploadPath)) {
            } else {
                die("Upload failed. Check if $uploadPath exists and is writable.");
            }
        }
    }

    $data = [
        'title'       => $_POST['title'],
        'description' => $_POST['description'],
        'fee'         => $_POST['fee'],
        'image_path'  => $imageName
    ];

    if (isset($_POST['update_subject'])) {
        $data['subject_id'] = $_POST['subject_id'];
        $result = $subjectObj->update($data);
        $msg = "updated";
    } else {
        $result = $subjectObj->create($data);
        $msg = "created";
    }

    header("Location: ../index.php?view=subjects&msg=" . ($result ? $msg : "error"));
    exit();
}