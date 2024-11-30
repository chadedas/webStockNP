<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict',
]);

session_start();

// ตรวจสอบว่าผู้ใช้ไม่ได้ล็อกอินหรือไม่
if (!isset($_SESSION['username']) || !isset($_SESSION['permission'])) {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่ากิจกรรมล่าสุดนานเกิน 30 นาทีหรือไม่
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();    // ลบข้อมูลเซสชัน
    session_destroy();  // ทำลายเซสชัน
    header("Location: index.php");  // เปลี่ยนเส้นทางไปยังหน้า login
    exit();
}

// อัปเดตเวลาล่าสุดของกิจกรรมในเซสชัน
$_SESSION['LAST_ACTIVITY'] = time();

// สร้าง ID เซสชันใหม่เพื่อป้องกันการ hijacking
session_regenerate_id(true);
?>
