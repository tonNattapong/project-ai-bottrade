<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=IBM+Plex+Sans+Thai&family=Prompt&display=swap" rel="stylesheet">
    </head>
<body>
    
    <div class="container">
    <img src="img/logo.png" alt="">
    <h2>Login</h2>
   
   
        <form action="login-check.php" method="post">
        
            <p>Port number</p> <br> <input type="text" name="portnumber" class="inputbox" required><br><br>
            <p>Password</p> <br> <input type="password" name="password" class="inputbox" required><br><br>
            <input type="submit" value="Login" class="loginbut">
        </form>
<br>
    <a href="register.php">Register</a>

    </div>
    
</body>


</html>
