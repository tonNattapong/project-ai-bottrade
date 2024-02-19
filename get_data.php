<?php
include('connect.php'); 

if(isset($_GET['timeframe'])) {
    $timeframe = $_GET['timeframe'];
    $symbol = $_GET['symbol'];
    $sql = "SELECT Symbol, TimeFrame,tem_id, accurate, highperiod, midperiod, lowperiod, lotsizemul, numofround, download_count,backtest_data FROM template WHERE Symbol = '$symbol' AND TimeFrame = '$timeframe'";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<tr>
                <th>Template</th>
                <th>Symbol</th>
                <th>Time Frame</th>
                <th>Version</th>
                <th>accurate</th>
                <th>highperiod</th>
                <th>midperiod</th>
                <th>lowperiod</th>
                <th>lot size mul</th>
                <th>num of round</th>
                <th>Downloads count</th>
                <th>Backtest Result</th>
                <th>Download</th>
            </tr>";

        $templateCounter = 1; // Variable to store template order
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$templateCounter."</td>"; // Display order
            echo "<td>".$row["Symbol"]."</td>";
            echo "<td>".$row["TimeFrame"]."</td>";
            echo "<td>".$row["tem_id"]."</td>";
            echo "<td>".$row["accurate"]."</td>";
            echo "<td>".$row["highperiod"]."</td>";
            echo "<td>".$row["midperiod"]."</td>";
            echo "<td>".$row["lowperiod"]."</td>";
            echo "<td>".$row["lotsizemul"]."</td>";
            echo "<td>".$row["numofround"]."</td>";
            echo "<td>".$row["download_count"]."</td>";
            echo "<td><a href='result/".$symbol . '_' . $timeframe . '_' . $row['tem_id'].".htm'>Detail</a></td>";
            echo "<td><a href='download_count.php?symbol=".$symbol."&timeframe=".$timeframe."&tem_id=".$row['tem_id']."'><button>Download <i class='fa-solid fa-download'></i></button></a></td>";

            echo "</tr>";
            
            $templateCounter++; // Increment template order
            
        }
    } else {
        echo "<tr><td colspan='9'>No data found</td></tr>";
    }

   
    $conn->close();
}
 

?>
!


