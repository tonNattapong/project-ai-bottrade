<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}

include('connect.php');

if(isset($_POST["submit"])) {
    if(isset($_FILES['templateFile']['tmp_name']) && !empty($_FILES['templateFile']['tmp_name'])) {
        // รับค่าข้อมูลจากฟอร์ม
        $name = $_POST['name'];
        $Symbol = $_POST['Symbol'];
        $backtest_name = $_POST['backtest_name'];
        
        // ตรวจสอบว่ามีการเลือกไฟล์ backtest_data หรือไม่
        if(isset($_FILES['backtest_data']['tmp_name']) && !empty($_FILES['backtest_data']['tmp_name'])) {
            $backtest_data = file_get_contents($_FILES['backtest_data']['tmp_name']);
        } else {
            $backtest_data = ""; // กำหนดค่าว่างหากไม่มีการเลือกไฟล์
        }

        // รับข้อมูลจากไฟล์ templateFile
        $templateFile = $_FILES['templateFile']['tmp_name'];
        $templateContent = file_get_contents($templateFile);

        // แยกข้อมูลจากไฟล์ที่อัปโหลด
        $data = explode("\n", $templateContent);
        $templateData = array();
        foreach($data as $line) {
            $lineData = explode("=", $line);
            if (isset($lineData[1])) {
                $templateData[$lineData[0]] = $lineData[1];
            }
        }

        // เพิ่มข้อมูลลงในฐานข้อมูล
        $sql = "INSERT INTO template (name, Symbol, backtest_name, backtest_data, merch_id, merch_id_1, lotSize, lotSize_1, numopen, numopen_1, takeprofit, takeprofit_1, stoploss, stoploss_1, stopequity, stopequity_1, timeframe,version) 
                VALUES ('$name', '$Symbol','$backtest_name', ?, '{$templateData['merch_id']}', '{$templateData['merch_id,1']}', '{$templateData['lotSize']}', '{$templateData['lotSize,1']}', '{$templateData['numopen']}', '{$templateData['numopen,1']}', '{$templateData['takeprofit']}', '{$templateData['takeprofit,1']}', '{$templateData['stoploss']}', '{$templateData['stoploss,1']}', '{$templateData['stopequity']}', '{$templateData['stopequity,1']}', '{$templateData['timeframe']}','$version')";
        
        // สร้าง statement และ bind ข้อมูล
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('b', $backtest_data);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Data inserted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        $stmt->close();
    } else {
        echo "No file uploaded";
    }
}

$conn->close();
?>
