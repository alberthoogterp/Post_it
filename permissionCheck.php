<?php
session_start();

function checkLogin(){
    if($_SESSION["login_permission"] !== true){
        header('location: http://localhost/hacklab/post_it/login.php', true, 303);
    }
}

function checkPermission(permissionType $permission){
    if($_SESSION["todoitem_permission"] != $permission){
        header('location: http://localhost/hacklab/post_it/overview.php', true, 303);
    }
}

enum permissionType:string{
    case CREATE = "CREATE";
    case CHANGE = "CHANGE";
    case READ = "READ";
}
?>