<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit']) && $_POST['submit'] == 'ตกลง') {
        // Update payment status to 'paid'
        $payment_id = $_POST['payment_id'];
        $update_payment_sql = "UPDATE payment SET status = 'paid' WHERE payment_id = '$payment_id'";
        if ($conn->query($update_payment_sql) === TRUE) {
            // Update history stats to 1 for the corresponding portnumber within the date range
            $payment_info_sql = "SELECT * FROM payment WHERE payment_id = '$payment_id'";
            $payment_info_result = $conn->query($payment_info_sql);
            if ($payment_info_result->num_rows > 0) {
                $payment_row = $payment_info_result->fetch_assoc();
                $start_date = $payment_row['start_date'];
                $end_date = $payment_row['end_date'];
                $portnumber = $payment_row['portnumber'];

                $update_history_sql = "UPDATE history SET stats = 1 WHERE portnumber = '$portnumber' AND date BETWEEN '$start_date' AND '$end_date'";
                if ($conn->query($update_history_sql) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "No payment found with the given ID";
            }
        } else {
            echo "Error updating payment: " . $conn->error;
        }
    } elseif (isset($_POST['submit']) && $_POST['submit'] == 'ยกเลิก') {
        // Update payment status to 'Not yet processed'
        $payment_id = $_POST['payment_id'];
        $update_payment_sql = "UPDATE payment SET status = 'Not yet processed' WHERE payment_id = '$payment_id'";
        if ($conn->query($update_payment_sql) === TRUE) {
            // Update history stats to 0 for the corresponding portnumber within the date range
            $payment_info_sql = "SELECT * FROM payment WHERE payment_id = '$payment_id'";
            $payment_info_result = $conn->query($payment_info_sql);
            if ($payment_info_result->num_rows > 0) {
                $payment_row = $payment_info_result->fetch_assoc();
                $start_date = $payment_row['start_date'];
                $end_date = $payment_row['end_date'];
                $portnumber = $payment_row['portnumber'];

                $update_history_sql = "UPDATE history SET stats = 0 WHERE portnumber = '$portnumber' AND date BETWEEN '$start_date' AND '$end_date'";
                if ($conn->query($update_history_sql) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "No payment found with the given ID";
            }
        } else {
            echo "Error updating payment: " . $conn->error;
        }
    }
}
?>