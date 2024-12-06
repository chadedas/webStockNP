<?php
include('connection.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_GET['id']) && isset($_GET['category'])) {
    $itemId = mysqli_real_escape_string($con, $_GET['id']);
    $category = $_GET['category'];

    // ตรวจสอบตารางที่อนุญาต
    $allowedTables = ['Stock_Main', 'Stock_Main2', 'Stock_Main2_inroom', 'Stock_Main2_Study', 'Stock_Main4_VR'];
    if (!in_array($category, $allowedTables)) {
        echo json_encode(['error' => 'Invalid category']);
        exit;
    }

    // Query ดึงข้อมูล
    if($category === "Stock_Main4_VR"){
      $query = "SELECT id, ItemName FROM $category WHERE id = '$itemId'";
    $result = mysqli_query($con, $query);
    }elseif($category === "Stock_Main2_Study"){
      $query = "SELECT id, ItemName, list FROM $category WHERE id = '$itemId'";
      $result = mysqli_query($con, $query);
    }else{
        $query = "SELECT id, ItemName, whereItem FROM $category WHERE id = '$itemId'";
      $result = mysqli_query($con, $query);
    }
    

    if ($result && mysqli_num_rows($result) > 0) {
        $itemDetails = mysqli_fetch_assoc($result);
        echo json_encode($itemDetails);
    } else {
        echo json_encode(['error' => 'Item not found']);
    }
} else {
    echo json_encode(['error' => 'No item ID or category provided']);
}
?>
