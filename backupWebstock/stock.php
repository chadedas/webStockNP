<?php
include('connection.php'); // เชื่อมต่อกับฐานข้อมูล
session_start(); // ใช้ session สำหรับตรวจสอบผู้ใช้ที่ล็อกอิน

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // หากไม่ได้ล็อกอิน ให้ไปหน้า login
    exit;
}
// รับค่าชื่อผู้ใช้จาก session
$username = $_SESSION['username'];

// ตรวจสอบคำค้นหาจากผู้ใช้
$search = isset($_POST['search']) ? $_POST['search'] : '';

// คำสั่ง SQL สำหรับดึงข้อมูลสินค้าจากฐานข้อมูล โดยจะกรองข้อมูลจากชื่อสินค้า
$query = "SELECT id, ItemName, Amount FROM Stock_Main WHERE ItemName LIKE '%$search%' ORDER BY id ASC";
$result = mysqli_query($con, $query);

// ตรวจสอบว่า query ประสบความสำเร็จหรือไม่
if (!$result) {
    echo "Error: " . mysqli_error($con); // แสดงข้อผิดพลาดจาก query
}

// ตรวจสอบจำนวนแถวที่ได้จากการ query
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูสินค้าคงคลังในสต็อก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media (max-width: 767px) {
            .table-responsive { border: 1px solid #ddd; }
            .table th, .table td { padding: 8px; }
        }
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
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="container mt-4">
        <h2 class="text-center">สินค้าคงคลังในสต็อก</h2>
        <a href="mainsystem.php?username=<?php echo urlencode($username); ?>" class="btn btn-primary mt-3">ย้อนกลับ</a>

        <!-- ฟอร์มค้นหา -->
        <form method="post" class="mt-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาชื่อสินค้า" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
            </div>
        </form>

        <?php
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered">';
            echo '<thead><tr><th>ID</th><th>ชื่อสินค้า</th><th>จำนวน</th><th>ดูรายละเอียด</th></tr></thead>';
            echo '<tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['ItemName'] . "</td>";
                echo "<td>" . $row['Amount'] . "</td>";
                echo "<td><button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#itemModal" . $row['id'] . "'>ดูรายละเอียด</button></td>";
                echo "</tr>";

                // Modal สำหรับแสดงรายละเอียดสินค้า
                echo "<div class='modal fade' id='itemModal" . $row['id'] . "' tabindex='-1' aria-labelledby='itemModalLabel' aria-hidden='true'>";
                echo "    <div class='modal-dialog'>";
                echo "        <div class='modal-content'>";
                echo "            <div class='modal-header'>";
                echo "                <h5 class='modal-title' id='itemModalLabel'>รายละเอียดสินค้า</h5>";
                echo "                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                echo "            </div>";
                echo "            <div class='modal-body'>";
                echo "                <p>ชื่อสินค้า: " . $row['ItemName'] . "</p>";
                echo "                <p>จำนวนที่เหลือ: " . $row['Amount'] . "</p>";
                echo "                <p>ข้อมูลเพิ่มเติมอื่น ๆ ที่คุณต้องการ</p>";
                echo "            </div>";
                echo "        </div>";
                echo "    </div>";
                echo "</div>";
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo "<p>ไม่พบสินค้าที่ตรงกับคำค้นหา</p>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
