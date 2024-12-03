<?php
include('connection.php'); // เชื่อมต่อกับฐานข้อมูล
session_start(); // ใช้ session สำหรับตรวจสอบผู้ใช้ที่ล็อกอิน

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username']) && !isset($_SESSION['permission'])) {
    header("Location: index.php"); // หากไม่ได้ล็อกอิน ให้ไปหน้า login
    exit;
} else {
    // ดึงชื่อผู้ใช้จาก session
    $username = $_SESSION['username'];
    $permission = $_SESSION['permission'];
    if ($permission != 'admin') {
        header("Location: mainsystem.php");
        exit;
    }
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
if (empty($_POST['firstname']) || empty($_POST['category']) || empty($_POST['user']) || empty($_POST['item']) || empty($_POST['quantity']) || empty($_POST['date_added'])) {
    die("Missing required fields. Please check the input form.");
}

// รับค่าจากฟอร์ม
$firstname = mysqli_real_escape_string($con, $_POST['firstname']);
$user = mysqli_real_escape_string($con, $_POST['user']);
$category = mysqli_real_escape_string($con, $_POST['category']);
$itemID = mysqli_real_escape_string($con, $_POST['item_id']);
$item = mysqli_real_escape_string($con, $_POST['item_name']);
$quantity = intval($_POST['quantity']); // แปลงเป็นจำนวนเต็ม
$dateImport = mysqli_real_escape_string($con, $_POST['date_added']);

// ตรวจสอบจำนวนสินค้าที่มีในสต็อก
$query = "SELECT Amount FROM `$category` WHERE id = '$itemID'"; // ใช้ backticks สำหรับ table name
$result = mysqli_query($con, $query);

// เพิ่มการดีบัก Query และผลลัพธ์
if (!$result) {
    echo "DEBUG: Query Error - " . mysqli_error($con) . "<br>";
    echo "DEBUG: Query - $query<br>";
    exit;
}

// ตรวจสอบว่าพบสินค้าในสต็อกหรือไม่
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentAmount = $row['Amount'];

    $newAmount = $currentAmount - $quantity;

    // อัปเดตจำนวนในสต็อก
    $updateQuery = "UPDATE `$category` SET Amount = '$newAmount' WHERE id = '$itemID'";
    if (!mysqli_query($con, $updateQuery)) {
        echo "DEBUG: Update Error - " . mysqli_error($con) . "<br>";
        echo "DEBUG: Update Query - $updateQuery<br>";
        exit;
    }

    // บันทึกข้อมูลการนำออกใน Stock_Export
    $insertQuery = "INSERT INTO Stock_Export (username, user , ItemName, Amount, Date) 
                    VALUES ('$username', '$user' , '$item', '$quantity', '$dateImport')";
    if (!mysqli_query($con, $insertQuery)) {
        echo "DEBUG: Insert Error - " . mysqli_error($con) . "<br>";
        echo "DEBUG: Insert Query - $insertQuery<br>";
        exit;
    }

    // จัดการอัพโหลดรูปภาพ
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        // ตรวจสอบประเภทไฟล์
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif',
            'image/bmp', 'image/webp', 'image/svg+xml', 'image/tiff'
        ];
        if (in_array($imageType, $allowedTypes) && $imageSize <= 5 * 1024 * 1024) {
            $id = mysqli_insert_id($con);
            $imageNewName = $id . '_' . $username . '_' . $item . '_' . $quantity . '_' . $dateImport . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
            $uploadDir = 'historys/export/';
            $uploadPath = $uploadDir . $imageNewName;

            if (move_uploaded_file($imageTmpName, $uploadPath)) {
                $imagePath = $uploadPath;
                $updateImageQuery = "UPDATE Stock_Export SET Image = '$imagePath' WHERE id = '$id'";
                if (!mysqli_query($con, $updateImageQuery)) {
                    echo "DEBUG: Image Update Error - " . mysqli_error($con) . "<br>";
                    exit;
                }
            } else {
                echo "DEBUG: Image Upload Failed<br>";
                exit;
            }
        } else {
            echo "DEBUG: Invalid Image Type or Size<br>";
            exit;
        }
    }

    $ipAddress = $_SERVER['REMOTE_ADDR'];  // หรือจะใช้ method อื่นๆ สำหรับดึง IP

    // สร้างข้อความ log
    $action = "นำของออกสต็อก";
    $details = "ไอดี: $itemID, เมนู: $category เพิ่มสินค้า: $item, จำนวน: $quantity, ผู้นำเข้า: $user";
    $logQuery = "INSERT INTO admin_logs (action, username, details, ip_address,action_date) 
             VALUES ('$action', '$username', '$details', '$ipAddress', '$dateImport')";

    // บันทึกข้อมูลลงฐานข้อมูล
    if (!mysqli_query($con, $logQuery)) {
        die("Error logging action: " . mysqli_error($con));
    }

    // สำเร็จ
    echo "DEBUG: Item successfully added.<br>";
    echo "DEBUG: Redirecting to exportItem.php<br>";
    header("Location: exportItem.php?success=item_added");
    exit;
} else {
    echo "DEBUG: Item not found in category $category with id $itemID<br>";
    echo "DEBUG: Query - $query<br>";
    exit;
}
?>
