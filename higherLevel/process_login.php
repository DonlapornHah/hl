<?php
session_start();
include 'config.php';

// รับค่าจากฟอร์ม
$student_id = $_POST['student_id'] ?? '';

if (empty($student_id)) {
    echo "<a href='login.php'>กรุณากรอกเลขนักศึกษา</a>";
    exit;
}

// ป้องกัน SQL Injection
$student_id = $conn->real_escape_string($student_id);

// ตรวจสอบผู้ใช้งาน
$sql = "SELECT * FROM users WHERE student_id = '$student_id' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // สร้าง session
    $_SESSION['student_id'] = $user['student_id'];
    $_SESSION['user'] = $user['fullname'];

    // Redirect ไปหน้า welcome
    header("Location: welcome.php");
    exit;
} else {
    echo "<a href='login.php'>ไม่พบผู้ใช้งาน กรุณากรอกรหัสนักศึกษาให้ถูกต้อง</a>";
}
?>
