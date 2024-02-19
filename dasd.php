<?php
session_start();

if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit(); // ต้องใส่ exit เพื่อหยุดการทำงานทันทีหลังจาก redirect
}

include('connect.php');

// Retrieve portnumber from the session
$portnumber = $_SESSION["portnumber"];

$sql = "SELECT * FROM infouser WHERE portnumber='$portnumber'";
$result = $conn->query($sql);

// แสดงผล
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID card: " . $row['idcard'] . "<br>";
        echo "Port number : " . $row['portnumber'] . "<br>";
        echo "Phone: " . $row['phone'] . "<br>";
        echo "Permission status: " . $row['permission'] . "<br>";
        echo "Role: " . $row['Role'] . "<br>";

        echo "<hr>";
    }
} else {
    echo "ไม่มีคำขอสิทธิ์";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
Connected successfully
