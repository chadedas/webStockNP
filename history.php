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

// ตรวจสอบว่ามีการเลือกหมวดหมู่หรือไม่ และตั้งค่า default เป็น 'overview'
$category = isset($_POST['category']) ? $_POST['category'] : 'overview';

// ดึงข้อมูลตามหมวดหมู่
$query = "";
if ($category == "import") {
    $query = "SELECT 'import' AS type, si.id, si.username, si.user, si.ItemName, si.Amount, si.Date AS date, e.firstname, e.surname, si.Image 
              FROM Stock_Import si 
              JOIN Employee e ON si.username = e.username 
              ORDER BY si.Date DESC";
} elseif ($category == "export") {
    $query = "SELECT 'export' AS type, se.id, se.username, se.user, se.ItemName, se.Amount, se.Date AS date, e.firstname, e.surname, se.Image 
              FROM Stock_Export se 
              JOIN Employee e ON se.username = e.username 
              ORDER BY se.Date DESC";
} elseif ($category == "overview") {
    $query = "(SELECT 'import' AS type, si.id, si.username, si.user, si.ItemName, si.Amount, si.Date AS date, e.firstname, e.surname, si.Image 
               FROM Stock_Import si 
               JOIN Employee e ON si.username = e.username)
              UNION ALL
              (SELECT 'export' AS type, se.id, se.username, se.user, se.ItemName, se.Amount, se.Date AS date, e.firstname, e.surname, se.Image 
               FROM Stock_Export se 
               JOIN Employee e ON se.username = e.username)
              ORDER BY date DESC";
}

// ดึงข้อมูลจากฐานข้อมูล
$result = mysqli_query($con, $query);
if (!$result) {
    echo "Error: " . mysqli_error($con);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการนำเข้า/ออกสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-import { background-color: #d4edda; } /* สีเขียวอ่อนสำหรับ Stock_Import */
        .table-export { background-color: #f8d7da; } /* สีแดงอ่อนสำหรับ Stock_Export */
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('your-image.jpg'); /* ใส่เส้นทางของภาพที่ต้องการใช้ */
            background-size: cover;
            background-position: center;
            z-index: -1;
            opacity: 0.5;
        }
        .btn-success {
            background-color: #28a745; /* สีเขียวเข้ม */
            border-color: #1e7e34; /* สีเขียวเข้มกว่า */
        }

        .btn-danger {
            background-color: #dc3545; /* สีแดงเข้ม */
            border-color: #c82333; /* สีแดงเข้มกว่า */
        }
    </style>
</head>
<body>
    <div class="background-image"></div> <!-- แสดงรูปพื้นหลัง -->

    <div class="container mt-4">
        <h2 class="text-center">ประวัติการนำเข้า/ออกสินค้า</h2>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="category" class="form-label">เลือกหมวดหมู่</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="overview" <?php echo ($category == 'overview') ? 'selected' : ''; ?>>ภาพรวมประวัติ</option>
                    <option value="import" <?php echo ($category == 'import') ? 'selected' : ''; ?>>นำเข้า</option>
                    <option value="export" <?php echo ($category == 'export') ? 'selected' : ''; ?>>นำออก</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">ดูประวัติ</button>
            <a href="mainsystem.php?username=<?php echo urlencode($username); ?>" class="btn btn-primary mt-3">ย้อนกลับ</a>
        </form>

        <?php
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered">';
            echo '<thead><tr><th>ชื่อพนักงาน</th><th>ชื่อสินค้า</th><th>จำนวน</th><th>วันที่</th><th>หมวดหมู่</th><th>ดูรูป</th></tr></thead>';
            echo '<tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                $datetime = date("d-m-Y H:i", strtotime($row['date']));
                $imagePath = $row['Image'];

                // กำหนดสีพื้นหลังตามหมวดหมู่
                $bgColor = ($row['type'] == 'import') ? 'style="background-color: #d4edda;"' : 'style="background-color: #f8d7da;"';

                echo "<tr $bgColor>";
                echo "<td>" . $row['user'] . "</td>";
                echo "<td>" . $row['ItemName'] . "</td>";
                echo "<td>" . $row['Amount'] . "</td>";
                echo "<td>" . $datetime . "</td>";
                echo "<td>" . ($row['type'] == 'import' ? 'นำเข้า' : 'นำออก') . "</td>";

                $buttonClass = ($row['type'] == 'import') ? 'btn-success' : 'btn-danger'; 
                echo "<td><button type='button' class='btn $buttonClass btn-sm' data-bs-toggle='modal' data-bs-target='#imageModal" . $row['id'] . "'>ดูรูป</button></td>";
                echo "</tr>";

                // Modal สำหรับแสดงรูปภาพ
                echo "<div class='modal fade' id='imageModal" . $row['id'] . "' tabindex='-1' aria-labelledby='imageModalLabel' aria-hidden='true' data-bs-backdrop='static'>";
                echo "    <div class='modal-dialog'>";
                echo "        <div class='modal-content'>";
                echo "            <div class='modal-header'>";
                echo "                <h5 class='modal-title' id='imageModalLabel'>รูปภาพ</h5>";
                echo "                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                echo "            </div>";
                echo "            <div class='modal-body'>";
                echo "                <img src='" . $imagePath . "' class='img-fluid' alt='Item Image'>";
                echo "            </div>";
                echo "        </div>";
                echo "    </div>";
                echo "</div>";
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo "<p>ไม่พบข้อมูลในหมวดหมู่นี้</p>";
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
