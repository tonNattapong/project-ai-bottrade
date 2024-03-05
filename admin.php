<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}

include('connect.php'); // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบ role ของผู้ใช้จากฐานข้อมูล
$portnumber = $_SESSION["portnumber"];
$sql = "SELECT role FROM infouser WHERE portnumber = '$portnumber'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // ถ้าพบข้อมูลผู้ใช้
    $row = $result->fetch_assoc();
    $role = $row["role"];
    
    // ตรวจสอบว่า role เป็น admin หรือไม่
    if ($role !== "admin") {
        header("location: login.php");
        exit();
    }
} else {
    // ถ้าไม่พบข้อมูลผู้ใช้
    header("location: login.php");
    exit();
}

?>
<?php
include('connect.php');

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$portnumber = $_SESSION['portnumber'];
$sql = "SELECT profit_date, equity, balance FROM profit WHERE portnumber = '$portnumber'";
$result = $conn->query($sql);

// สร้างอาร์เรย์สำหรับเก็บข้อมูลที่ดึงมา
$chartData = array();
while ($row = $result->fetch_assoc()) {
    $chartData[] = array(
        'profit_date' => $row['profit_date'],
        'equity' => $row['equity'],
        'balance' => $row['balance']
    );
}
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
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=IBM+Plex+Sans+Thai&family=Prompt&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
   

           


   
    <div class="container">
    <h1 style="color: #d17842;
                text-align:center;">Admmin</h1>
    <h2> Permission </h2>
    <table class='tablecontent'>
        <tr>
            <th>portnumber</th>
            <th>id card</th>
            <th>phone</th>
            <th>permission</th>
            <th>ALLOW</th>
        </tr>
        <?php
        include('connect.php');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM infouser";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['permission'] != "ALLOW") {
                    echo "<tr>
                            <td>" . $row['portnumber'] . "</td>
                            <td>" . $row['idcard'] . "</td>
                            <td>" . $row['phone'] . "</td> 
                            <td>" . $row['permission'] . "</td>
                            <td>
                                <form action='allow.php' method='post'>
                                    <input type='hidden' name='portnumber' value='" . $row['portnumber'] . "'>
                                    <input type='submit' name='allow' value='ALLOW' class = 'allowbut'>
                                </form>
                            </td>
                        </tr>";
                }
            }
        } else {
            echo "<tr><td colspan='5'>0 results</td></tr>";
        }

        
        ?>
    </table>
        <h2>Update Template</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="templateFile">Choose file:</label>
    <input type="file" id="templateFile" name="templateFile" accept=".txt">
    <br><br>
    <label for="name">Name:</label>
    <input type="text" id="name" name="name">
    <br><br>
    <label for="Symbol">Symbol:</label>
    <input type="text" id="Symbol" name="Symbol">
    <br><br>
    <label for="backtest_name">Backtest Name:</label>
    <input type="text" id="backtest_name" name="backtest_name">
    <br><br>
    <label for="backtest_data">Backtest Data:</label>
    <input type="file" id="backtest_data" name="backtest_data" accept=".txt">
    <br><br>
    <label for="version">Version:</label>
    <input type="text" id="version" name="version" >
    <br><br>
    <input type="submit" value="Upload" name="submit" class="buttt">
</form>
        <h2>Payment check</h2>
<?php

    $sql = "SELECT * FROM payment WHERE status = 'pending'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>portnumber</th>
                    <th>start_date</th>
                    <th>end_date</th>
                    <th>status</th>
                    <th>total</th>
                    <th>Pay_slip</th>
                    <th>payment_date</th>
                    <th>payment_id</th>
                    <th>Action</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row['portnumber']."</td>
                    <td>".$row['start_date']."</td>
                    <td>".$row['end_date']."</td>
                    <td>".$row['status']."</td>
                    <td>".$row['total']."</td>
                    <td><a href='view_payslip_status.php?id=".$row['payment_id']."'>Click to open</a></td>
                    <td>".$row['payment_date']."</td>
                    <td>".$row['payment_id']."</td>
                    <td>
                        <form action='update_payment.php' method='post'>
                            <input type='hidden' name='payment_id' value='".$row['payment_id']."'>
                            <input type='submit' name='submit' value='ตกลง'>
                           
                        </form>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
?>





    </div>
    
  

</body>

</html>
