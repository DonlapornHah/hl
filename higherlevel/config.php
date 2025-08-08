<?php
// กำหนดค่าการเชื่อมต่อฐานข้อมูล
$servername = "sql305.infinityfree.com";
$username = "if0_39645282";
$password = "p4qNdcwqkawyT";  
$dbname = "if0_39645282_higherlevel"; 

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// กำหนด charset ให้รองรับ UTF-8 (ถ้าต้องการ)
$conn->set_charset("utf8");

// ถ้าต้องการทดสอบ
// echo "เชื่อมต่อฐานข้อมูลสำเร็จ";
?>
