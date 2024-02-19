<?php
session_start();
// เชื่อมต่อฐานข้อมูล
include('connect.php');
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $portnumber = $_SESSION["portnumber"];

// บันทึกข้อมูลลงในฐานข้อมูล
$sql = "UPDATE infouser SET permission = 'pending'WHERE portnumber = '$portnumber';";


if ($conn->query($sql) === TRUE) {
    $status = "success";
    $message = "ส่งคำขอสิทธิ์สำเร็จ";
    
} else {
    $status = "success";
    $message = "ส่งคำขอสิทธิ์สำเร็จ";    
}


// ปิดการเชื่อมต่อ
$conn->close();

header("Location: status.php?status=$status&message=$message");
exit;