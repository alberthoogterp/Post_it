<?php
require __DIR__ . '/dbFunction.php';
session_start();
session_unset();

$username = $_POST["username"];
$password = $_POST["password"];
$passwordverify = $_POST["passwordverify"];

if(checkUser($username)){
    $_SESSION["username_creation_error"] = "This username already exists";
    header('location: http://localhost/hacklab/post_it/accountCreation.php', true, 303);
    exit();
}
else{
    if(checkInput($username, $password, $passwordverify)){
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        Query("INSERT INTO users (name, password) VALUES (?,?);", [$username, $hashedpassword]);
        header('location: http://localhost/hacklab/post_it/succesfullAccountCreation.php', true, 303);
        exit();
    }
    else{
        header('location: http://localhost/hacklab/post_it/accountCreation.php', true, 303);
        exit();
    }
    
}

function checkInput($username, $password, $passwordverify){
    $success = true;
    //username checks
    if(strLen($username) < 1){
        $_SESSION["username_creation_error"] = "Username too short";
        $success = false;
    }
    else if(strLen($username) > 20){
        $_SESSION["username_creation_error"] = "Username too long";
        $success = false;
    }
    //password checks
    if(strLen($password) < 8){
        $_SESSION["password_creation_error"] = "Password too short";
        $success = false;
    }
    else if(!preg_match('/[!@#$%^&*()_+{}[\]:;<>?\/\\.,]/', $password)){
        $_SESSION["password_creation_error"] = "Password should contain atleast one special character";
        $success = false;
    }
    else if(!preg_match('/[A-Z]/', $password)){
        $_SESSION["password_creation_error"] = "Password should contain atleast one uppercase letter";
        $success = false;
    }
    //second password checks
    if($password != $passwordverify){
        $_SESSION["passwordverify_error"] = "Incorrect password, make sure you input the same password";
        $success = false;
    }
    return $success;
}
?>