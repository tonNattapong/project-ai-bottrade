<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}
?>
<?php
include('connect.php'); 



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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/download-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=IBM+Plex+Sans+Thai&family=Prompt&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="script.js" defer></script>
    <title>Document</title>
    <script>
       window.addEventListener('scroll', function() {
    var sidebar = document.querySelector('.sidebar');
    var currentPosition = window.scrollY;
    if (currentPosition > 10) { // 100 เป็นค่าที่คุณสามารถปรับเปลี่ยนได้ตามความต้องการ
        sidebar.classList.add('hidden-sidebar');
    } else {
        sidebar.classList.remove('hidden-sidebar');
    }
});
    </script>
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
           <li><a href="port.php"><span><i class="fa-solid fa-square-poll-vertical" style="color:  #52555A;"></i></span></a></li>
           <li><a href="status.php"><span> <i class="fa-solid fa-square-check" style="color: #52555A;"></i></span></a></li>
           <li> <a href="homepage.php"><span> <i class="fa-solid fa-house" style="color: #52555A"></i></span></a></li>
           <li><a href="download.php"><span class="dl"><i class="fa-solid fa-circle-down" style="color: #d17842;"></i></span></i></a></li>
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
   
<h2 class="topic"> Top Download <i class="fa-solid fa-fire" style="color: #ff8800;"></i></h2> <br>
        <div class="slider">
            
            <?php
                include('connect.php'); 

                // เตรียมคำสั่ง SQL สำหรับดึงข้อมูล symbol จากตาราง template ที่ dowload_count มากที่สุด 3 รายการ
                $sql = "SELECT name, Symbol, download_count,timeframe FROM template ORDER BY download_count DESC LIMIT 3";
                $result = $conn->query($sql);
                $count=0;
                // ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
                if ($result->num_rows > 0) {
                    // วนลูปเพื่อดึงข้อมูลแต่ละแถว
                    while($row = $result->fetch_assoc()) {
                        $timeframe = $row["timeframe"];
                        $name = $row["name"];
                        $Symbol = $row["Symbol"];
                        $download_count = $row["download_count"];
                        $count++;
                        // สร้างชื่อไฟล์ภาพโดยเริ่มต้นด้วย 'z' และลงท้ายด้วย '.png'
                        $image_filename = 'z' . $Symbol . '.png';
                ?>

                        <div class="slide">
                            <img src="img/<?php echo $Symbol; ?>.png" alt="">
                            <h2><?php echo $count; ?></h2>
                            <div class="label">
                                
                                <h3><?php echo $Symbol; ?> M<?php echo $timeframe; ?> </h3>
                                <h4><?php echo $name; ?></h4>
                            </div>
                            <p><?php echo $download_count; ?> <i class="fa-solid fa-download" style="color: #d17842;"></i></p>

                            <a href="z<?php echo $Symbol; ?>.php">visit <i class="fa-solid fa-arrow-right" style="color: #ffffff;"></i></a>
                        </div>
                <?php
                    }
                } else {
                    echo "0 results";
                }
                $conn->close();
                ?>
                <br>
    </div>
    <h2 class="topic2">Download <i class="fa-solid fa-circle-down" style="color: #ff8800;"></i></h2>
    <div class="container">
        
        <div class="box">
            <img class="image" src="img/XAUUSD.png" alt="">
            <span class="name">XAUUSD</span> 
            <a href="zXAUUSD.php">View Details   </i></a>  
        </div>
        <div class="box">
            <img class="image" src="img/EURUSD.png" alt="">
            <span class="name">EURUSD</span>
            <a href="zEURUSD.php">View Details </i></a>   

        </div>
        <div class="box">
            <img class="image" src="img/USDCAD.png" alt="">
            <span class="name">USDCAD</span> 
            <a href="zEURUSD.php">View Details </i></a>  

        </div>
    
    
    <?php if ($permission === 'pending'): ?>

    <script>
        alert('คุณไม่มีสิทธิเข้าใช้งาน กรุณารอคำอนุมัติจากแอดมิน');
        window.location.href = 'status.php';
    </script>

<?php elseif ($permission === 'not allow'): ?>
    <script>
        alert('คุณไม่มีสิทธิเข้าใช้งาน กรุณาไปยังหน้า Status เพื่อร้องขอสิทธิ');
        window.location.href = 'status.php';
    </script>

<?php endif; ?>
</div>


    
  
</body>

</html>
