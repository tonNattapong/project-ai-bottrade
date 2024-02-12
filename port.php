
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/port-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
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
    <h1>การเติบโตของกระเป๋า</h1>
  <canvas id="myChart"></canvas>
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
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Balance',
                    data: balanceData,
                    borderColor: 'rgba(217, 217, 217, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
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
