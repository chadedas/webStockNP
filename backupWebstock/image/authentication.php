<?php
include('connection.php');  
session_start();

$username = $_POST['user'];  
$password = $_POST['pass'];  
   
// ป้องกัน SQL Injection
$username = stripcslashes($username);  
$password = stripcslashes($password);  
$username = mysqli_real_escape_string($con, $username);  
$password = mysqli_real_escape_string($con, $password);  
 
// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT * FROM Employee WHERE username = '$username'";  
$result = mysqli_query($con, $sql);  
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
 
if ($row && password_verify($password, $row['password'])) {
    // ตรวจสอบว่าผู้ใช้เป็น admin หรือไม่
    if ($row['permission'] == 'admin') {
        session_regenerate_id(true); // เปลี่ยน ID ของเซสชั่นเพื่อป้องกันการโจมตี
        $_SESSION['username'] = $username;
        $_SESSION['permission'] = $row['permission']; // เก็บสิทธิ์ของผู้ใช้ในเซสชั่น

        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'เข้าสู่ระบบสำเร็จ',
                    text: 'คุณจะถูกเปลี่ยนเส้นทางไปยังระบบหลัก',
                    icon: 'success',
                    timer: 1000,
                    timerProgressBar: true
                }).then(function() {
                    window.location = 'mainsystem.php'; // เปลี่ยนเส้นทางไปยังหน้าเพจหลัก
                });
            });
        </script>";
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถเข้าสู่ระบบได้',
                    text: 'คุณไม่มีสิทธิ์ในการเข้าถึงระบบนี้',
                }).then(function() {
                    window.location = 'index.php'; // เปลี่ยนเส้นทางกลับไปยังหน้า login
                });
            });
        </script>";
    }
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถเข้าสู่ระบบได้',
                text: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
            }).then(function() {
                window.location = 'index.php'; // เปลี่ยนเส้นทางกลับไปยังหน้า login
            });
        });
    </script>";
}
?>
