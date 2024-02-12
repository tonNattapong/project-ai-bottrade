<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin-style.css">
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
            font-size: 20px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <ul>
        <li><a href=""><i class="fa-solid fa-bars" id="bar"></i></a>
            <ul class="dropdown">
                <li><a href="home.php">Home</a></li>
                <li><a href="port.php">Port</a></li>
                <li><a href="logout.php" class="logoutbut">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i> </a></li>
            </ul>
        </li>

    </ul>

    <h2>บิลของฉัน</h2>
    <div class="container">
        <table class='tablecontent'>
            <tr>
                <th>portnumber</th>
                <th>start</th>
                <th>end</th>
                <th>total</th>
                <th>total trade</th>
                <th>pay</th>
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
                        date >= NOW() - INTERVAL 7 DAY 
                    GROUP BY 
                        DATE(date)";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['portnumber'] . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['total'] . "</td>";
                    echo "<td>" . $row['total_trade'] . "</td>";
                    echo "<td><button onclick='showQR()'>pay</button></td>";
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
