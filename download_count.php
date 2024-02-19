<?php
include('connect.php'); 

if(isset($_GET['symbol']) && isset($_GET['timeframe']) && isset($_GET['tem_id'])) {
    $symbol = $_GET['symbol'];
    $timeframe = $_GET['timeframe'];
    $tem_id = $_GET['tem_id'];

    // Update download count in the database
    $sql_update = "UPDATE template SET download_count = download_count + 1 WHERE Symbol = '$symbol' AND TimeFrame = '$timeframe' AND tem_id = '$tem_id'";
    $conn->query($sql_update);

    // Perform download process here (e.g., generate file, zip files, etc.)
    // Then send file to user
    // For example, if you want to force download a file:
    $file_path = "result/EURUSD_M15_1.zip";
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    readfile($file_path);

    exit;
} else {
    // Invalid request, handle accordingly
    // For example, redirect back to homepage
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$conn->close();
?>