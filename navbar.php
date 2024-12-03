<head>
<link rel="manifest" href="manifest.json">
    <meta name="apple-mobile-web-app-title" content="N.P. Robotics">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="apple-touch-icon" href="image/NPPP-192x192.png">
    <link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
    <link rel="icon" type="image/icon" href="image/NPPP.ico">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">
</head>
<style>
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
    .navbar {
        transition: background-color 0.3s ease;
    }
    .sticky-top {
        z-index: 1030;
    }
</style>
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a href="mainsystem.php" class="navbar-brand d-flex align-items-center">
                <img src="image/NPPP.png" alt="N.P. Robotics Logo" width="40" height="40" class="me-2">
                <strong>Stock N.P. Robotics</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="importItem.php">เอาของเข้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="exportItem.php">เอาของออก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="addnewuser.php">เพิ่มผู้ใช้ใหม่</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">ประวัติ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="stock.php">สต็อก</a>
                    </li>
                </ul>
                <span class="navbar-text me-3 text-white">
                    <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['surname']); ?>
                </span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>
</header>
