<?php
include('connection.php');
session_start();

// ถ้า user เข้าสู่ระบบแล้วและมีเซสชัน username
if (isset($_SESSION['username'])) {
  // ตรวจสอบว่า permission เป็น 'user' หรือ 'admin'
  //if ($_SESSION['permission'] == 'user') {
  //  header("Location: mainsystem.php?username=" . $_SESSION['username']);  // ส่งไปหน้า edituser_user.php
  //  exit;
  //}
}

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

// เริ่มต้นค่าการค้นหาเป็นค่าว่าง
$search = '';
if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($con, $_GET['search']);  // ป้องกันการโจมตี SQL Injection
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stock N.P Robotics</title>
  <link rel="stylesheet" href="styles.css">
</head>
<style>
/* General Reset */
/* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Arial', sans-serif;
  background-color: #2b2e3d;
  color: #fff;
}

.app-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

/* Header */
.header {
  text-align: center;
  padding: 10px 0;
  background-color: #1f2233;
}

.header h1 {
  font-size: 1.5rem;
  margin-bottom: 5px;
}

.header p {
  font-size: 0.9rem;
  color: #ccc;
}

/* Main Grid */
.grid-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
  padding: 20px;
  flex: 1;
  background-color: #3b3f51;
}

.grid-item {
  display: flex; /* ใช้ Flexbox */
  flex-direction: column; /* จัดเรียงเนื้อหาในแนวตั้ง */
  align-items: center; /* จัดให้อยู่กลางในแนวนอน */
  justify-content: center; /* จัดให้อยู่กลางในแนวตั้ง */
  text-align: center; /* จัดข้อความให้อยู่กลาง */
  height: 200px; /* กำหนดความสูงของกล่อง (ปรับได้ตามต้องการ) */
  background-color: #333; /* สีพื้นหลัง (ปรับได้) */
  border-radius: 8px; /* ทำมุมขอบมน */
  padding: 10px; /* เพิ่มระยะห่างภายใน */
  transition: background-color 0.3s ease; /* เอฟเฟกต์เมื่อ hover */
}

.grid-item img {
  width: 40px;
  height: 40px;
  margin-bottom: 10px;
}

.grid-item p {
  margin: 0; /* ลบระยะห่างด้านนอก */
  flex-shrink: 0; /* ป้องกันไม่ให้ย่อเล็ก */
  align-self: center; /* จัดข้อความให้อยู่กึ่งกลางใน Flexbox */
}

.grid-item:hover {
  background-color: #555; /* สีพื้นหลังเมื่อ hover */
}

/* Footer */
.footer {
  display: flex;
  justify-content: space-around;
  align-items: center;
  background-color: #1f2233;
  padding: 10px 0;
}

.footer-icon {
  flex: 1;
  text-align: center;
}

.footer-icon img {
  width: 25px;
  height: 25px;
  filter: grayscale(100%);
  transition: filter 0.3s ease;
}

.footer-icon.active img,
.footer-icon:hover img {
  filter: grayscale(0%);
}

.hide-item {
  opacity: 0; /* ทำให้โปร่งใสจนไม่เห็น */
  pointer-events: none; /* ป้องกันไม่ให้คลิกได้ */
}

/* Responsive Design */
@media (max-width: 768px) {
  .nav-list {
    flex-direction: column;
    gap: 0.5rem;
  }
}
</style>

<body>
  <div class="app-container">
    <!-- Header -->
    <header class="header">
      <h1 class="text-white">Stock N.P. Robotics</h1>
      <p><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['surname']); ?></p>
      <a href="logout.php">
    <button type="button" class="btn btn-sm btn-danger shadow-sm">Logout</button>
  </a>
    </header>

    <!-- Main Grid -->
    
    <main class="grid-container">
    <a class="grid-item link-offset-2 link-underline link-underline-opacity-0 text-white" href="importItem.php?username=<?php echo htmlspecialchars($user['username']); ?>">
  <img src="image/NPPP.png" alt="Dashboard" class="img-fluid">
  <p class="fs-3 text-center">เอาของเข้า</p>
    </a>
    <a class="grid-item link-offset-2 link-underline link-underline-opacity-0 text-white" href="exportItem.php?username=<?php echo htmlspecialchars($user['username']); ?>">
  <img src="image/NPPP.png" alt="Dashboard" class="img-fluid">
  <p class="fs-3 text-center">เอาของออก</p>
</a>
<a class="grid-item link-offset-2 link-underline link-underline-opacity-0 text-white" href="history.php">
  <img src="image/NPPP.png" alt="Dashboard" class="img-fluid">
  <p class="fs-3 text-center">ประวัติการเอาเข้าออก</p>
</a>
<a class="grid-item link-offset-2 link-underline link-underline-opacity-0 text-white" href="stock.php">
  <img src="image/NPPP.png" alt="Dashboard" class="img-fluid">
  <p class="fs-3 text-center">ดูของในสต็อก</p>
</a>
<a class="grid-item link-offset-2 link-underline link-underline-opacity-0 text-white hide-item" href="dashbord.php">
  <img src="image/NPPP.png" alt="Dashboard" class="img-fluid">
  <p class="fs-3 text-center">ซ่อน</p>
</a>
<a class="grid-item link-offset-2 link-underline link-underline-opacity-0 text-white hide-item" href="dashbord.php">
  <img src="image/NPPP.png" alt="Dashboard" class="img-fluid">
  <p class="fs-3 text-center">ซ่อน</p>
</a>

    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-icon active">
        <!--<img src="icons/home.png" alt="Home"> -->
      </div>
      <div class="footer-icon">
       <!-- <img src="icons/menu.png" alt="Menu"> -->
      </div>
      <div class="footer-icon">
        <!--<img src="icons/settings.png" alt="Settings"> -->
      </div>
    </footer>
  </div>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="CSS/style_edituser.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
  // เมื่อผู้ใช้พิมพ์ในช่องค้นหา
  document.getElementById('search').addEventListener('keyup', function () {
    var searchQuery = this.value.trim(); // เอาค่าที่พิมพ์ออกจากช่องค้นหามา
    fetchData(searchQuery);
  });

  // โหลดข้อมูลทั้งหมดเมื่อหน้าเว็บโหลด
  window.addEventListener('load', function () {
    var searchQuery = document.getElementById('search').value.trim();
    fetchData(searchQuery);
  });

  // ฟังก์ชัน fetch ข้อมูล
  function fetchData(query) {
    fetch('search.php?search=' + encodeURIComponent(query))
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text(); // รับข้อมูลเป็นข้อความ
      })
      .then(data => {
        document.getElementById('table-body').innerHTML = data;
        attachClickEvent(); // เพิ่ม Event Listener ให้ div ใหม่
      })
      .catch(error => {
        console.error('Error fetching data:', error);
        document.getElementById('table-body').innerHTML =
          '<tr><td colspan="100%">ไม่สามารถโหลดข้อมูลได้</td></tr>';
      });
  }

  // เพิ่ม Event Listener ให้กับ div ทุกตัวที่มี class clickable-item
  function attachClickEvent() {
    document.querySelectorAll('.clickable-item').forEach(item => {
      item.removeEventListener('click', handleClick); // ลบ Event เดิมก่อน (ถ้ามี)
      item.addEventListener('click', handleClick);
    });
  }

  function handleClick() {
    const targetHref = this.getAttribute('data-href');
    if (targetHref) {
      window.location.href = targetHref; // เปลี่ยนหน้าไปยังลิงก์ที่กำหนด
    }
  }
</script>
</body>

</html>