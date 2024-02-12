<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}

include('connect.php');

// เชื่อมต่อฐานข้อมูล
$conn = mysqli_connect($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// คำสั่ง SQL เพื่อตรวจสอบสถานะของบอท
$sql_bot = "SELECT stats FROM history WHERE portnumber = '{$_SESSION["portnumber"]}'";
$result_bot = mysqli_query($conn, $sql_bot);

// ตรวจสอบสถานะของบอท
if (mysqli_num_rows($result_bot) > 0) {
    $row_bot = mysqli_fetch_assoc($result_bot);
    $stats_bot = $row_bot["stats"];
    
    if ($stats_bot == 1) {
        // คำสั่ง SQL เพื่อตรวจสอบสิทธิ์ของผู้ใช้
        $sql_user = "SELECT role FROM infouser WHERE role = 'ALLOW' AND portnumber = '{$_SESSION["portnumber"]}'";
        $result_user = mysqli_query($conn, $sql_user);

        // ตรวจสอบสิทธิ์ของผู้ใช้
        if (mysqli_num_rows($result_user) > 0) {
            $status_class = "status green";
            $status_text = "ใช้งานได้";
        } else {
            $status_class = "nostatus";
            $status_text = "ไม่มีสิทธิ์เข้าใช้งาน";
        }
    } else {
        $status_class = "yellow";
        $status_text = "ค้างชำระ";
    }
} else {
    // ตรวจสอบสิทธิ์ของผู้ใช้
    $sql_user = "SELECT role FROM infouser WHERE (role = 'pending' OR role = 'not allow') AND portnumber = '{$_SESSION["portnumber"]}'";
    $result_user = mysqli_query($conn, $sql_user);

    if (mysqli_num_rows($result_user) > 0) {
        $status_class = "nostatus";
        $status_text = "ไม่มีสิทธิ์เข้าใช้งาน";
    } else {
        $sql_user_allowed = "SELECT role FROM infouser WHERE role = 'ALLOW' AND portnumber = '{$_SESSION["portnumber"]}'";
        $result_user_allowed = mysqli_query($conn, $sql_user_allowed);

        if (mysqli_num_rows($result_user_allowed) > 0) {
            $status_class = "status green";
            $status_text = "ใช้งานได้";
        } else {
            $status_class = "nostatus";
            $status_text = "ไม่มีสิทธิ์เข้าใช้งาน";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/home-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
<nav class="menubar">
    <ul>
        <li><a href=""><i class="fa-solid fa-bars" id="bar"></i></a>
            <ul class="dropdown">
                <li><a href="home.php">Home</a></li>
                <li><a href="port.php">Port</a></li>
                <li><a href="permission.php">Permission</a></li>
                <li><a href="logout.php" class="logoutbut">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i> </a></li>
            </ul>
        </li>
    </ul>
</nav>

<div class="container">
    <div class="status-box">
        <div class="status <?php echo $status_class; ?>">
            <h2>สถานะบอท</h2>
            <h1><?php echo $status_text; ?></h1>
        </div>
        <div class="action">
            <a href="payment.php">บิลของฉัน</a>
            <a href="https://www.analyticsinsight.net/wp-content/uploads/2021/09/Want-to-trade-automatic-See-Top-10-Crypto-Trading-Bots-in-2021.jpg" download="bot.jpg">
                ดาวน์โหลด
            </a>
        </div>
    </div>
</div>

</body>
</html>
