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

// ตรวจสอบว่ามีการส่งค่าหมวดหมู่มาหรือไม่
$category = isset($_POST['category']) ? $_POST['category'] : '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการนำเข้า/ออกสินค้า</title>
    <!-- Bootstrap CSS สำหรับความยืดหยุ่นของ layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom style for responsive table */
        @media (max-width: 767px) {
            .table-responsive { border: 1px solid #ddd; }
            .table th, .table td { padding: 8px; }
        }

        /* Custom style for the background image */
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('your-image.jpg'); /* ใส่เส้นทางของภาพที่ต้องการใช้ */
            background-size: cover;
            background-position: center;
            z-index: -1; /* ทำให้เป็นพื้นหลัง */
            opacity: 0.5;
        }
        /* กำหนดขนาดของ modal เพื่อให้เหมาะสมกับมือถือ */
/* เพิ่ม z-index เพื่อให้ modal อยู่ด้านบนสุด */
.modal {
    z-index: 1051 !important;
}

/* ทำให้ modal ใช้พื้นที่เต็มจอในโทรศัพท์ */
.modal-dialog {
    max-width: 95%;
    margin: 30px auto;
}

.modal-backdrop {
    opacity: 0.05 !important;  /* ลดความเข้มของ backdrop ให้เบาลง */
}
    </style>
</head>
<body>
    <div class="background-image"></div> <!-- แสดงรูปด้านหลังสุด -->

    <div class="container mt-4">
        <h2 class="text-center">ประวัติการนำเข้า/ออกสินค้า</h2>
        <form method="POST" class="mb-4">
            <div class="form-group">
                <label for="category" class="form-label">เลือกหมวดหมู่</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="" disabled <?php echo ($category == '') ? 'selected' : ''; ?>>เลือกหมวดหมู่</option>
                    <option value="import" <?php echo ($category == 'import') ? 'selected' : ''; ?>>นำเข้า</option>
                    <option value="export" <?php echo ($category == 'export') ? 'selected' : ''; ?>>นำออก</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">ดูประวัติ</button>
            <a href="mainsystem.php?username=<?php echo urlencode($username); ?>" class="btn btn-primary mt-3">ย้อนกลับ</a>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $category != '') {
            // เลือก query ตามหมวดหมู่ที่ผู้ใช้เลือก
            $query = "";

            if ($category == "import") {
                // Query สำหรับดูประวัติการนำเข้าจาก Stock_Import และ Employee
                $query = "SELECT si.id, si.username, si.ItemName, si.Amount, si.DateImport, e.firstname, e.surname, si.Image 
                          FROM Stock_Import si 
                          JOIN Employee e ON si.username = e.username 
                          ORDER BY si.DateImport DESC";
            } elseif ($category == "export") {
                // Query สำหรับดูประวัติการนำออกจาก Stock_Export และ Employee
                $query = "SELECT se.id, se.username, se.ItemName, se.Amount, se.DateExport, e.firstname, e.surname, se.Image 
                          FROM Stock_Export se 
                          JOIN Employee e ON se.username = e.username 
                          ORDER BY se.DateExport DESC";
            }

            // ดึงข้อมูลจากฐานข้อมูล
            $result = mysqli_query($con, $query);

            // ตรวจสอบว่า query ประสบความสำเร็จหรือไม่
            if (!$result) {
                echo "Error: " . mysqli_error($con); // แสดงข้อผิดพลาดจาก query
            }

            // ตรวจสอบจำนวนแถวที่ได้จากการ query
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>ID</th><th>ผู้ใช้งาน</th><th>ชื่อสินค้า</th><th>จำนวน</th><th>วันที่</th><th>ดูรูป</th></tr></thead>';
                echo '<tbody>';

                // แสดงข้อมูลในตาราง
                while ($row = mysqli_fetch_assoc($result)) {
                    // เลือกวันที่จากหมวดหมู่ที่เลือก
                    $date = ($category == "import") ? $row['DateImport'] : $row['DateExport'];
                    // แปลงวันที่ให้แสดงทั้งวันที่และเวลา (ชั่วโมง นาที)
                    $datetime = date("d-m-Y H:i", strtotime($date));

                    // ใช้เส้นทางรูปภาพจากฐานข้อมูล
                    $imagePath = $row['Image']; // แทนที่ด้วยเส้นทางของรูปที่เก็บในฐานข้อมูล

                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['firstname'] . " " . $row['surname'] . "</td>";
                    echo "<td>" . $row['ItemName'] . "</td>";
                    echo "<td>" . $row['Amount'] . "</td>";
                    echo "<td>" . $datetime . "</td>";
                    echo "<td><button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#imageModal" . $row['id'] . "'>ดูรูป</button></td>";
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
        }
        ?>

    </div>

    <script>
    // ให้ backdrop สามารถปิด modal ได้
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function () {
            var backdrop = document.querySelector('.modal-backdrop');
            backdrop.style.opacity = '0.7';  // ลดความเข้มของ backdrop
        });
    });

    // ปิด modal โดยการคลิกที่ backdrop
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-backdrop')) {
            var modal = document.querySelector('.modal.show');
            if (modal) {
                var modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
            }
        }
    });
</script>

    <!-- Bootstrap JS และ Popper สำหรับการทำงานของฟอร์มและ UI -->
    <!-- เพิ่ม Bootstrap และ Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
