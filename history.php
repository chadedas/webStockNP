<?php
include('connection.php');
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['permission'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$result = mysqli_query($con, "SELECT * FROM Employee WHERE username = '$username'");
$user = mysqli_fetch_assoc($result);

$category = isset($_POST['category']) ? $_POST['category'] : 'overview';

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.9/dist/sweetalert2.all.min.js"></script>
    <style>
        .table-import {
            background-color: #d4edda;
        }

        .table-export {
            background-color: #f8d7da;
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

<?php include 'navbar.php'; ?>

<body>
    <div class="background-image"></div>

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

                $bgColor = ($row['type'] == 'import') ? 'style="background-color: #d4edda;"' : 'style="background-color: #f8d7da;"';

                echo "<tr $bgColor>";
                echo "<td>" . $row['user'] . "</td>";
                echo "<td>" . $row['ItemName'] . "</td>";
                echo "<td>" . $row['Amount'] . "</td>";
                echo "<td>" . $datetime . "</td>";
                echo "<td>" . ($row['type'] == 'import' ? 'นำเข้า' : 'นำออก') . "</td>";

                $buttonClass = ($row['type'] == 'import') ? 'btn-success' : 'btn-danger';
                echo "<td><button type='button' class='btn $buttonClass btn-sm' onclick='showImage(\"$imagePath\")'>ดูรูป</button></td>";
                echo "</tr>";
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo "<p>ไม่พบข้อมูลในหมวดหมู่นี้</p>";
        }
        ?>

    </div>

    <script>
        function showImage(imageUrl) {
            Swal.fire({
                imageUrl: imageUrl,
            imageAlt: "รูปภาพ",
            imageWidth: 'auto',
            imageHeight: 'auto', 
            maxWidth: '90vw', // กำหนดให้ขนาดสูงสุดไม่เกิน 90% ของหน้าจอ
            maxHeight: '80vh' // กำหนดให้ขนาดสูงสุดไม่เกิน 80% ของความสูงหน้าจอ
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
