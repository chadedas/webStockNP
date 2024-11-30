<?php
include('connection.php');
session_start();
// ถ้า user เข้าสู่ระบบแล้วและมีเซสชัน username
if (isset($_SESSION['username'])) {
  // ตรวจสอบว่า permission เป็น 'user' หรือ 'admin'
}

// รับ username จาก URL
if (isset($_GET['username'])) {
  $username = mysqli_real_escape_string($con, $_GET['username']);

  // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
  $result = mysqli_query($con, "SELECT * FROM Employee WHERE username = '$username'");
  $user = mysqli_fetch_assoc($result);

  if (!$user) {
    die("User not found.");
  }
} else {
  die("Username is not specified.");
}
$itemQuery = mysqli_query($con, "SELECT id, ItemName FROM Stock_Main");

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Item to Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="CSS/style_edituser.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      scroll-behavior: smooth;
    }

    .form-label {
      font-size: 0.9em;
      color: #6c757d;
    }

    .btnRegister {
      margin-top: 10px;
      width: 100%;
    }
  </style>
</head>

<body class="bg-light">

  <div class="container register">
    <div class="row">
      <div class="col-md-3 register-left">
        <img src="image/NPPP.png" alt="" />
        <h3>Add Item to stock</h3>
        <p>สำหรับแสดงข้อมูลผู้ใช้โปรแกรมถอดประกอบ KR150</p>
      </div>
      <div class="col-md-9 register-right">
        <h3 class="register-heading">กรอกฟอร์ม</h3>
        <form action="importItem_backend.php" method="post" enctype="multipart/form-data">
          <div class="row register-form">
            <div class="col-md-6">
              <div class="form-group">
                <label for="firstname" class="form-label">ผู้นำของเข้า (ชื่อจริง)</label>
                <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname']); ?>" readonly required />
              </div>
              <div class="form-group">
                <label for="lastname" class="form-label">ผู้นำของเข้า (นามสกุล)</label>
                <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo htmlspecialchars($user['surname']); ?>" readonly required />
              </div>
              <div class="form-group">
                <label for="item" class="form-label">ของที่นำเข้า</label>
                <select name="item" id="item" class="form-control" required>
                  <option value="" disabled selected>เลือกของที่จะนำเข้า</option> <!-- ค่า default -->
                  <?php
                  $itemQuery = mysqli_query($con, "SELECT ItemName FROM Stock_Main");

                  if (mysqli_num_rows($itemQuery) > 0) {
                    while ($item = mysqli_fetch_assoc($itemQuery)) {
                      echo "<option value='" . $item['ItemName'] . "'>" . $item['ItemName'] . "</option>";
                    }
                  } else {
                    echo "<option value=''>ไม่มีของในสต็อก</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="form-group">
                <label for="quantity" class="form-label">จำนวนกี่ชิ้น</label>
                <select name="quantity" id="quantity" class="form-control" required>
                  <option value="" disabled selected>เลือกจำนวน</option> <!-- ค่า default -->
                  <?php
                  for ($i = 1; $i <= 100; $i++) {
                    echo "<option value='$i'>$i</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="image" class="form-label">แนบรูปภาพประกอบ</label>
                <input type="file" name="image" id="image" class="form-control" required />
              </div>
              <div class="form-group">
                <label for="date_added" class="form-label">วันที่เพิ่ม (เวลาปัจจุบัน)</label>
                <input type="text" name="date_added" id="date_added" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly required />
              </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3"> <!-- ปุ่มยืนยันอยู่ในคอลัมน์เดียวกับปุ่มย้อนกลับ -->
                    <input type="submit" class="btn btn-success btnRegister w-100" value="ยืนยัน" />
                </div>
                <div class="col-md-12"> <!-- ปุ่มย้อนกลับอยู่ใต้ปุ่มยืนยัน -->
                    <a href="mainsystem.php?username=<?php echo urlencode($user['username']); ?>" class="btn btn-secondary w-100">ย้อนกลับ</a>
                </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function redirectToChangePassword() {
      const username = '<?php echo htmlspecialchars($user['username']); ?>';
      window.location.href = `editpassword_user.php?username=${username}`; // Corrected by adding backticks
    }

    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($success == 'item_added'): ?>
        Swal.fire({
          title: 'นำของเข้าสต็อกสำเร็จ',
          text: 'คุณจะถูกเปลี่ยนเส้นทางไปยังระบบหลัก',
          icon: 'success',
          timer: 1000,
          timerProgressBar: true
        }).then(function() {
          window.location = 'mainsystem.php?username=' + encodeURIComponent('<?php echo htmlspecialchars($user['username']); ?>');
        });
      <?php endif; ?>
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>
