<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/register-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
    <h2>Register</h2>
    <form action="insert-register.php" method="post">
        Port number <br><input type="text" name="portnumber" required class="inputbox"><br><br>
        Password <br> <input type="password" name="password" required class="inputbox" ><br><br>
        ID card <br> <input type="text" name="idcard" required class="inputbox"><br><br>
        Phone<br> <input type="text" name="phone" required class="inputbox"><br><br>
        <br><input type="submit" value="Register" class="loginbut">
    </form>
    <br>
    <a href="login.php">login</a>
    </div>

</body>
</html>
