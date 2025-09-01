<?php
include 'config.php';
session_start();

$s = $_POST['student_id'] ?? '';
if(!$s){ echo "<a href='login.php'>กรุณากรอกเลขนักศึกษา</a>"; return; }

$s = mysqli_real_escape_string($conn, $s);
$r = mysqli_query($conn,"SELECT * FROM users WHERE student_id='$s'");
$d = mysqli_fetch_assoc($r);

if($d){
    $_SESSION['student_id'] = $d['student_id'];
    $_SESSION['user'] = $d['fullname'];
    echo "<script>location='welcome.php';</script>";
}else{
    echo "<a href='login.php'>ไม่พบผู้ใช้งาน</a>";
}
?>
