<?php
include('connection.php');
session_start();
// ถ้า user เข้าสู่ระบบแล้วและมีเซสชัน username
if (isset($_SESSION['username']) && $_SESSION['permission']) {
  // ตรวจสอบว่า permission เป็น 'user' หรือ 'admin'
  if ($_SESSION['permission'] != 'admin') {
    header("Location: mainsystem.php");  // ส่งไปหน้า edituser_user.php
    exit;
  }else{
    $username = $_SESSION['username'];
    $permission = $_SESSION['permission'];
    $result = mysqli_query($con, "SELECT * FROM Employee WHERE username = '$username'");
    $user = mysqli_fetch_assoc($result);
  }
}
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add User Information</title>
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
<?php include 'navbar.php'; ?>
<body class="bg-light">

  <div class="container register">
    <div class="row">
      <div class="col-md-3 register-left">
        <img src="image/NPPP.png" alt="" />
        <h3>User Management</h3>
        <p class="">สำหรับเพิ่มข้อมูลผู้ใช้โปรแกรมถอดประกอบ KR150</p>
      </div>
      <div class="col-md-9 register-right">
        <h3 class="register-heading">เพิ่มข้อมูลผู้ใช้โปรแกรมใหม่</h3>
        <form action="update_new_user.php" method="post" enctype="multipart/form-data">
          <div class="row register-form">
            <div class="col-md-6">
              <div class="form-group">
                <label for="firstname" class="form-label">ชื่อจริง (ภาษาไทย)</label>
                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="ชื่อจริง *" required />
              </div>
              <div class="form-group">
                <label for="surname" class="form-label">นามสกุล (ภาษาไทย)</label>
                <input type="text" name="surname" id="surname" class="form-control" placeholder="นามสกุล *" required />
              </div>
              <div class="form-group">
                <label for="nickname" class="form-label">ชื่อเล่น (ภาษาไทย)</label>
                <input type="text" name="nickname" id="nickname" class="form-control" placeholder="ชื่อเล่น *" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="image" class="form-label">รูปภาพ</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required />
              </div>
              <input type="submit" class="btn btn-primary btnRegister" value="ยืนยัน" />
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
      // ป้องกันการ submit แบบปกติ
      event.preventDefault();

      // แสดงการโหลดกลางหน้าจอ
      Swal.fire({
        title: 'กำลังดำเนินการ...',
        text: 'กรุณารอสักครู่',
        allowOutsideClick: false, // ป้องกันการคลิกนอกหน้าต่าง
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // ใช้ setTimeout เพื่อจำลองการส่งข้อมูล
      // เปลี่ยนเป็นการส่งข้อมูลจริงได้ในขั้นตอนต่อไป
      setTimeout(() => {
        // ส่งข้อมูลจริงไปยัง backend
        form.submit(); // ส่งฟอร์มไปยัง backend
      }, 1000); // ใส่เวลารอ 2 วินาทีเพื่อให้เห็นข้อความ loading
    });
      <?php if ($success == 'user_added'): ?>
        Swal.fire({
          title: 'เพิ่มข้อมูลสำเร็จ',
          text: 'คุณจะถูกเปลี่ยนเส้นทางไปยังระบบหลัก',
          icon: 'success',
          timer: 1000,
          timerProgressBar: true
        }).then(function() {
          window.location = 'mainsystem.php';
        });
      <?php elseif ($error == 'invalid_username'): ?>
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'ชื่อผู้ใช้ต้องเป็นภาษาอังกฤษและมีความยาวอย่างน้อย 6 ตัว',
        }).then(function() {
          window.location = 'addnewuser.php';
        });
      <?php elseif ($error == 'username_exists'): ?>
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว กรุณาเลือกชื่อใหม่',
        }).then(function() {
          window.location = 'addnewuser.php';
        });
      <?php elseif ($error == 'passwords_do_not_match'): ?>
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'รหัสผ่านไม่ตรงกัน',
        }).then(function() {
          window.location = 'addnewuser.php';
        });
      <?php endif; ?>
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
