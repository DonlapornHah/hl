<?php
$host = "localhost";
$username = "root";      // user ของ MySQL บน XAMPP ปกติคือ root
$password = "";          // ถ้าไม่ได้ตั้ง password ให้เว้นว่าง
$database = "higherlevel";  // ชื่อฐานข้อมูลที่สร้างใน phpMyAdmin

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $conn->connect_error);
}
?>
