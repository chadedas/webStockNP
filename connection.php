<?php      
    $host = "localhost";  
    $user = "nproboti_stock";  
    $password = 'xzh2cyjUrJrLuXNXzK8n';  
    $db_name = "nproboti_stockwebapp";  
      
 // สร้างการเชื่อมต่อกับฐานข้อมูล
    $con = mysqli_connect($host, $user, $password, $db_name);  
    
    // ตรวจสอบการเชื่อมต่อ
    if(mysqli_connect_errno()) {  
        die("Failed to connect with MySQL: ". mysqli_connect_error());  
    }  

    // ตั้งค่าการเข้ารหัสให้เป็น utf8mb4 เพื่อรองรับภาษาไทย
    mysqli_set_charset($con, "utf8mb4");
?>