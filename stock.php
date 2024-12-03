<?php
include('connection.php'); // เชื่อมต่อกับฐานข้อมูล
session_start(); // ใช้ session สำหรับตรวจสอบผู้ใช้ที่ล็อกอิน

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username']) && !isset($_SESSION['permission'])) {
    header("Location: index.php"); // หากไม่ได้ล็อกอิน ให้ไปหน้า login
    exit;
}

// รับค่าชื่อผู้ใช้จาก session
$username = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูสินค้าคงคลังในสต็อก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('your-image.jpg');
            background-size: cover;
            background-position: center;
            z-index: -1;
            opacity: 0.5;
        }
        .category {
    position: relative;
    top: 0; /* กำหนดให้หมวดหมู่เริ่มต้นที่ตำแหน่งนี้ */
}
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="container mt-4">
        <h2 class="text-center">สินค้าคงคลังในสต็อก</h2>
        <a href="mainsystem.php" class="btn btn-primary mt-3">ย้อนกลับ</a>

        <!-- ฟอร์มค้นหา -->
        <form id="searchForm" class="mt-3" method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="search" id="search" placeholder="ค้นหาชื่อสินค้า" autocomplete="off">
            </div>
            
            <!-- Dropdown สำหรับเลือกหมวดหมู่ -->
            <div class="mt-3">
                <label for="category" class="form-label">เลือกหมวดหมู่</label>
                <select name="table" id="category" class="form-select">
                    <option value="all" selected>ภาพรวม</option>
                    <option value="Stock_Main">ห้องประชุม</option>
                    <option value="Stock_Main2">ห้อง สแปร์ (บนชั้น)</option>
                    <option value="Stock_Main2_Controller">ห้อง สแปร์ ตู้คอลโทรล</option>
                    <option value="Stock_Main2_inroom">ห้อง สแปร์ (นอกชั้น)</option>
                    <option value="Stock_Main2_KPS">ห้องสแปร์ (ไดร์ฟ)</option>
                    <option value="Stock_Main2_Service">อุปกรณ์ออกเซอร์วิส</option>
                    <option value="Stock_Main2_Study">ชุดสำหรับอบรม</option>
                    <option value="Stock_Main3_Ppon">ชุดโรบอทพี่พล</option>
                    <option value="Stock_Main4_VR">ชุดวีอา VR</option>
                </select>
            </div>
        </form>

        <!-- แสดงตารางที่ค้นหา -->
        <div id="stockTable"></div>
    </div>

    <script>
        $(document).ready(function() {
    // ฟังก์ชันที่ใช้ดึงข้อมูลทั้งหมด
    function loadStockData(table = 'all', search = '') {
        $.ajax({
            url: "search_stock.php", // ไฟล์ PHP ที่จะประมวลผลการค้นหา
            method: "POST",
            data: {
                search: search, // ส่งคำค้นหาที่ผู้ใช้กรอก
                table: table // ส่งชื่อของตารางที่ผู้ใช้เลือก
            },
            success: function(response) {
                $("#stockTable").html(response); // แสดงผลลัพธ์ใน div ที่มี id="stockTable"
            }
        });
    }

    // เมื่อเลือกหมวดหมู่หรือพิมพ์ในช่องค้นหา
    $("#search, #category").on("change input", function() {
        var search = $("#search").val();
        var category = $("#category").val();
        loadStockData(category, search); // เรียกใช้ฟังก์ชันเพื่อดึงข้อมูล
    });

    // โหลดข้อมูลตามค่าเริ่มต้นเมื่อโหลดหน้า
    loadStockData('all', ''); // ค่าเริ่มต้นคือ "all"
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
