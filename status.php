<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit(); // ต้องใส่ exit เพื่อหยุดการทำงานทันทีหลังจาก redirect
}
?>

<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

if ($status === 'success') {
    echo "<script>alert('$message');</script>";
} elseif ($status === 'error') {
    echo "<script>alert('$message');</script>";
}

// Include database connection file
include('connect.php');

// Retrieve permission from database
$sql_permission = "SELECT permission FROM infouser WHERE portnumber = " . $_SESSION['portnumber'];
$result_permission = $conn->query($sql_permission);

if ($result_permission->num_rows > 0) {
    $row_permission = $result_permission->fetch_assoc();
    $permission = $row_permission['permission'];
} else {
    $permission = 'pending'; // Default to 'pending' if permission is not found
}
?>
<?php
include('connect.php');
$sql_permission = "SELECT permission FROM infouser WHERE portnumber = " . $_SESSION['portnumber'];
$result_permission = $conn->query($sql_permission);

if ($result_permission->num_rows > 0) {
    $row_permission = $result_permission->fetch_assoc();
    $permission = $row_permission['permission'];
} else {
    $permission = 'pending'; // Default to 'pending' if permission is not found
}

// Check if the user has permission to use the bot
$sql_bot_permission = "SELECT stats FROM history WHERE portnumber = '{$_SESSION["portnumber"]}'";
$result_bot_permission = $conn->query($sql_bot_permission);

if ($permission === 'ALLOW') {
    // Check if the bot status is 1 or if there is no record in history table
    if ($result_bot_permission->num_rows == 0) {
        $status_class = "status green";
        $status_text = "ใช้งานได้";
    } else {
        $row_bot_permission = $result_bot_permission->fetch_assoc();
        $stats_bot_permission = $row_bot_permission['stats'];
        if ($stats_bot_permission == 1) {
            $status_class = "status green";
            $status_text = "ใช้งานได้";
        } elseif ($stats_bot_permission == 0) {
            $status_class = "status orange";
            $status_text = "ค้างชำระ";
        }
    }
} elseif ($permission === 'pending' || $permission === 'not allow') {
    $status_class = "status orange";
    $status_text = "ไม่มีสิทธิ์เข้าใช้งาน";
} else {
    $status_class = "status orange";
    $status_text = "ไม่มีสิทธิ์เข้าใช้งาน";
}

// เตรียมคำสั่ง SQL สำหรับดึงข้อมูลจากตาราง infouser
$sql = "SELECT portnumber, permission FROM infouser WHERE portnumber = '" . $_SESSION["portnumber"] . "'";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
if ($result->num_rows > 0) {
    // วนลูปเพื่อดึงข้อมูลแต่ละแถว
    while($row = $result->fetch_assoc()) {
        $portnumber = $row["portnumber"];
        $permission = $row["permission"];
    }
} else {
    echo "0 results";
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<link rel="stylesheet" href="css/status-style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=IBM+Plex+Sans+Thai&family=Prompt&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
           <li><a href="port.php"><span><i class="fa-solid fa-square-poll-vertical" style="color: #52555A;"></i></span></a></li>
           <li><a href="status.php"><span> <i class="fa-solid fa-square-check" style="color: #d17842;"></i></span></a></li>
           <li> <a href="homepage.php"><span> <i class="fa-solid fa-house" style="color: #52555A"></i></span></a></li>
           <li><a href="download.php"><span class="dl"><i class="fa-solid fa-circle-down" style="color: #52555A;"></i></span></i></a></li>
           <li><a href="payment.php"><span><i class="fa-solid fa-file-invoice-dollar" style="color: #52555A;"></i></span></i></a></li>
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
   

 
    </div>
            
        <?php if ($permission === 'ALLOW'): ?>
            <div class="status-content">
                <div class="status-box">
                <h2>สถานะบอท</h2>
                    <div class="status <?php echo $status_class; ?>">
                        
                    <h1 id="status"><?php echo $status_text; ?></h1>
                        </div>
                    <div class="action">
                        <a href="payment.php" class="bill">บิลของฉัน</a>
                    </div>
                </div>
            <?php elseif ($permission === 'pending'): ?>
                 <h2 class="require-content">รอการอนุมัติจากแอดมิน</h2>
            <?php else: ?>
                 <div class="require-content">
                    <h2>ขอสิทธิการเข้าใช้งาน</h2>
                    <form action="request.php" method="post">
                        <input type="submit" value="ส่งคำขอ" class="submitbut">
                    </form>
                </div>
            <?php endif; ?>

        </div>
    
  

  

 

        
</body>
</html>
