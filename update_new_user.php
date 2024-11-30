<?php
include('connection.php');

// ตั้งค่าเขตเวลา
date_default_timezone_set('Asia/Bangkok');

// รับค่าจากฟอร์ม
$firstname = mysqli_real_escape_string($con, $_POST['firstname']);
$surname = mysqli_real_escape_string($con, $_POST['surname']);
$nickname = mysqli_real_escape_string($con, $_POST['nickname']);

// ตรวจสอบและบันทึกภาพ
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageType = $_FILES['image']['type'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp', 'image/svg+xml', 'image/tiff'];

    if (in_array($imageType, $allowedTypes)) {
        $imageNewName = uniqid($firstname . '_' . $surname) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $uploadDir = 'users/';
        $uploadPath = $uploadDir . $imageNewName;

        if (!move_uploaded_file($imageTmpName, $uploadPath)) {
            header("Location: addnewuser.php?error=upload_failed");
            exit;
        }
    } else {
        header("Location: addnewuser.php?error=invalid_file_type");
        exit;
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// เพิ่มข้อมูลลงฐานข้อมูล
$sql = "INSERT INTO nameTable (firstname, surname, nickname, image) VALUES ('$firstname', '$surname', '$nickname', '$uploadPath')";

if (mysqli_query($con, $sql)) {
    header("Location: addnewuser.php?success=user_added");
} else {
    header("Location: addnewuser.php?error=" . urlencode(mysqli_error($con)));
}
?>
