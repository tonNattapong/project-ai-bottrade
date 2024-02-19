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
    <link rel="stylesheet" href="css/payment.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
    <style>
        .overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .qr-image {
            max-width: 80%;
            max-height: 80%;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
            font-size: 40px;
            cursor: pointer;
            z-index: 1000;
            color:#E58E27;
            
        }
        .paybut {
        width: 60px;
        height: 25px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .paybut:hover {
        background-color: #45a049;
    }
    </style>
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

    <div class="container">
    <h2>บิลของฉัน</h2>
        <table class='tablecontent'>
            <tr>
                <th>Portnumber</th>
                <th>Start</th>
                <th>End</th>
                <th>Total</th>
                <th>Total Trade</th>
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
            portnumber = '$portnumber' 
            AND 
            date >= (SELECT MIN(date) FROM history WHERE portnumber = '$portnumber') 
            AND 
            date <= NOW() 
        GROUP BY 
            FLOOR(DATEDIFF(date, (SELECT MIN(date) FROM history WHERE portnumber = '$portnumber')) / 7)";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['portnumber'] . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['total'] . "</td>";
                    echo "<td>" . $row['total_trade'] . "</td>";
                    echo "<td><button onclick='showQR()' class='paybut'>pay</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No records found</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- QR Image Overlay -->
    <div class="overlay" id="qrOverlay" onclick="hideQR()">
        <span class="close-button" onclick="hideQR()">&times;</span>
        <img src="qr.png" alt="QR Code" class="qr-image">
    </div>

    <script>
        function showQR() {
            document.getElementById("qrOverlay").style.display = "flex";
        }

        function hideQR() {
            document.getElementById("qrOverlay").style.display = "none";
        }
    </script>
</body>

</html>
