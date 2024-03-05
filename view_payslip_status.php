<?php
include('connect.php'); 

if(isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    
    $sql = "SELECT Pay_slip FROM payment WHERE payment_id = $payment_id";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageData = $row['Pay_slip'];
        //แปลงข้อมูล binary เป็น base64 string
        $imageBase64 = base64_encode($imageData);
        //สร้าง HTML tag ให้แสดงรูป
        echo "<img src='data:image/jpeg;base64," . $imageBase64 . "' alt='Pay slip'>";
    } else {
        echo "No image found.";
    }
} else {
    echo "No ID provided.";
}
?>