<?php
session_start();

// ตรวจสอบว่ามี Session portnumber หรือไม่
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}

// เชื่อมต่อกับฐานข้อมูล
include('connect.php');
if (isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_GET['total_trade']) && isset($_GET['total'])) {
    // รับค่า $start_date, $end_date, $total_trade, $total, และ $status จาก URL parameters
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $total_trade = $_GET['total_trade'];
    $total = $_GET['total'];
    
} else {
    // หากไม่มีค่าที่ส่งมา
    echo "ไม่พบข้อมูล Invoice";
    exit(); // จบการทำงาน
}
// ตรวจสอบว่ามีการส่งพารามิเตอร์ portnumber ผ่าน URL หรือไม่
if (isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_GET['total_trade']) && isset($_GET['total'])) {
    // รับค่าจาก URL parameters
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $total_trade = $_GET['total_trade'];
    $total = $_GET['total'];

    // ดึงอัตราแลกเปลี่ยน USD เป็น THB โดยใช้ FreeCurrencyAPI
    $access_key = 'fca_live_w6XVNeM8USiLK8Sg5YEcqE3JBciU6bO1ijZX6vUP'; // ระบุ Access Key ของคุณที่ได้รับจาก FreeCurrencyAPI
    $url = "https://api.freecurrencyapi.com/v1/latest?apikey=$access_key&currencies=THB";
    $response = file_get_contents($url);

    if ($response !== false) {
        $data = json_decode($response, true);

        if (isset($data['data']['THB'])) {
            $usd_to_thb_rate = $data['data']['THB'];

            // แปลงยอดรวมเงิน USD เป็น THB โดยการคูณอัตราแลกเปลี่ยน
            $total_in_thb = $total * $usd_to_thb_rate ;
            $total_profit = number_format($total * $usd_to_thb_rate*0.05,2);
            // กำหนดค่าของตัวแปร total_unprofit และ unprofit
         
        } else {
            echo "ไม่พบข้อมูลอัตราแลกเปลี่ยน USD เป็น THB";
            exit(); // จบการทำงาน
        }
    } else {
        echo "ไม่สามารถเชื่อมต่อกับ FreeCurrencyAPI ได้";
        exit(); // จบการทำงาน
    }
} else {
    // ถ้าไม่มีพารามิเตอร์ที่ส่งมาให้
    echo "ไม่พบข้อมูล Invoice";
    exit(); // จบการทำงาน
}
?>

<?php
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // รับค่า start_date และ end_date จาก URL parameters
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    
    // คำสั่ง SQL สำหรับดึงข้อมูลจำนวนเงินที่มีค่าติดลบหรือเท่ากับ 0 (unprofit) จากตาราง history
    $sql = "SELECT 
                SUM(CASE WHEN money <= 0 THEN money ELSE 0 END) AS unprofit
            FROM 
                history 
            WHERE 
                portnumber = '" . $_SESSION["portnumber"] . "' 
                AND 
                date BETWEEN '$start_date' AND '$end_date'";
    
    // ดำเนินการ query กับฐานข้อมูล
    $result = $conn->query($sql);
    
    if ($result !== false && $result->num_rows > 0) {
        // วนลูปเพื่อดึงข้อมูลจากการ query
        while ($row = $result->fetch_assoc()) {
            $unprofit = $row["unprofit"]; // เก็บค่า unprofit ไว้ในตัวแปร $unprofit
        }
    } else {
        // ถ้าไม่พบข้อมูล
        echo "ไม่พบข้อมูลการซื้อขายที่เสียเงิน (unprofit)";
    }
} else {
    // ถ้าไม่มีพารามิเตอร์ start_date หรือ end_date ที่ส่งมา
    echo "กรุณาระบุวันที่เริ่มต้นและสิ้นสุดใน URL parameters";
}

$sql = "SELECT 
            COUNT(*) AS total_unprofit
        FROM 
            history 
        WHERE 
            portnumber = '" . $_SESSION["portnumber"] . "' 
            AND 
            money <= 0 
            AND 
            date BETWEEN '$start_date' AND '$end_date'";

// ดำเนินการ query กับฐานข้อมูล
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    // วนลูปเพื่อดึงข้อมูลจากการ query
    while ($row = $result->fetch_assoc()) {
        $total_unprofit = $row["total_unprofit"]; // เก็บค่า total_unprofit ไว้ในตัวแปร $total_unprofit
    }
} else {
    // ถ้าไม่พบข้อมูล
    echo "ไม่พบข้อมูลการซื้อขายที่เสียเงิน (unprofit)";
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
   
    <title>Invoice</title>
    <link rel="stylesheet" href="css/invoice-style.css">
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
           <li><a href="status.php"><span> <i class="fa-solid fa-square-check" style="color: #52555A;"></i></span></a></li>
           <li> <a href="homepage.php"><span> <i class="fa-solid fa-house" style="color: #52555A"></i></span></a></li>
           <li><a href="download.php"><span class="dl"><i class="fa-solid fa-circle-down" style="color: #52555A;"></i></span></i></a></li>
           <li><a href="payment.php"><span><i class="fa-solid fa-file-invoice-dollar" style="color: #d17842;;"></i></span></i></a></li>
          </ul>

          
         
  </div>
 
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
   

           
           
    <div class="invoice-container">
        <h2>Invoice</h2>
        <table class="invoice-table">
           
                <tr>
                    <th style="color: #d17842;">วันที่เริ่มต้น</th>
                    <td style="color: #d17842;">วันที่สิ้นสุด</td>
                    
                </tr>
                <tr>
                <th ><?php echo $start_date; ?></th>
                <td ><?php echo $end_date; ?></td>
                
                <br>
                
            
            <tr>
               
                <th>รวมจำนวนการซื้อขายที่ได้ขาดทุน</th>
                <td>ขาดทุนทั้งหมด <?php echo $total_unprofit; ?> ครั้ง รวม <?php echo number_format($unprofit  * $usd_to_thb_rate,2); ?> บาท</td>
</td>
            </tr>
           
           
             <tr>
                <th>รวมจำนวนการซื้อขายที่ได้กำไร</th>
                <td>ได้กำไรทั้งหมด <?php echo $total_trade ; ?> ครั้ง รวม <?php echo number_format($total * $usd_to_thb_rate,2); ?> บาท</td>
            </tr>
           
            <tr>
                <th> ยอดที่ต้องชำระ 5 %</th>
                <td><?php echo number_format($total_in_thb*0.05, 2); ?> บาท </td>
            </tr>
        </table>

        <form action="" method="post" enctype="multipart/form-data" >
            <input type="file" name="image" accept="image/*">
            <button type="submit">Confirm Payment</button>
        </form>
    </div>
        <?php
    
// เชื่อมต่อกับฐานข้อมูล
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    // ตรวจสอบว่ามีการอัพโหลดไฟล์รูปหรือไม่
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // กำหนดค่าตัวแปรที่จำเป็นสำหรับการบันทึกข้อมูลลงในฐานข้อมูล
        
        $total_profit_float = (float) $total_profit;

        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $payment_date = date('Y-m-d H:i:s'); // เวลาปัจจุบัน
        $status = 'pending'; // สถานะ 'pending'

        // ดำเนินการอัพโหลดไฟล์รูปและเก็บข้อมูลลงในตาราง payment
        $image_data = file_get_contents($_FILES['image']['tmp_name']);
        $image_base64 = base64_encode($image_data);

        // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลในตาราง payment
        $sql = "INSERT INTO payment (portnumber,total, start_date, end_date, Pay_slip, payment_date, status) 
                VALUES ('" . $_SESSION['portnumber'] . "','$total_profit', '$start_date', '$end_date', '$image_base64', '$payment_date', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location.href = 'payment.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error . "');</script>";
        }

    } else {
        echo "เกิดข้อผิดพลาดในการอัพโหลดไฟล์รูป";
    }
}
?>

    </form>
    
    </div>



</body>
</html>
