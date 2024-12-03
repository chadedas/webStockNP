<?php
include('connection.php');
session_start();

if (!isset($_SESSION['username']) && !isset($_SESSION['permission'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$permission = $_SESSION['permission'];
$search = isset($_POST['search']) ? $_POST['search'] : '';
$table = isset($_POST['table']) ? $_POST['table'] : '';

if ($table) {
    if ($table === 'all') {
        // สำหรับ "ภาพรวม" ดึงข้อมูลจากทุกตาราง
        $tables = [
            'Stock_Main' => "SELECT id, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                            CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, 
                            whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2' => "SELECT id, ItemName AS ชื่ออุปกรณ์, ProductName AS ชื่อ, Amount AS จำนวนคงเหลือ, 
                            CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main2 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Controller' => "SELECT id, ItemName AS ชื่ออุปกรณ์, NumDrive AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                         whereItem AS เก็บไว้ที่, status AS สถานะ 
                                         FROM Stock_Main2_Controller WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_inroom' => "SELECT id, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                                     whereItem AS เก็บไว้ที่, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, note AS หมายเหตุ 
                                     FROM Stock_Main2_inroom WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_KPS' => "SELECT id, ItemName AS ชื่ออุปกรณ์, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, whereItem AS เก็บไว้ที่ 
                                  FROM Stock_Main2_KPS WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Service' => "SELECT id, ItemName AS ชื่ออุปกรณ์, NumberItem AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                     company AS บริษัท, user AS ผู้นำออก, date AS วันที่ 
                                     FROM Stock_Main2_Service WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Study' => "SELECT id, ItemName AS ชื่ออุปกรณ์, list AS รายการ, Amount AS จำนวนคงเหลือ, 
                                    CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, note AS หมายเหตุ, package AS ชุดที่ 
                                    FROM Stock_Main2_Study WHERE ItemName LIKE '%$search%'",
            'Stock_Main3_Ppon' => "SELECT id, ItemName AS ชื่ออุปกรณ์, SerialNumber AS ซีเรียลนัมเบอร์, 
                                   whereItem AS เก็บไว้ที่, date AS วันที่, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, note AS หมายเหตุ 
                                   FROM Stock_Main3_Ppon WHERE ItemName LIKE '%$search%'",
            'Stock_Main4_VR' => "SELECT id, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, 
                                 note AS หมายเหตุ, date AS วันที่ FROM Stock_Main4_VR WHERE ItemName LIKE '%$search%'"
        ];

        foreach ($tables as $table_name => $query) {
            // แปลงชื่อ table_name เป็นข้อความภาษาไทย
            $table_display_name = "";
            switch ($table_name) {
                case 'Stock_Main':
                    $table_display_name = "ห้องประชุม";
                    break;
                case 'Stock_Main2':
                    $table_display_name = "ห้องสแปร์ (บนชั้น)";
                    break;
                case 'Stock_Main2_Controller':
                    $table_display_name = "ห้องสแปร์ (ตู้คอนโทรล)";
                    break;
                case 'Stock_Main2_inroom':
                    $table_display_name = "ห้องสแปร์ (ในห้อง)";
                    break;
                case 'Stock_Main2_KPS':
                    $table_display_name = "ห้องสแปร์ (ไดร์ฟ)";
                    break;
                case 'Stock_Main2_Service':
                    $table_display_name = "ของเซอร์วิส";
                    break;
                case 'Stock_Main2_Study':
                    $table_display_name = "ชุดอบรม";
                    break;
                case 'Stock_Main3_Ppon':
                    $table_display_name = "ชุดโรบอทพี่พล";
                    break;
                case 'Stock_Main4_VR':
                    $table_display_name = "ชุด VR";
                    break;
                default:
                    $table_display_name = "ไม่ทราบแหล่งข้อมูล";
            }

            // แสดงข้อความตามชื่อที่แปลงแล้ว
            echo "<h4>ข้อมูลจากตาราง: $table_display_name</h4>";

            $result = mysqli_query($con, $query);
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-bordered">';
                    echo '<thead><tr>';

                    // ดึงชื่อคอลัมน์ทั้งหมด
                    $field_info = mysqli_fetch_fields($result);
                    foreach ($field_info as $val) {
                        echo "<th>" . $val->name . "</th>";
                    }
                    echo "<th>Edit</th>"; // เพิ่มคอลัมน์สำหรับปุ่มแก้ไข
                    echo '</tr></thead>';
                    echo '<tbody>';

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        foreach ($row as $key => $column) {
                            echo "<td>" . htmlspecialchars($column) . "</td>";
                        }

                        // ตรวจสอบว่ากำลังแสดงข้อมูลจาก "ภาพรวม" หรือหมวดหมู่เฉพาะ
                        $current_table = ($table === 'all') ? $table_name : $table;

                        // เพิ่มปุ่มแก้ไขที่ลิงก์ไปยัง editItem.php
                        echo '<td>';
                        if (isset($row['id'])) { // ตรวจสอบว่ามีคอลัมน์ id
                            echo '<a href="editItem.php?id=' . urlencode($row['id']) . '&table=' . urlencode($current_table) . '" class="btn btn-warning btn-sm">แก้ไข</a>';
                        } else {
                            echo 'ไม่มี ID';
                        }
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo "<p>ไม่พบข้อมูลที่ตรงกับคำค้นหาในตาราง $table_display_name</p>";
                }
            } else {
                echo "Error: " . mysqli_error($con);
            }
        }
    } else {
        // ถ้าคุณเลือกตารางเฉพาะ
        $queries = [
            'Stock_Main' => "SELECT id, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                            CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, 
                            whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2' => "SELECT id, ItemName AS ชื่ออุปกรณ์, ProductName AS ชื่อ, Amount AS จำนวนคงเหลือ, 
                            CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, whereItem AS เก็บไว้ที่ 
                            FROM Stock_Main2 
                            WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Controller' => "SELECT id, ItemName AS ชื่ออุปกรณ์, NumDrive AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                         whereItem AS เก็บไว้ที่, status AS สถานะ 
                                         FROM Stock_Main2_Controller WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_inroom' => "SELECT id, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, 
                                     whereItem AS เก็บไว้ที่, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, note AS หมายเหตุ 
                                     FROM Stock_Main2_inroom WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_KPS' => "SELECT id, ItemName AS ชื่ออุปกรณ์, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, whereItem AS เก็บไว้ที่ 
                                  FROM Stock_Main2_KPS WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Service' => "SELECT id, ItemName AS ชื่ออุปกรณ์, NumberItem AS เลขอุปกรณ์, NumNP AS เลขบริษัท, 
                                     company AS บริษัท, user AS ผู้นำออก, date AS วันที่ 
                                     FROM Stock_Main2_Service WHERE ItemName LIKE '%$search%'",
            'Stock_Main2_Study' => "SELECT id, ItemName AS ชื่ออุปกรณ์, list AS รายการ, Amount AS จำนวนคงเหลือ, 
                                    CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, note AS หมายเหตุ, package AS ชุดที่ 
                                    FROM Stock_Main2_Study WHERE ItemName LIKE '%$search%'",
            'Stock_Main3_Ppon' => "SELECT id, ItemName AS ชื่ออุปกรณ์, SerialNumber AS ซีเรียลนัมเบอร์, 
                                   whereItem AS เก็บไว้ที่, date AS วันที่, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, note AS หมายเหตุ 
                                   FROM Stock_Main3_Ppon WHERE ItemName LIKE '%$search%'",
            'Stock_Main4_VR' => "SELECT id, ItemName AS ชื่ออุปกรณ์, Amount AS จำนวนคงเหลือ, CASE 
                                WHEN status IS NULL OR status = '' THEN 'ไม่ระบุ ❔' 
    WHEN status = 'active' THEN 'ปกติ ✅'
    WHEN status = 'active_notgood' THEN 'ปกติแต่ไม่ดี 🟨'
    WHEN status = 'wait_test' THEN 'รอเทส 🔵'
    WHEN status = 'not_active' THEN 'เสีย ❌'
    WHEN status = 'wait' THEN 'รอซ่อม 🟡'
    WHEN status = 'repairing' THEN 'กำลังซ่อม 🟤' 
                                ELSE status 
                            END AS สถานะ, 
                                 note AS หมายเหตุ, date AS วันที่ FROM Stock_Main4_VR WHERE ItemName LIKE '%$search%'"
        ];

        $query = $queries[$table];
        $result = mysqli_query($con, $query);
        $table_display_name = "";
        if ($result) {
            if (mysqli_num_rows($result) > 0) {

                switch ($table) {
                    case 'Stock_Main':
                        $table_display_name = "ห้องประชุม";
                        break;
                    case 'Stock_Main2':
                        $table_display_name = "ห้องสแปร์ (บนชั้น)";
                        break;
                    case 'Stock_Main2_Controller':
                        $table_display_name = "ห้องสแปร์ (ตู้คอนโทรล)";
                        break;
                    case 'Stock_Main2_inroom':
                        $table_display_name = "ห้องสแปร์ (ในห้อง)";
                        break;
                    case 'Stock_Main2_KPS':
                        $table_display_name = "ห้องสแปร์ (ไดร์ฟ)";
                        break;
                    case 'Stock_Main2_Service':
                        $table_display_name = "ของเซอร์วิส";
                        break;
                    case 'Stock_Main2_Study':
                        $table_display_name = "ชุดอบรม";
                        break;
                    case 'Stock_Main3_Ppon':
                        $table_display_name = "ชุดโรบอทพี่พล";
                        break;
                    case 'Stock_Main4_VR':
                        $table_display_name = "ชุด VR";
                        break;
                    default:
                        $table_display_name = "ไม่ทราบแหล่งข้อมูล";
                }



                echo "<h4>ข้อมูลจากตาราง: $table_display_name</h4>";
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered">';
                echo '<thead><tr>';

                // ดึงชื่อคอลัมน์ทั้งหมด
                $field_info = mysqli_fetch_fields($result);
                foreach ($field_info as $val) {
                    echo "<th>" . $val->name . "</th>";
                }
                echo "<th>Edit</th>"; // เพิ่มคอลัมน์สำหรับปุ่มแก้ไข
                echo '</tr></thead>';
                echo '<tbody>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    foreach ($row as $key => $column) {
                        echo "<td>" . htmlspecialchars($column) . "</td>";
                    }

                    // ตรวจสอบว่ากำลังแสดงข้อมูลจาก "ภาพรวม" หรือหมวดหมู่เฉพาะ
                    $current_table = ($table === 'all') ? $table_name : $table;

                    // เพิ่มปุ่มแก้ไขที่ลิงก์ไปยัง editItem.php
                    echo '<td>';
                    if (isset($row['id'])) { // ตรวจสอบว่ามีคอลัมน์ id
                        echo '<a href="editItem.php?id=' . urlencode($row['id']) . '&table=' . urlencode($current_table) . '" class="btn btn-warning btn-sm">แก้ไข</a>';
                    } else {
                        echo 'ไม่มี ID';
                    }
                    echo '</td>';
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
