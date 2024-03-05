<?php
include('connect.php'); 

if(isset($_GET['timeframe'])) {
    $timeframe = $_GET['timeframe'];
    $symbol = $_GET['symbol'];
    $sql = "SELECT name, timeframe,Symbol,id,backtest_data,download_count,backtest_data,version FROM template WHERE Symbol = '$symbol' AND timeframe = $timeframe ";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<tr>
                <th>Template</th>
                <th>name</th>
                <th>Symbol</th>
                <th>Time Frame</th>
                <th>Version</th>
                <th>Downloads count</th>
                <th>Backtest Result</th>
                <th>Download</th>
            </tr>";

        $templateCounter = 1; // Variable to store template order
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$templateCounter."</td>"; // Display order
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["Symbol"]."</td>";
            echo "<td>".$row["timeframe"]."</td>";
            echo "<td>".$row["version"]."</td>";
            echo "<td>".$row["download_count"]."</td>";
            echo "<td><a href='result/".$symbol . '_' .'M'. $timeframe . '_' . $row['version'].".htm'>Detail</a></td>";
            echo "<td><a href='download_count.php?symbol=".$symbol."&timeframe=".$timeframe."&id=".$row['id']."'><button class='button'>Download <i class='fa-solid fa-download'></i></button></a></td>";

            echo "</tr>";
            
            $templateCounter++; // Increment template order
            
        }
    } else {
        echo "<tr><td colspan='9'>No data found $symbol $timeframe</td></tr>";
    }

   
    $conn->close();
}
 

?>



