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
        <title>Responsive Web Template</title>
    </head>
<style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Global Styles */
body {
    font-family: 'Kanit', sans-serif;
    scroll-behavior: smooth;
    line-height: 1.6;
    color: #333;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

/* Header */
.header {
    background: #333;
    color: #fff;
    padding: 1rem 0;
}

.header .logo {
    font-size: 1.5rem;
}

.nav {
    margin-top: 0.5rem;
}

.nav-list {
    display: flex;
    list-style: none;
    justify-content: center;
    gap: 1.5rem;
}

.nav-list a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
}

.nav-list a:hover {
    text-decoration: underline;
}

/* Hero Section */
.hero {
    text-align: center;
    padding: 3rem 1rem;
    background: #f4f4f4;
}

.hero h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.hero p {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
}

.cta-button {
    background: #333;
    color: #fff;
    padding: 0.5rem 1rem;
    border: none;
    cursor: pointer;
}

.cta-button:hover {
    background: #555;
}

/* Features Section */
.features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    padding: 2rem 1rem;
}

.feature {
    background: #fff;
    border: 1px solid #ddd;
    padding: 1rem;
    text-align: center;
}

.feature h3 {
    margin-bottom: 0.5rem;
}

/* Footer */
.footer {
    text-align: center;
    padding: 1rem;
    background: #333;
    color: #fff;
    margin-top: 2rem;
}
.btn-custom-rounded {
    border-radius: 15px; /* ขอบโค้ง 15px */
}

.btn-custom-pill {
    border-radius: 50px; /* ขอบโค้งแบบวงรี */
}
.feature {
    background: #6c757d; /* สีพื้นหลัง */
    border-radius: 15px; /* ขอบโค้งมน */
    padding: 1rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เงาเล็กน้อย */
}


/* Responsive Design */
@media (max-width: 768px) {
    .nav-list {
        flex-direction: column;
        gap: 0.5rem;
    }

    .hero h2 {
        font-size: 1.5rem;
    }

    .hero p {
        font-size: 1rem;
    }
}
</style>

<body>
  
    <header class="header">
    <nav class="navbar sticky-bottom bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">เว็ปจัดการ Stock</a>
    <p class="text-primary"><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['surname']); ?>!</p>
  </div>
</nav>
    </header>

    <main class="main-content">
        <center>
            <div class=" p-3 text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-3">
                เดี๋ยวมาตกแต่งเพิ่มเติมครับ
              </div>
        </center>
       
        <section class="features">
            <div class="feature bg-secondary">
                

                <div class="card p-3 mb-2 bg-dark text-white">
                    <div class="card-body ">
                      <h5 class="card-title fw-bold">นำของเข้าสต็อก</h5>
                      <p class="card-text"></p>
                      <button type="button" class="btn btn-success btn-lg d-grid gap-2 col-6 mx-auto fw-bold btn-custom-rounded">Click</button>
                    </div>
                  </div>
            </div>
            <div class="feature bg-secondary">
                <div class="card p-3 mb-2 bg-dark text-white">
                    <div class="card-body">
                      <h5 class="card-title fw-bold">นำของออกสต็อก</h5>
                      <p class="card-text"></p>
                      <button type="button" class="btn btn-danger btn-lg d-grid gap-2 col-6 mx-auto fw-bold btn-custom-pill">Click</button>
                    </div>
                  </div>
            </div>
            </div>
            <div class="feature bg-secondary">
                <div class="card p-3 mb-2 bg-dark text-white">
                    <div class="card-body">
                      <h5 class="card-title fw-bold">ตรวจสอบของในสต็อก</h5>
                      <p class="card-text"></p>
                      <button type="button" class="btn btn-info btn-lg d-grid gap-2 col-6 mx-auto fw-bold">Click</button>
                    </div>
                  </div>
            </div>
        </section>
    </main>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="CSS/style_edituser.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">  
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
    // เมื่อผู้ใช้พิมพ์ในช่องค้นหา
    document.getElementById('search').addEventListener('keyup', function () {
  var searchQuery = this.value.trim(); // เอาค่าที่พิมพ์ออกจากช่องค้นหามา
  if (searchQuery.length > 0) {
    // ถ้ามีข้อความในช่องค้นหา ให้ทำการค้นหา
    fetch('search.php?search=' + searchQuery)
      .then(response => response.text()) // รับข้อมูลเป็นข้อความ
      .then(data => {
        document.getElementById('table-body').innerHTML = data;
      });
  } else {
    // ถ้าช่องค้นหาว่างเปล่า ให้โหลดข้อมูลทั้งหมด
    fetch('search.php?search=')
      .then(response => response.text()) // รับข้อมูลทั้งหมด
      .then(data => {
        document.getElementById('table-body').innerHTML = data;
      });
  }
});

// โหลดข้อมูลทั้งหมดเมื่อหน้าเว็บโหลด
window.addEventListener('load', function () {
  var searchQuery = document.getElementById('search').value.trim();
  if (searchQuery.length === 0) {
    // ถ้าไม่มีกำหนดคำค้นหา ให้โหลดข้อมูลทั้งหมด
    fetch('search.php?search=')
      .then(response => response.text())
      .then(data => {
        document.getElementById('table-body').innerHTML = data;
      });
  }
});

  </script>
</body>

</html>
