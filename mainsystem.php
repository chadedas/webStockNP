<?php
include('connection.php');
session_start();

// ถ้า user เข้าสู่ระบบแล้วและมีเซสชัน username
if (isset($_SESSION['username']) && isset($_SESSION['permission'])) {
    $username = $_SESSION['username'];
    $permission = $_SESSION['permission'];
    $result = mysqli_query($con, "SELECT * FROM Employee WHERE username = '$username'");
    $user = mysqli_fetch_assoc($result);
} else {
    header("Location: logout.php");
    exit;
}

// เริ่มต้นค่าการค้นหาเป็นค่าว่าง
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($con, $_GET['search']);  // ป้องกันการโจมตี SQL Injection
}
?>
<html lang="en" data-bs-theme="dark">

<head>



    <script src="/docs/5.3/assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Stock N.P. Robotics</title>
    <link rel="icon" type="image/icon" href="image/NPPP.ico">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link href="/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- mangifie -->
    <link rel="manifest" href="manifest.json">
    <meta name="apple-mobile-web-app-title" content="N.P. Robotics">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- Favicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="apple-touch-icon" href="image/NPPP-192x192.png">

    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.3/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap" rel="stylesheet">
    <meta name="theme-color" content="#712cf9">


    <style>
        body {
            font-family: 'Kanit', sans-serif;
            scroll-behavior: smooth;
        }

        a {
            text-decoration: none;
        }



        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            padding: 20px;
        }

        @media (min-width: 767.98px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }

        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }

        .grid-item:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            background-color: #555;
        }

        .grid-item img {
            transition: transform 0.3s ease;
            width: 100%;
            height: auto;
        }

        .grid-item:hover img {
            transform: scale(1);
        }

        .grid-item p {
            transition: color 0.3s ease;
            margin: 0;
        }

        .grid-item:hover p {
            color: #ffc107;
            /* เปลี่ยนสีข้อความ */
        }

        @media (max-width: 767.98px) {
            .grid-item {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-radius: 10px;
                overflow: hidden;
                /* ป้องกันส่วนเกิน */
            }

            .card-body {
                padding: 10px;
            }

        }
    </style>


</head>
<?php include 'navbar.php'; ?>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path
                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z">
            </path>
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z">
            </path>
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z">
            </path>
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path
                d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z">
            </path>
        </symbol>
    </svg>




    <main>

        <div class="album py-5 bg-body-tertiary">
            <div class="container">

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                    <div class="col text-center mx-auto">
                        <a href="importItem.php">
                            <div class="card shadow-sm grid-item">
                                <svg class="bd-placeholder-img card-img-top " width="100%" height="225"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <image href="image/in-stock.png" width="100%" height="100%" />
                                </svg>
                                <div class="card-body border-top border-secondary shadow bg-success bg-opacity-75">
                                    <p class="card-text fs-5 link-offset-2 link-underline link-underline-opacity-0">เอาของเข้า</p>
                                </div>
                            </div>
                        </a>
                    </div>


                    <div class="col text-center mx-auto">
                        <a href="exportItem.php">
                            <div class="card shadow-sm grid-item">
                                <svg class="bd-placeholder-img card-img-top " width="100%" height="225"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <image href="image/out-of-stock.png" width="100%" height="100%" />
                                </svg>
                                <div class="card-body border-top border-secondary shadow bg-danger bg-opacity-75">
                                    <p class="card-text fs-5 link-offset-2 link-underline link-underline-opacity-0">เอาของออก</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col text-center mx-auto">
                        <a href="addnewuser.php">
                            <div class="card shadow-sm grid-item">
                                <svg class=" bd-placeholder-img card-img-top " width="100%" height="225"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <image href="image/add-user.png" width="100%" height="100%" />
                                </svg>
                                <div class="card-body border-top border-secondary shadow bg-primary bg-opacity-75">
                                    <p class="card-text fs-5 link-offset-2 link-underline link-underline-opacity-0">เพิ่มผู้ใช้ใหม่</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col text-center mx-auto">
                        <a href="history.php">
                            <div class="card shadow-sm grid-item">
                                <svg class="bd-placeholder-img card-img-top " width="100%" height="225"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <image href="image/sheets.png" width="100%" height="100%" />
                                </svg>
                                <div class="card-body border-top border-secondary shadow bg-secondary bg-opacity-75">
                                    <p class="card-text fs-5 link-offset-2 link-underline link-underline-opacity-0">ประวัติการเอาเข้าออก</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col text-center mx-auto">
                        <a href="stock.php">
                            <div class="card shadow-sm grid-item">
                                <svg class="bd-placeholder-img card-img-top " width="100%" height="225"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <image href="image/stock.png" width="100%" height="100%" />
                                </svg>
                                <div class="card-body border-top border-secondary shadow bg-opacity-75">
                                    <p class="card-text fs-5 link-offset-2 link-underline link-underline-opacity-0">ดูของในสต็อก</p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <footer class="text-body-secondary py-5 text-center">
        <div class="container">
            <p class="mb-1">©2024 www.np-robotics.com. All rights reserved.</p>
        </div>
    </footer>


    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        document.querySelectorAll('[data-bs-theme-value]').forEach(item => {
            item.addEventListener('click', function() {
                const theme = this.getAttribute('data-bs-theme-value');
                document.documentElement.setAttribute('data-bs-theme', theme);

                document.querySelectorAll('[data-bs-theme-value]').forEach(btn => {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-pressed', 'false');
                });

                this.classList.add('active');
                this.setAttribute('aria-pressed', 'true');
            });
        });
    </script>
    <script>
        // เมื่อผู้ใช้พิมพ์ในช่องค้นหา
        document.getElementById('search').addEventListener('keyup', function() {
            var searchQuery = this.value.trim(); // เอาค่าที่พิมพ์ออกจากช่องค้นหามา
            fetchData(searchQuery);
        });

        // โหลดข้อมูลทั้งหมดเมื่อหน้าเว็บโหลด
        window.addEventListener('load', function() {
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