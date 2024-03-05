<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}

include('connect.php');

$sql = "SELECT portnumber, permission FROM infouser WHERE portnumber = '" . $_SESSION["portnumber"] . "'";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่

?>

<?php
if ($result->num_rows > 0) {
    // วนลูปเพื่อดึงข้อมูลแต่ละแถว
    while($row = $result->fetch_assoc()) {
        $portnumber = $row["portnumber"];
        $permission = $row["permission"];
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/payment-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=IBM+Plex+Sans+Thai&family=Prompt&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
    
</head>

<body>
<div class="sidebar">
           

           <ul>    
           <li><a href="#"> <img src="img/logo3.png" alt="">   </a></li>
           <li> <a href="homepage.php"><span> Home</span></a></li>
           <li><a href="port.php"><span> Port</span></a></li>
           <li><a href="status.php"></i><span> Status</span></a></li>
           <li><a href="download.php"><span class="dl"> Dowload</span></i></a></li>
           <li><a href="payment.php"><span> My Bill</span></i></a></li>
           </ul>

           <div class="account-info">
               <div class="profile-pic">
                       <img src="img/1.png" alt="Profile picture">
               </div>
                   
               <div class="user-details">
                               <p class="port-number">Port: <?php echo $portnumber; ?></p>
                               <?php
                               // ตรวจสอบค่าของ $permission เพื่อแสดงข้อความและสีตามเงื่อนไข
                               if ($permission == "ALLOW") {
                                   echo '<p class="status" style="color: #00FF00;">มีสิทธิเข้าใช้งาน</p>';
                               } elseif ($permission == "pending") {
                                   echo '<p class="status" style="color: #E1A12B;">รออนุมัติ</p>';
                               } elseif ($permission == "not allow") {
                                   echo '<p class="status" style="color: red;">ไม่มีสิทธิเข้าใช้งาน</p>';
                               } else {
                                   echo '<p class="status" style="color: white;">ไม่ทราบสถานะ</p>';
                               }
                               ?>
               </div>
               <div class="logoutbut">
                   <a href="logout.php" ><i class="fa-solid fa-arrow-right-from-bracket"></i> </a>
               </div>
           </div>
          
   </div>
   <div class="mobile_sidebar">
          

          <ul>    
           <li><a href="port.php"><span><i class="fa-solid fa-square-poll-vertical" style="color:#52555A;"></i></span></a></li>
           <li><a href="status.php"><span> <i class="fa-solid fa-square-check" style="color: #52555A;"></i></span></a></li>
           <li> <a href="homepage.php"><span> <i class="fa-solid fa-house" style="color: #52555A"></i></span></a></li>
           <li><a href="download.php"><span class="dl"><i class="fa-solid fa-circle-down" style="color: #52555A;"></i></span></i></a></li>
           <li><a href="payment.php"><span><i class="fa-solid fa-file-invoice-dollar" style="color: #d17842;"></i></span></i></a></li>
          </ul>

          
         
  </div>
   <div class="content">
       <div class="mobile_mode">
          <a href=""><img src="img/logo3.png" alt=""></a> 
           <div class="account-info">
               <div class="profile-pic">
                       <img src="img/1.png" alt="Profile picture">
               </div>
                   
               <div class="user-details">
                               <p class="port-number">Port: <?php echo $portnumber; ?></p>
                               <?php
                               // ตรวจสอบค่าของ $permission เพื่อแสดงข้อความและสีตามเงื่อนไข
                               if ($permission == "ALLOW") {
                                   echo '<p class="status" style="color: #00FF00;">มีสิทธิเข้าใช้งาน</p>';
                               } elseif ($permission == "pending") {
                                   echo '<p class="status" style="color: #E1A12B;">รออนุมัติ</p>';
                               } elseif ($permission == "not allow") {
                                   echo '<p class="status" style="color: red;">ไม่มีสิทธิเข้าใช้งาน</p>';
                               } else {
                                   echo '<p class="status" style="color: white;">ไม่ทราบสถานะ</p>';
                               }
                               ?>
               </div>
               <div class="logoutbut">
                   <a href="logout.php" ><i class="fa-solid fa-arrow-right-from-bracket"></i> </a>
               </div>
          </div>
      
       </div>
   

    <div class="container">
    <h2>บิลของฉัน</h2>
        <table class='tablecontent'>
    <tr>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Frequency</th>
        <th>Total Amount</th>
        <th>Pay</th>
    </tr>
    <?php
include('connect.php');

$portnumber = $_SESSION['portnumber'];

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง history โดยรวมและจัดกลุ่มตามวัน
$sql = "SELECT 
            portnumber, 
            MIN(date) AS start_date, 
            MAX(date) AS end_date, 
            SUM(money) AS total, 
            COUNT(money) AS total_trade 
        FROM 
            history 
        WHERE 
            portnumber = '$portnumber' AND
            stats = 0 AND
            money > 0 AND
            date >= (SELECT MIN(date) FROM history WHERE portnumber = '$portnumber') AND 
            date <= NOW() 
        GROUP BY 
            FLOOR(DATEDIFF(date, (SELECT MIN(date) FROM history WHERE portnumber = '$portnumber')) / 7)";

$result = mysqli_query($conn, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            // คำนวณหาจำนวนวันระหว่าง start_date และ end_date
            $start_date = new DateTime($row['start_date']);
            $end_date = new DateTime($row['end_date']);
            $period = $start_date->diff($end_date)->days + 1; // เพิ่ม 1 เพราะจำนวนวันที่คำนวณต้องรวมวันที่เริ่มต้นด้วย
            echo "<tr>";
            echo "<td>" . $row['start_date'] . "</td>";
            echo "<td>" . $row['end_date'] . "</td>";
            echo "<td>" . $row['total_trade'] . "</td>";
            echo "<td>" . $row['total'] ." $". "</td>";
            echo "<td>";
            // เปลี่ยนปุ่มเป็นลิงก์ที่เชื่อมโยงไปยังหน้า invoice.php พร้อมส่งพารามิเตอร์ที่เกี่ยวข้อง
            echo "<a href='invoice.php?start_date=" . urlencode($row['start_date']) . "&end_date=" . urlencode($row['end_date']) . "&total_trade=" . urlencode($row['total_trade']) . "&total=" . urlencode($row['total']) . "' class='paybut'>Continue</a>";

            echo "</td>";           
            
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No records found</td></tr>";
    }
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

?>
</table>

    </div>

    

    
    </body>
  

    </html>
