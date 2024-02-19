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
            $status_class = "status yellow";
            $status_text = "ค้างชำระ";
        }
    }
} elseif ($permission === 'pending' || $permission === 'not allow') {
    $status_class = "nostatus";
    $status_text = "ไม่มีสิทธิ์เข้าใช้งาน";
} else {
    $status_class = "nostatus";
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

<link rel="stylesheet" href="css/permission-status-style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
<div class="sidebar">
        <ul>
            <li><a href="#"><img src="img/logo.png" alt=""></a></li>
            <li><a href="homepage.php"><i class="fa-solid fa-house"></i><span>Home</span></a></li>
            <li><a href="port.php"><i class="fa-solid fa-wallet"></i><span>Port</span></a></li>
            <li><a href="status.php"><i class="fa-solid fa-check"></i></i><span>Status</span></a></li>
            <li><a href="download.php"><i class="fa-solid fa-download"><span>Dowload</span></i></a></li>
            <li><a href="payment.php"><i class="fa-solid fa-file-invoice-dollar"></i><span>My Bill</span></i></a></li>
        </ul>
    </div>

    <nav>
        <div class="account-info">
            <div class="logoutbut">
                <a href="logout.php" ><i class="fa-solid fa-arrow-right-from-bracket"></i> </a>
            </div>
            <div class="profile-pic">
                <img src="img/acc.PNG" alt="Profile picture">
            </div>
            
            <div class="user-details">
    <p class="port-number">Port: <?php echo $portnumber; ?></p>
    <?php
    // ตรวจสอบค่าของ $permission เพื่อแสดงข้อความและสีตามเงื่อนไข
    if ($permission == "ALLOW") {
        echo '<p class="status" style="color: green;">มีสิทธิเข้าใช้งาน</p>';
    } elseif ($permission == "pending") {
        echo '<p class="status" style="color: #E1A12B;">รออนุมัติ</p>';
    } elseif ($permission == "not allow") {
        echo '<p class="status" style="color: red;">ไม่มีสิทธิเข้าใช้งาน</p>';
    } else {
        echo '<p class="status" style="color: black;">ไม่ทราบสถานะ</p>';
    }
    ?>
</div>
    
    </nav>

    
    <?php if ($permission === 'ALLOW'): ?>
        <div class="container">
    <div class="status-box">
        <div class="status <?php echo $status_class; ?>">
            <h2>สถานะบอท</h2>
            <h1 id="status"><?php echo $status_text; ?></h1>
        </div>
        <div class="action">
            <a href="payment.php" class="bill">บิลของฉัน</a>
           
        </div>
    </div>
</div>

  
<?php elseif ($permission === 'pending'): ?>
        <h2 class="center">รอการอนุมัติจากแอดมิน</h2>
    <?php else: ?>
        <div class="container">
            <h2>ขอสิทธิการเข้าใช้งาน</h2>
            <form action="request.php" method="post">
                <input type="submit" value="ส่งคำขอ" class="submitbut">
            </form>
        </div>
    <?php endif; ?>
  

 

        
</body>
</html>
