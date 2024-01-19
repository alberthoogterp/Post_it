<?php
require __DIR__ . '/dbFunction.php';
session_start();
session_unset();

$username = $_POST["username"];
$password = $_POST["password"];

if(checkUser($username)){
    $stored_password = Query("SELECT password FROM users where name = (?)", [$username])["result"] -> fetch_assoc();
    $stored_password = $stored_password["password"];
    if(password_verify($password, $stored_password)){
        $_SESSION["login_permission"] = true;
        $_SESSION["username"] = $username;
        header('location: http://localhost/hacklab/post_it/overview.php', true, 303);
        exit();
    }
    $_SESSION["login_error"] = "incorrect login";
}
else{
    $_SESSION["login_error"] = "Incorrect login";
    header('location: http://localhost/hacklab/post_it/login.php', true, 303);
    exit();
}
?>