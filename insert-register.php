<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $portnumber = $_POST['portnumber'];
    $idcard = $_POST['idcard'];
    $phone = $_POST['phone'];
    $pass = $_POST['password'];

    // ตรวจสอบว่า portnumber และ idcard ไม่ซ้ำ
    $checkDuplicate = "SELECT * FROM infouser WHERE portnumber='$portnumber' OR idcard='$idcard'";
    $result = $conn->query($checkDuplicate);

    if ($result->num_rows > 0) {
        // ถ้ามีข้อมูลซ้ำในฐานข้อมูล
        echo "Error: Portnumber or ID Card already exists";
    } else {
        // ถ้าไม่มีข้อมูลซ้ำในฐานข้อมูล ทำการเพิ่มข้อมูล
        $sql = "INSERT INTO infouser (portnumber, idcard, phone, password)
                VALUES ('$portnumber', '$idcard', '$phone', '$pass')";
         header("Location: login.php");

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
