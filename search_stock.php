<?php
include('connection.php');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$search = isset($_POST['search']) ? $_POST['search'] : '';
$table = isset($_POST['table']) ? $_POST['table'] : '';

if ($table) {
    if ($table === 'all') {
        // สำหรับ "ภาพรวม" ดึงข้อมูลจากทุกตาราง
        $tables = [
            'Stock_Main' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                            CASE 
                                WHEN status IS NULL THEN 'สถานะไม่ระบุ' 
                                WHEN status = 'active' THEN '✅' 
                                WHEN status = 'not_active' THEN '❌' 
                                ELSE status 
                            END AS สถานะ, 
                            whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, ProductName AS ชื่อ, Amount AS จำนวนคงเหลือ, 
                            status AS สถานะ, whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main2 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Controller' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, NumDrive AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                         whereItem AS เก็บไว้ที่, status AS สถานะ 
                                         FROM Stock_Main2_Controller WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_inroom' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                                     whereItem AS เก็บไว้ที่, status AS สถานะ, note AS หมายเหตุ 
                                     FROM Stock_Main2_inroom WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_KPS' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, status AS สถานะ, whereItem AS เก็บไว้ที่ 
                                  FROM Stock_Main2_KPS WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Service' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, NumberItem AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                     company AS บริษัท, user AS ผู้นำออก, date AS วันที่ 
                                     FROM Stock_Main2_Service WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Study' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, list AS รายการ, Amount AS จำนวนคงเหลือ, 
                                    status AS สถานะ, note AS หมายเหตุ, package AS ชุดที่ 
                                    FROM Stock_Main2_Study WHERE ItemName LIKE '%$search%'",
            'Stock_Main3_Ppon' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, SerialNumber AS ซีเรียลนัมเบอร์, 
                                   whereItem AS เก็บไว้ที่, date AS วันที่, status AS สถานะ, note AS หมายเหตุ 
                                   FROM Stock_Main3_Ppon WHERE ItemName LIKE '%$search%'",
            'Stock_Main4_VR' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, status AS สถานะ, 
                                 note AS หมายเหตุ, date AS วันที่ FROM Stock_Main4_VR WHERE ItemName LIKE '%$search%'"
        ];

        foreach ($tables as $table_name => $query) {
            echo "<h4>ข้อมูลจากตาราง: $table_name</h4>";
            $result = mysqli_query($con, $query);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-bordered">';
                    echo '<thead><tr>';
                    $field_info = mysqli_fetch_fields($result);
                    foreach ($field_info as $val) {
                        echo "<th>" . $val->name . "</th>";
                    }
                    echo '</tr></thead>';
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        foreach ($row as $column) {
                            echo "<td>" . $column . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo "<p>ไม่พบข้อมูลที่ตรงกับคำค้นหาในตาราง $table_name</p>";
                }
            } else {
                echo "Error: " . mysqli_error($con);
            }
        }
    } else {
        // ถ้าคุณเลือกตารางเฉพาะ
        $queries = [
            'Stock_Main' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                            CASE 
                                WHEN status IS NULL THEN 'สถานะไม่ระบุ' 
                                WHEN status = 'active' THEN '✅' 
                                WHEN status = 'not_active' THEN '❌' 
                                ELSE status 
                            END AS สถานะ, 
                            whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, ProductName AS ชื่อ, Amount AS จำนวนคงเหลือ, 
                            status AS สถานะ, whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main2 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Controller' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, NumDrive AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                         whereItem AS เก็บไว้ที่, status AS สถานะ 
                                         FROM Stock_Main2_Controller WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_inroom' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                                     whereItem AS เก็บไว้ที่, status AS สถานะ, note AS หมายเหตุ 
                                     FROM Stock_Main2_inroom WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_KPS' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, status AS สถานะ, whereItem AS เก็บไว้ที่ 
                                  FROM Stock_Main2_KPS WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Service' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, NumberItem AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                     company AS บริษัท, user AS ผู้นำออก, date AS วันที่ 
                                     FROM Stock_Main2_Service WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Study' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, list AS รายการ, Amount AS จำนวนคงเหลือ, 
                                    status AS สถานะ, note AS หมายเหตุ, package AS ชุดที่ 
                                    FROM Stock_Main2_Study WHERE ItemName LIKE '%$search%'",
            'Stock_Main3_Ppon' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, SerialNumber AS ซีเรียลนัมเบอร์, 
                                   whereItem AS เก็บไว้ที่, date AS วันที่, status AS สถานะ, note AS หมายเหตุ 
                                   FROM Stock_Main3_Ppon WHERE ItemName LIKE '%$search%'",
            'Stock_Main4_VR' => "SELECT id AS ลำดับ, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, status AS สถานะ, 
                                 note AS หมายเหตุ, date AS วันที่ FROM Stock_Main4_VR WHERE ItemName LIKE '%$search%'"
        ];

        $query = $queries[$table];
        $result = mysqli_query($con, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<h4>ข้อมูลจากตาราง: $table</h4>";
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered">';
                echo '<thead><tr>';
                $field_info = mysqli_fetch_fields($result);
                foreach ($field_info as $val) {
                    echo "<th>" . $val->name . "</th>";
                }
                echo '</tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    foreach ($row as $column) {
                        echo "<td>" . $column . "</td>";
                    }
                    echo "</tr>";
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo "<p>ไม่พบข้อมูลที่ตรงกับคำค้นหาในตาราง $table</p>";
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
} else {
    echo "<p>กรุณาเลือกตารางที่ต้องการค้นหา</p>";
}
?>