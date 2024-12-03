<?php
session_start();
// ถ้า user เข้าสู่ระบบแล้วและมีเซสชัน username
if (isset($_SESSION['username']) && isset($_SESSION['permission'])) {
  header("Location: mainsystem.php");
  exit;
  // ตรวจสอบว่า permission เป็น 'user' หรือ 'admin'
}

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="manifest" href="manifest.json">
    <meta name="apple-mobile-web-app-title" content="N.P. Robotics">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="apple-touch-icon" href="image/NPPP-192x192.png">
    <link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
    <link rel="icon" type="image/icon" href="image/NPPP.ico">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    html,
    body {
      font-family: 'Kanit', sans-serif;
      scroll-behavior: smooth;
    }
        /* Scrollbar */
::-webkit-scrollbar {
    width: 13px; /* ความกว้างของ Scrollbar */
    height: 10px; /* ความสูงของ Scrollbar (สำหรับ Scroll แนวนอน) */
}

/* Track */
::-webkit-scrollbar-track {
    background: #f0f0f0; /* สีพื้นหลังของ Track */
    border-radius: 10px; /* มุมโค้ง */
}

/* Thumb */
::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #6a11cb, #2575fc); /* ไล่สี */
    border-radius: 10px; /* มุมโค้ง */
}

/* Hover effect */
::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #4e0db1, #1d4ed8); /* สีเมื่อชี้เมาส์ */
}

/* Active effect */
::-webkit-scrollbar-thumb:active {
    background: linear-gradient(45deg, #3a0a8e, #163ead); /* สีเมื่อคลิก Scrollbar */
}

/* Optional: ซ่อน Scrollbar บนมือถือ */
@media (max-width: 768px) {
    ::-webkit-scrollbar {
        display: none; /* ซ่อน Scrollbar */
    }
  }

    .bg-opacity-90 {
      opacity: 0.9 !important;
    }

    .shadow_nav {
      box-shadow: 0px 10px 15px rgb(0 0 0 / 7%);
      z-index: 10000;
    }

    .bg_top {
      background-color: #3784f5;
    }

    .btn:first-child:hover,
    :not(.btn-check)+.btn:hover {
      background: #3784f5;
      color: white !important;
      border-radius: 5px !important;
    }

    .btn1:first-child:hover,
    :not(.btn1-check)+.btn1:hover {
      background: #ff0000;
      color: white !important;
      border-radius: 5px !important;
    }

    .btn2:first-child:hover,
    :not(.btn2-check)+.btn2:hover {
      background: #198754;
      color: white !important;
      border-radius: 5px !important;
    }

    .py-6 {
      padding-top: 6rem !important;
      padding-bottom: 3rem !important;
    }
    
  </style>
</head>

<body>
  <div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
      <div class="text-center">
        <img src="image/NPPP.png" height="50" width="50" class="rounded mb-2" alt="...">
      </div>
      <h4 class="text-center mb-3">เข้าสู่ระบบ</h4>

      <form name="f1" action="authentication.php" onsubmit="return validation()" method="POST">
        <div class="form-outline mb-4">
          <label class="form-label" for="username">ชื่อผู้ใช้</label>
          <input type="text" id="user" name="user" class="form-control" required />
        </div>
        <div class="form-outline mb-4">
          <label class="form-label" for="password">รหัสผ่าน</label>
          <input type="password" id="pass" name="pass" class="form-control" required />
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      <p class="text-center mt-5 "><a href="https://www.np-robotics.com/"
          class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover "
          target="_blank">บริษัท.
          เอ็น.พี. โรโบติกส์ แอนด์ โซลูชั่น จำกัด</a></p>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function validation() {
      var id = document.f1.user.value;
      var ps = document.f1.pass.value;
      if (id.length == "" && ps.length == "") {
        alert("User Name and Password fields are empty");
        return false;
      }
      else {
        if (id.length == "") {
          alert("User Name is empty");
          return false;
        }
        if (ps.length == "") {
          alert("Password field is empty");
          return false;
        }
      }
    }  
  </script>

</body>

</html>
