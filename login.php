<?php
require __DIR__ . "/inputValidation.php";
session_start();

$login_error = "";
if(isset($_SESSION["login_error"])){
    $login_error = $_SESSION["login_error"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>postit login</title>
</head>
<body>
    <form action="loginValidation.php" method="post">
        <p>
            <input type="text" name="username" placeholder="Username" autocomplete="off">
        </p>
        <p>     
            <input type="password" name="password" placeholder="Password">
        </p>
        <p>
            <input type="submit" value="login">
            <span><?php echo $login_error;?></span>
        </p>
    </form>
    <form action="accountCreation.php" method="post">
        <p>
            Don't have an account yet?
            <input type="submit" value="Create account">
        </p>
    </form>
</body>
</html>