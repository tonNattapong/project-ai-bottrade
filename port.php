
<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit(); // ต้องใส่ exit เพื่อหยุดการทำงานทันทีหลังจาก redirect
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/port-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
   

           
          
   
    <div class="content">
    <canvas id="myChart" class="chart-canvas"></canvas>
</div>

    
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        // ดึงข้อมูลจาก PHP มาใช้ใน JavaScript
        var labels = <?php echo json_encode(array_column($chartData, 'profit_date')); ?>;
        var equityData = <?php echo json_encode(array_column($chartData, 'equity')); ?>;
        var balanceData = <?php echo json_encode(array_column($chartData, 'balance')); ?>;

        // สร้างแผนภูมิ
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Equity',
            data: equityData,
            borderColor: 'rgb(229, 142, 39)',
            backgroundColor: 'rgb(229, 142, 39)',
            tension: 0.1,
        }, {
            label: 'Balance',
            data: balanceData,
            borderColor: 'white',
            backgroundColor: 'white',
            tension: 0.1,
        }]
    },
    options: {
        scales: {
            x: {
                ticks: {
                    color: 'white' // เปลี่ยนสีของเลขบนแกน x เป็นขาว
                }
            },
            y: {
                ticks: {
                    color: 'white' // เปลี่ยนสีของเลขบนแกน y เป็นขาว
                },
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: 'white' // เปลี่ยนสีของตัวอักษรในส่วนของตำแหน่งตัวอย่างเป็นขาว
                }
            }
        }
    }
});
    });
</script>
<!-- <div class="container">
    <table class='tablecontent'>
        <tr>
            <th>portnumber</th>
            <th>profit_date</th>
            <th>equity</th>
            <th>balance</th>
        </tr>
        <?php
        // ดึงข้อมูลจากตาราง profit
        $sql = "SELECT portnumber, profit_date, equity, balance FROM profit";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // วนลูปเพื่อแสดงข้อมูลในตาราง
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['portnumber'] . "</td>
                        <td>" . $row['profit_date'] . "</td>
                        <td>" . $row['equity'] . "</td>
                        <td>" . $row['balance'] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>ไม่พบข้อมูล</td></tr>";
        }
        ?>
    </table>
</div> -->

</body>
</html>