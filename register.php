<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/register-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=IBM+Plex+Sans+Thai&family=Prompt&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="img/logo.png" alt="">
    <h2>Register</h2>
    <form action="insert-register.php" method="post">
        <p>Port number</p> <br><input type="text" name="portnumber" required class="inputbox"><br><br>
        <p>Password </p> <br> <input type="password" name="password" required class="inputbox" ><br><br>
        <p>ID card </p><br> <input type="text" name="idcard" required class="inputbox"><br><br>
        <p>Phone</p><br> <input type="text" name="phone" required class="inputbox"><br><br>
        <br><input type="submit" value="Register" class="loginbut">
    </form>
    <br>
    <a href="login.php">Login</a>
    </div>

</body>
</html>
