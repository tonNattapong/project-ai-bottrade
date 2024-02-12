<?php
session_start();
if (!isset($_SESSION["portnumber"])) {
    header("location: login.php");
    exit();
}

include('connect.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$portnumber = $_SESSION["portnumber"];

// Check if the user has "ALLOW" permission
$permission_query = "SELECT permission FROM infouser WHERE portnumber = '$portnumber'";
$permission_result = $conn->query($permission_query);

if ($permission_result->num_rows > 0) {
    $row = $permission_result->fetch_assoc();
    $user_permission = $row['permission'];

    if ($user_permission !== 'ALLOW') {
        echo "No Permmission";
        header("location: home.php");
        exit();
    }
} else {
    echo "No Permmission";    
    header("location: home.php");
    exit();
}

// Rest of your code to display the content for users with "ALLOW" permission
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
</head>

<body>
    <br>
    
<a href="https://www.analyticsinsight.net/wp-content/uploads/2021/09/Want-to-trade-automatic-See-Top-10-Crypto-Trading-Bots-in-2021.jpg" download="bot.jpg">
  Download Bot
</a>
<br>
    <a href="logout.php">Logout </a>
   

</body>

</html>
