<?php
include('connection.php');

if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($con, $_GET['search']); // ป้องกัน SQL Injection
  // คำสั่ง SQL สำหรับค้นหาผู้ใช้
  $sql = "SELECT username, permission, date, hwid FROM login WHERE username LIKE '%$search%'";
  $result = mysqli_query($con, $sql);

  if ($result) {
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (!empty($data)) {
      // แสดงผลลัพธ์การค้นหาทั้งหมด
      foreach ($data as $row) {
        echo "<tr class='text-center'>
                <td>" . htmlspecialchars($row['username']) . "</td>
                <td>" . htmlspecialchars($row['permission']) . "</td>
                <td>" . (empty($row['hwid']) ? '❌' : '✅') . "</td>
                <td>" . htmlspecialchars($row['date']) . "</td>
                <td><form action='edituser.php' method='get'>
                      <input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>
                      <button type='submit' class='btn btn-success btn-sm fw-bold'>แก้ไขข้อมูล</button>
                    </form></td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='5' class='text-center'>ไม่พบข้อมูลผู้ใช้</td></tr>";
    }
  } else {
    echo "เกิดข้อผิดพลาดในการค้นหา: " . mysqli_error($con);
  }
}
?>
