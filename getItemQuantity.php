<?php
include('connection.php');

if (isset($_GET['item'])) {
    $itemName = mysqli_real_escape_string($con, $_GET['item']);

    // ดึงจำนวนสินค้าจาก Stock_Main ที่ตรงกับชื่อของที่เลือก
    $query = "SELECT Amount FROM Stock_Main WHERE ItemName = '$itemName'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $quantity = $row['Amount'];

        // ส่งจำนวนสินค้าที่มีอยู่ในสต็อกกลับไปในรูปแบบ JSON
        echo json_encode(range(1, $quantity));
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>
