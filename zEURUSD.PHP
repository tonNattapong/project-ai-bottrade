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
    <link rel="stylesheet" href="css/download-inside-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=IBM+Plex+Sans+Thai&family=Prompt&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
     
   
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
           <li><a href="port.php"><span><i class="fa-solid fa-square-poll-vertical" style="color: #d17842;"></i></span></a></li>
           <li><a href="status.php"><span> <i class="fa-solid fa-square-check" style="color: #52555A;"></i></span></a></li>
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
   



    <script>
    function getData(symbol, timeframe) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_data.php?symbol=" + symbol + "&timeframe=" + timeframe, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("table-container").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>
    <div class="container">
        <h3 style="color:#d17842;">EURUSD</h3>
        <h3>Select Time Frame</h3>
        <div class="button-container">
            <button value="EURUSD_M15" onclick="getData('EURUSD', '15')">M15</button>
            <button value="EURUSD_M30" onclick="getData('EURUSD', '30')">M30</button>
        </div>  
    
        <table  class="tablecontent" id="table-container">
            <thead>
               
            </thead>
            <tbody>
              
            </tbody>
        </table>
    
        
    </div>







</body>

</html>
