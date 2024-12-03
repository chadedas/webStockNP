<?php
include('connection.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่าที่จำเป็นมาหรือไม่
if (isset($_GET['category'], $_GET['item_id'], $_GET['item'])) {
    $category = mysqli_real_escape_string($con, $_GET['category']);
    $item_id = mysqli_real_escape_string($con, $_GET['item_id']);
    $item_name = mysqli_real_escape_string($con, $_GET['item']);

    // ตรวจสอบว่า category อยู่ในรายการที่อนุญาต
    $allowedTables = [
        'Stock_Main',
        'Stock_Main2',
        'Stock_Main2_inroom',
        'Stock_Main2_Study',
        'Stock_Main4_VR'
    ];

    if (!in_array($category, $allowedTables)) {
        echo json_encode(['error' => 'Invalid category']);
        exit;
    }

    // Query เพื่อดึงข้อมูลจำนวนจากตารางที่ระบุ
    $query = "SELECT Amount FROM $category WHERE id = '$item_id' AND ItemName = '$item_name'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $amount = $row['Amount']; // จำนวนที่มีอยู่ในคลัง

        // ถ้าจำนวนในคลังเป็น 0 ให้แสดงว่า "หมด"
        if ($amount <= 0) {
          echo json_encode(['error' => 'ไม่เหลือในสต็อก']);
      } else {
          // สร้างตัวเลือกจำนวนตั้งแต่ 1 ถึงจำนวนที่มีในสต็อก
          $quantities = range(1, $amount);

          header('Content-Type: application/json');
          echo json_encode($quantities);
      }
    } else {
        echo json_encode(['error' => 'Item not found or no stock available']);
    }
} else {
    echo json_encode(['error' => 'Missing required parameters']);
}
?>
