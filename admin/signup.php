<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit();
}
//header('Location: index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin signup</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,400;1,600&family=Ubuntu&display=swap');

* {
    margin: 
    padding: 0;
    box-sizing: border-box;
    font-family: 'Ubuntu', sans-serif;
}
body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: rgb(14, 13, 13);
}
.auth {
    position: relative;
    width: 340px;
    height: 650px;
    background: black;
    border-radius: 10px;
    overflow: hidden;
    margin: auto 20px;
    overflow: hidden;
}
@media screen and (min-width:767px){
  .auth{
    width: 480px;
  }
}
.auth::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 340px;
    height: 500px;
    background: linear-gradient(0deg, yellow, transparent, transparent);
    transform-origin:bottom right ;
    animation: animate 6s linear infinite;
}
.auth::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 300px;
    height: 500px;
    background: linear-gradient(0deg, yellow, transparent, transparent);
    transform-origin:bottom right ;
    animation: animate 6s linear infinite;
    animation-delay: 3s;
}
@keyframes animate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(-360deg);
    }
}
.login {
    position: absolute;
    inset: 2px;
    border-radius: 8px;
    background: black;
    z-index: 10;
    color: yellow;
    padding: 40px 10px;
    display: flex;
    flex-direction: column;
}
.login h2 {
    font-weight: 500;
    text-align: center;
    letter-spacing: .1em;
}
.InputBox {
    position: relative;
    width: 100%;
    margin-top: 20px;
}
.InputBox input {
    position: relative;
    width: 100%;
    padding: 20px 10px 10px;
    background: transparent;
    border: none;
    outline: none;
    color: rgb(46, 46, 44);
    font-size: 1em;
    letter-spacing: .05em;
    z-index: 10;
}
.InputBox span {
    position: absolute;
    left: 0;
    padding: 20px 10px 10px;
    font-size: 1em;
    color: rgb(168, 178, 187);
    pointer-events: none;
    letter-spacing: .05em;
    transition: .5s;
}
.InputBox input:not(:placeholder-shown) ~ span,
.InputBox input:focus ~ span
{
    color: yellow;
    transform: translateX(-10px) translateY(-28px);
    font-size: .75em;
}
.InputBox i {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: yellow;
    border-radius: 4px;
    transition: .5s;
    pointer-events: none;
    z-index: 9;
}
.InputBox input:not(:placeholder-shown) ~ i,
.InputBox input:focus ~ i
{
    height: 38px;
}
.error {
    display: flex;
    justify-content: center;
    margin: 20px;
    color: red;
}

input[type="submit"]
{
    border: none;
    outline: none;
    background: yellow;
    padding: 11px 25px;
    width: 100px;
    margin-top: 10px;
    margin: auto;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
}
input[type="submit"]:active
{
    opacity: .8;
}
    </style>
</head>
<body>
    <div class="auth">
        <form action="authenticate.php" method="post" class="login">
            <h2>ADMIN SIGN UP</h2>
                <div class="InputBox">
                    <input type="text" name="name" placeholder=" " value="<?php echo $_GET['name']?>" required="required">
                    <span>Name</span>
                    <i></i>
                </div>
                <div class="InputBox">
                    <input type="text" name="username" value="<?php echo $_GET['username']?>" placeholder=" " required="required">
                    <span>Username</span>
                    <i></i>
                </div>
                <span style="margin-top:5px;font-size:14px;">Username must be 3-20 characters, allowing only letters, numbers, _, -, or .</span>
                <div class="InputBox">
                    <input type="email" name="email" value="<?php echo $_GET['email']?>" placeholder=" " required="required">
                    <span>Email</span>
                    <i></i>
                </div>
                <div class="InputBox">
                    <input type="password" name="password" placeholder=" " required="required">
                    <span>Password</span>
                    <i></i>
                </div>
                <div class="error">
                  <?php 
                  if (isset($_GET['error'])) {
                    $error=$_GET['error'];
                    echo($error);
                  }
                  ?>
                </div>
                <input name="signup" type="submit" value="Sign up">
                <a href="login.php" style="color:yellow;text-decoration:none;margin:20px auto;"> Go to Login </a>
        </form>
        
    </div>
</body>
</html>