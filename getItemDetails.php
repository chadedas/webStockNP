<?php
include('connection.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_GET['id'])) {
  $itemId = $_GET['id'];
  
  // Query to get item details by ID
  $query = "SELECT id, Amount, whereItem, ItemName FROM Stock_Main WHERE id = '$itemId'";
  $result = mysqli_query($con, $query);

  if ($result) {
    $itemDetails = mysqli_fetch_assoc($result);
    echo json_encode($itemDetails);
  } else {
    echo json_encode(['error' => 'Item not found']);
  }
} else {
  echo json_encode(['error' => 'No item ID provided']);
}
?>