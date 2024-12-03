<?php
include('connection.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_GET['category'])) {
  $category = $_GET['category'];

  // ตารางที่มีคอลัมน์ Amount
  $allowedTables = [
    'Stock_Main',
    'Stock_Main2',
    'Stock_Main2_inroom',
    'Stock_Main2_Study',
    'Stock_Main4_VR'
  ];

  // ตรวจสอบว่า category ถูกต้อง
  if ($category === 'all') {
    // ดึงข้อมูลจากทุกตารางที่มี Amount
    $query = '';
    foreach ($allowedTables as $table) {
      if (!empty($query)) {
        $query .= " UNION "; // รวมผลลัพธ์จากทุกตาราง
      }
      $query .= "SELECT id, ItemName FROM $table";
    }
  } elseif (in_array($category, $allowedTables)) {
    // ดึงข้อมูลเฉพาะตารางที่เลือก
    $query = "SELECT id, ItemName FROM $category";
  } else {
    // กรณีหมวดหมู่ไม่ถูกต้อง
    echo json_encode(['error' => 'Invalid category']);
    exit;
  }

  // รัน query
  $result = mysqli_query($con, $query);

  $items = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $items[] = $row; // เก็บผลลัพธ์ใน array
    }
  }

  // ส่งข้อมูลกลับในรูปแบบ JSON
  header('Content-Type: application/json');
  echo json_encode($items);
} else {
  echo json_encode(['error' => 'No category specified']);
}

?>