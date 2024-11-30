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
        <h3 class="register-heading">นำของจากสต็อก</h3>
        <form action="exportItem_backend.php" method="post" enctype="multipart/form-data">
          <div class="row register-form">
            <div class="col-md-6">
              <div class="form-group">
                <label for="firstname" class="form-label">โดยผู้ดูแลสต็อก</label>
                <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname'] . ' ' . $user['surname']); ?>" readonly required />
              </div>
              <div class="form-group">
                <label for="user" class="form-label">ผู้นำออก</label>
                <select name="user" id="user" class="form-control" required>
                  <option value="" disabled selected>เลือกชื่อผู้นำออก</option> <!-- ค่า default -->
                  <?php
                  // Query เพื่อดึงข้อมูล firstname และ surname
                  $nameQuery = mysqli_query($con, "SELECT firstname, surname, nickname FROM nameTable");

                  if (mysqli_num_rows($nameQuery) > 0) {
                    // Loop เพื่อสร้าง <option> สำหรับดรอปดาวน์
                    while ($row = mysqli_fetch_assoc($nameQuery)) {
                      $fullname = htmlspecialchars($row['firstname'] . ' ' . $row['surname'] . ' (' . $row['nickname'] . ')'); // รวมชื่อเต็มและป้องกัน XSS
                      echo "<option value='$fullname'>$fullname</option>"; // แสดงชื่อเต็มในดรอปดาวน์
                    }
                  } else {
                    echo "<option value=''>ไม่มีข้อมูลใน nameTable</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="item" class="form-label">ของที่จะนำออก</label>
                <select name="item" id="item" class="form-control" required onchange="updateQuantityOptions()">
                  <option value="" disabled selected>เลือกของที่จะนำออก</option> <!-- ค่า default -->
                  <?php
                  $itemQuery = mysqli_query($con, "SELECT id, ItemName, Amount FROM Stock_Main");

                  if (mysqli_num_rows($itemQuery) > 0) {
                    while ($item = mysqli_fetch_assoc($itemQuery)) {
                      $disabled = $item['Amount'] <= 0 ? "disabled" : ""; // ตรวจสอบว่าจำนวน <= 0 หรือไม่
                      $style = $item['Amount'] <= 0 ? "style='color: red;'" : ""; // เพิ่มสีแดงถ้า Amount <= 0
                      $amountDisplay = $item['Amount'] <= 0 ? "(หมด)" : "(" . $item['Amount'] . ")"; // แสดง "(หมด)" หากจำนวน <= 0

                      echo "<option value='" . $item['ItemName'] . "' $disabled $style>" . $item['ItemName'] . " $amountDisplay</option>";
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function updateQuantityOptions() {
      const itemSelect = document.getElementById('item');
      const quantitySelect = document.getElementById('quantity');
      const itemName = itemSelect.value;

      // Clear previous options
      quantitySelect.innerHTML = '<option value="" disabled selected>เลือกจำนวน</option>';

      if (itemName) {
        // ส่งคำขอเพื่อดึงข้อมูลจำนวนจากฐานข้อมูล
        fetch('getItemQuantity.php?item=' + itemName)
          .then(response => {
            console.log(response); // ตรวจสอบการตอบกลับจาก server
            return response.json();
          })
          .then(data => {
            console.log(data); // ตรวจสอบข้อมูลที่ได้รับ
            if (Array.isArray(data) && data.length > 0) {
              data.forEach(quantity => {
                const option = document.createElement('option');
                option.value = quantity;
                option.textContent = quantity;
                quantitySelect.appendChild(option);
              });
            } else {
              const option = document.createElement('option');
              option.value = "";
              option.textContent = "ไม่มีจำนวนในสต็อก";
              quantitySelect.appendChild(option);
            }
          })
          .catch(error => {
            console.error('Error fetching quantity:', error);
            const option = document.createElement('option');
            option.value = "";
            option.textContent = "เกิดข้อผิดพลาดในการดึงข้อมูล";
            quantitySelect.appendChild(option);
          });
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($success == 'item_removed'): ?>
        Swal.fire({
          title: 'นำของออกสต็อกสำเร็จ',
          text: 'คุณจะถูกเปลี่ยนเส้นทางไปยังระบบหลัก',
          icon: 'success',
          timer: 1000,
          timerProgressBar: true
        }).then(function() {
          window.location = 'mainsystem.php?username=' + encodeURIComponent('<?php echo htmlspecialchars($user['username']); ?>');
        });
      <?php endif; ?>

      <?php if (isset($_GET['error'])): ?>
        const errorMessage = "<?php echo $_GET['error']; ?>";
        Swal.fire({
          title: 'เกิดข้อผิดพลาด',
          text: errorMessage,
          icon: 'error'
        });
      <?php endif; ?>
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>