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
</head>

<body>

        



    <h2> คำขอร้องสิทธิ์ </h2>
    <div class="container">
    <a href="logout.php" ><i class="fa-solid fa-arrow-right-from-bracket" style="color: #FFD43B;"></i> </a>
    <table class='tablecontent'>
        <tr>
            <th>portnumber</th>
            <th>id card</th>
            <th>phone</th>
            <th>permission</th>
            <th>ALLOW</th>
        </tr>
        <?php
        include('connect.php');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM infouser";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['permission'] != "ALLOW") {
                    echo "<tr>
                            <td>" . $row['portnumber'] . "</td>
                            <td>" . $row['idcard'] . "</td>
                            <td>" . $row['phone'] . "</td> 
                            <td>" . $row['permission'] . "</td>
                            <td>
                                <form action='allow.php' method='post'>
                                    <input type='hidden' name='portnumber' value='" . $row['portnumber'] . "'>
                                    <input type='submit' name='allow' value='ALLOW' class = 'allowbut'>
                                </form>
                            </td>
                        </tr>";
                }
            }
        } else {
            echo "<tr><td colspan='5'>0 results</td></tr>";
        }

        
        ?>
    </table>

    </div>
    
  

</body>

</html>
