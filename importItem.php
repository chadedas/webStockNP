<?php
include('connection.php');
session_start();
// ถ้า user เข้าสู่ระบบแล้วและมีเซสชัน username
if (isset($_SESSION['username']) && isset($_SESSION['permission'])) {
  // ตรวจสอบว่า permission เป็น 'user' หรือ 'admin'
  $username = $_SESSION['username'];
  $permission = $_SESSION['permission'];

  if ($permission != 'admin') {
    header("Location: mainsystem.php");
    exit;
  }
  $result = mysqli_query($con, "SELECT * FROM Employee WHERE username = '$username'");
  $user = mysqli_fetch_assoc($result);
  if (!$user) {
    die("User not found.");
  }
} else {
  header("Location: logout.php");
  exit;
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
<?php include 'navbar.php'; ?>
<body class="bg-light">



  <div class="container register">
    <div class="row">
      <div class="col-md-3 register-left">
        <img src="image/NPPP.png" alt="" />
        <h3>Add Item to stock</h3>
        <p>สำหรับแสดงข้อมูลผู้ใช้โปรแกรมถอดประกอบ KR150</p>
      </div>
      <div class="col-md-9 register-right">
        <h3 class="register-heading">เอาของเข้า</h3>
        <form action="importItem_backend.php" method="post" enctype="multipart/form-data">
          <div class="row register-form">
            <div class="col-md-6">
              <div class="form-group">
                <label for="firstname" class="form-label">โดยผู้ดูแลสต็อก</label>
                <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo htmlspecialchars($user['firstname'] . ' ' . $user['surname']); ?>" readonly required />
              </div>
              <div class="form-group">
                <label for="user" class="form-label">ผู้นำเข้า</label>
                <select name="user" id="user" class="form-control" required>
                  <option value="" disabled selected>เลือกชื่อผู้นำเข้า</option> <!-- ค่า default -->
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
                <label for="category" class="form-label">เลือกหมวดหมู่</label>
                <select name="category" id="category" class="form-control" required>
                  <option value="" disabled selected>เลือกหมวดหมู่</option>
                  <option value="Stock_Main">ห้องประชุม</option>
                  <option value="Stock_Main2">ห้องสแปร์ (บนชั้น)</option>
                  <option value="Stock_Main2_inroom">ห้องสแปร์ (นอกชั้น)</option>
                  <option value="Stock_Main2_Study">ชุดสำหรับอบรม</option>
                  <option value="Stock_Main4_VR">ชุดวีอา VR</option>
                </select>

              </div>
              <div class="form-group">
                <label for="item" class="form-label">เลือกของที่จะนำเข้า</label>
                <select name="item" id="item" class="form-control" required>
                  <option value="" disabled selected>เลือกของที่จะนำเข้า</option>
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
              <input type="hidden" name="item_id" id="item_id" value="">
              <input type="hidden" name="item_name" id="item_name" value="">
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
                <a href="mainsystem.php" class="btn btn-secondary w-100">ย้อนกลับ</a>
              </div>
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
      const categorySelect = document.getElementById('category');
      const itemSelect = document.getElementById('item');

      categorySelect.addEventListener('change', function () {
    const selectedCategory = categorySelect.value;

    if (selectedCategory) {
        fetch(`getItemQuantity.php?category=${selectedCategory}`)
            .then(response => response.json())
            .then(data => {
                itemSelect.innerHTML = `<option value="" disabled selected>เลือกของที่จะนำเข้า</option>`;

                if (data.error) {
                    console.error(data.error);
                    alert('เกิดข้อผิดพลาด: ' + data.error);
                } else {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        // ตรวจสอบว่า key มีอยู่ใน object ก่อน
                        const id = item.id || 'ID';
                        const name = item.ItemName;

                        option.value = id; // กำหนดให้ value เป็น id
                        if(selectedCategory === "Stock_Main2_Study"){
                          const list = item.list;
                          option.textContent = `${id} - ${name} - ${list}`; // แสดงข้อมูล id และชื่อ
                        }else{
                          option.textContent = `${id} - ${name}`; // แสดงข้อมูล id และชื่อ
                        }
                        
                        itemSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching items:', error);
                alert('ไม่สามารถดึงข้อมูลสินค้าได้');
            });
    }
});

      itemSelect.addEventListener('change', function() {
        const selectedItem = itemSelect.selectedOptions[0];
        const selectedCategory = categorySelect.value;
        let categoryName = '';
        switch (selectedCategory) {

          case 'Stock_Main':
            categoryName = 'ห้องประชุม';
            break;
          case 'Stock_Main2':
            categoryName = 'ห้องสแปร์ (บนชั้น)';
            break;
          case 'Stock_Main2_inroom':
            categoryName = 'ห้องสแปร์ (ในห้อง)';
            break;
          case 'Stock_Main2_Study':
            categoryName = 'ห้องสแปร์ (ในห้อง)';
            break;
          case 'Stock_Main4_VR':
            categoryName = 'ห้องประชุม';
            break;
            // เพิ่มกรณีอื่นๆ ที่ต้องการ
          default:
            categoryName = 'ไม่ทราบ';
        }

        if (selectedItem) {
          const itemId = selectedItem.value;
          if (selectedCategory === "Stock_Main2_Study"){
            const itemName = selectedItem.textContent.split(' - ')[1];
          }else{
            const itemName = selectedItem.textContent.split(' - ')[1];
          }
          

          // Make an AJAX call to get the item's details
          fetch(`getItemDetails.php?id=${itemId}&category=${selectedCategory}`) // ใช้ & แทน ? สำหรับพารามิเตอร์ตัวที่สอง
    .then(response => response.json())
    .then(data => {
        if (!data.error) {
            Swal.fire({
                title: `รายละเอียดของ ${data.ItemName}`,
                html: `
                    <table class="table">
                        <tr><th>เก็บในห้อง</th><td>${categoryName}</td></tr>
                        <tr><th>ไปเอาได้ที่</th><td>${data.whereItem || '${categoryName}'}</td></tr>
                        <tr><th>ชื่อของ</th><td>${data.ItemName}</td></tr>
                    </table>
                `,
                showClass: {
                    popup: `animate__animated animate__fadeInUp animate__faster`
                },
                hideClass: {
                    popup: `animate__animated animate__fadeOutDown animate__faster`
                }
            });
        } else {
            Swal.fire('ข้อมูลไม่พบ', data.error, 'error');
        }
    })
    .catch(error => console.error('Error fetching item details:', error));
        }
      });
      
      document.getElementById('item').addEventListener('change', function () {
    const selectedOption = this.selectedOptions[0];
    const itemId = selectedOption ? selectedOption.value : ''; // ตรวจสอบว่ามีค่า
    const itemName = selectedOption ? selectedOption.text.split(' - ').pop() : ''; // ตัดเอาค่าอันสุดท้ายหลังเครื่องหมาย ' - '

    document.getElementById('item_id').value = itemId; // อัปเดต item_id
    document.getElementById('item_name').value = itemName; // อัปเดต item_name
});
    });
    <?php if (isset($_GET['success']) && $_GET['success'] == 'item_added'): ?>
    Swal.fire({
      title: 'นำของเข้าสต็อกสำเร็จ',
      text: 'คุณจะถูกเปลี่ยนเส้นทางไปยังระบบหลัก',
      icon: 'success',
      timer: 1000,
      timerProgressBar: true
    }).then(function() {
      window.location = 'mainsystem.php';
    });
  <?php elseif (isset($_GET['error']) && $_GET['error'] == 'true'): ?>
    Swal.fire({
      title: 'ดำเนินการไม่สำเร็จ',
      text: 'เกิดข้อผิดพลาดบางประการ',
      icon: 'error',
      timer: 1000,
      timerProgressBar: true
    }).then(function() {
      // ไม่ต้องทำอะไรเพิ่มเติม หรือเปลี่ยนหน้า
    });
  <?php endif; ?>
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>

</html>