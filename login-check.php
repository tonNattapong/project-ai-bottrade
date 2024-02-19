<?php
include('connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $portnumber = $_POST['portnumber'];
    $password = $_POST['password'];

    // ทำการค้นหาข้อมูลในฐานข้อมูล
    $sql = "SELECT * FROM infouser WHERE portnumber='$portnumber' AND password='$password'";
    $result = $conn->query($sql);

    if (!$result) {
        // ตรวจสอบว่ามีข้อผิดพลาดในคำสั่ง SQL
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows == 1) {
        $row=mysqli_fetch_array($result);
        $_SESSION['portnumber'] = $portnumber;
        $_SESSION['Role'] = $row['Role'];
            if( $_SESSION['Role'] == 'user'){
                header("Location: homepage.php"); 
            }else if( $_SESSION['Role'] == 'admin') {
                header("Location: admin.php"); 
            }
        
        exit(); // ต้องใส่ exit เพื่อหยุดการทำงานทันทีหลังจาก redirect
    } else {
        // เมื่อไม่เจอข้อมูลในฐานข้อมูล
        echo "Login failed. Invalid Port number or password.";
    }
}
?>
