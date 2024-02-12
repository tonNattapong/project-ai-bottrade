<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['allow'])) {
    $portnumber = $_POST['portnumber'];

    // Update the permission in the database
    $update_sql = "UPDATE infouser SET permission='ALLOW' WHERE portnumber='$portnumber'";
    if ($conn->query($update_sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();



?>
