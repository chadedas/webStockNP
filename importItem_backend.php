<?php
include('connection.php'); // เชื่อมต่อกับฐานข้อมูล
session_start(); // ใช้ session สำหรับตรวจสอบผู้ใช้ที่ล็อกอิน

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // หากไม่ได้ล็อกอิน ให้ไปหน้า login
    exit;
}

// เปิดการแสดงข้อผิดพลาด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ตรวจสอบว่าฟอร์มกรอกข้อมูลครบถ้วนหรือไม่
if (empty($_POST['firstname']) || empty($_POST['user']) || empty($_POST['item']) || empty($_POST['quantity']) || empty($_POST['date_added'])) {
    die("Missing required fields. Please check the input form.");
}

// ดึงชื่อผู้ใช้จาก session
$username = $_SESSION['username'];

// รับค่าจากฟอร์ม
$firstname = mysqli_real_escape_string($con, $_POST['firstname']);
$user = mysqli_real_escape_string($con, $_POST['user']);
$item = mysqli_real_escape_string($con, $_POST['item']);
$quantity = intval($_POST['quantity']); // แปลงเป็นจำนวนเต็ม
$dateImport = mysqli_real_escape_string($con, $_POST['date_added']);

// ตรวจสอบจำนวนสินค้าที่มีในสต็อก
$query = "SELECT Amount FROM Stock_Main WHERE ItemName = '$item'";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Error in SELECT query: " . mysqli_error($con));
}

// ตรวจสอบว่าพบสินค้าในสต็อกหรือไม่
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentAmount = $row['Amount'];

        $newAmount = $currentAmount + $quantity;

        // อัปเดตจำนวนในสต็อก
        $updateQuery = "UPDATE Stock_Main SET Amount = '$newAmount' WHERE ItemName = '$item'";
        if (!mysqli_query($con, $updateQuery)) {
            die("Error in UPDATE query: " . mysqli_error($con));
        }

        // บันทึกข้อมูลการนำออกใน Stock_Import
        $insertQuery = "INSERT INTO Stock_Import (username, user , ItemName, Amount, Date) 
                        VALUES ('$username', '$user' , '$item', '$quantity', '$dateImport')";
        if (!mysqli_query($con, $insertQuery)) {
            die("Error in INSERT query: " . mysqli_error($con));
        }

        // จัดการอัพโหลดรูปภาพ
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageName = $_FILES['image']['name'];
            $imageTmpName = $_FILES['image']['tmp_name'];
            $imageSize = $_FILES['image']['size'];
            $imageType = $_FILES['image']['type'];

            // ตรวจสอบประเภทไฟล์
            $allowedTypes = [
                'image/jpeg', 'image/png', 'image/gif', // รูปแบบทั่วไป
                'image/bmp', 'image/webp',             // เพิ่มรูปแบบ BMP และ WebP
                'image/svg+xml',                       // SVG
                'image/tiff'                           // TIFF
            ];
            if (in_array($imageType, $allowedTypes) && $imageSize <= 5 * 1024 * 1024) {
                $id = mysqli_insert_id($con); // ดึง ID ที่เพิ่งเพิ่มล่าสุด
                $imageNewName = $id . '_' . $username . '_' . $item . '_' . $quantity . '_' . $dateImport . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
                $uploadDir = 'historys/import/';
                $uploadPath = $uploadDir . $imageNewName;

                if (move_uploaded_file($imageTmpName, $uploadPath)) {
                    $imagePath = $uploadPath;

                    // อัปเดตรูปภาพในฐานข้อมูล
                    $updateImageQuery = "UPDATE Stock_Import SET Image = '$imagePath' WHERE id = '$id'";
                    if (!mysqli_query($con, $updateImageQuery)) {
                        header("Location: importItem.php?username=" . urlencode($username) . "&error=update_image_failed");
                        exit;
                    }
                } else {
                    header("Location: importItem.php?username=" . urlencode($username) . "&error=upload_failed");
                    exit;
                }
            }
        }

        // สำเร็จ
        header("Location: importItem.php?username=" . urlencode($username) . "&success=item_added");
        exit;
} else {
    // ถ้าไม่พบสินค้าในฐานข้อมูล
    header("Location: importItem.php?username=" . urlencode($username) . "&error=item_not_found");
    exit;
}
?>
