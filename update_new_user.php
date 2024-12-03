<?php
include('connection.php');
session_start();
if (isset($_SESSION['username']) && $_SESSION['permission']) {
    // ตรวจสอบว่า permission เป็น 'user' หรือ 'admin'
    if ($_SESSION['permission'] != 'admin') {
      header("Location: mainsystem.php");  // ส่งไปหน้า edituser_user.php
      exit;
    }
}
$username = $_SESSION['username'];
$permission = $_SESSION['permission'];
// ตั้งค่าเขตเวลา
date_default_timezone_set('Asia/Bangkok');
// ดึงเวลาปัจจุบัน
$date = date('Y-m-d H:i:s');

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

    $ipAddress = $_SERVER['REMOTE_ADDR'];  // หรือจะใช้ method อื่นๆ สำหรับดึง IP

    // สร้างข้อความ log
    $action = "เพิ่มผู้ใช้ใหม่";
    $details = "ผู้เพิ่ม: $username, เพิ่มชื่อ: $firstname $surname ($nickname)";
    $logQuery = "INSERT INTO admin_logs (action, username, details, ip_address, action_date) 
             VALUES ('$action', '$username', '$details', '$ipAddress', '$date')";

    // บันทึกข้อมูลลงฐานข้อมูล
    if (!mysqli_query($con, $logQuery)) {
        die("Error logging action: " . mysqli_error($con));
    }


    header("Location: addnewuser.php?success=user_added");
} else {
    header("Location: addnewuser.php?error=" . urlencode(mysqli_error($con)));
}
?>
