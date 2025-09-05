<?php
$host = "sql305.infinityfree.com";       // โฮสต์ MySQL ของ InfinityFree
$username = "if0_39645282";               // ชื่อผู้ใช้ฐานข้อมูล
$password = "p4qNdcwqkawyT";             // รหัสผ่านฐานข้อมูล
$database = "if0_39645282_higherlevel";  // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $conn->connect_error);
}

// ตั้งค่า charset ให้รองรับภาษาไทย
$conn->set_charset("utf8mb4");
?>
