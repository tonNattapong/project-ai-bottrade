<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300&family=Prompt:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">

    <h2>Login</h2>
        <form action="login-check.php" method="post">
            Port number <br> <input type="text" name="portnumber" class="inputbox" required><br><br>
            Password <br> <input type="password" name="password" class="inputbox" required><br><br>
            <input type="submit" value="Login" class="loginbut">
        </form>
<br>
    <a href="register.php">register</a>

    </div>
    
</body>
</html>
