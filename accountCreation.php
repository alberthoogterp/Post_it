<?php
session_start();

$username_creation_error = "";
$password_creation_error = "";
$passwordverify_error = "";
if(isset($_SESSION["username_creation_error"])){
    $username_creation_error = $_SESSION["username_creation_error"];
}
if(isset($_SESSION["password_creation_error"])){
    $password_creation_error = $_SESSION["password_creation_error"];
}
if(isset($_SESSION["passwordverify_error"])){
    $passwordverify_error = $_SESSION["passwordverify_error"];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Creation</title>
</head>
<body>
<form action="accountCreationValidation.php" method="post">
        <p>
            <input type="text" name="username" placeholder="Username" autocomplete="off">
            <span><?php echo $username_creation_error;?></span>
        </p>
        <p>     
            <input type="password" name="password" placeholder="Password">
            <span><?php echo $password_creation_error;?></span>
        </p>
        <p>
            <input type="password" name="passwordverify" placeholder="Re-enter password">
            <span><?php echo $passwordverify_error;?></span>
        </p>
        <p>
            <input type="submit" value="Back" formaction="login.php">
            <input type="submit" value="Create account">
        </p>
    </form>
</body>
</html>