<?php
require __DIR__ . '/dbFunction.php';
session_start();

if(($_SERVER["REQUEST_METHOD"] == "POST")){
    echo var_dump($_POST);
    $id = $_POST["id"];
    $table = $_POST["table"];
    if($table == "postitdata"){
        //Query("DELETE FROM postitdata WHERE postid = ?;",[$id]);
    }
    else if($table == "todoitems"){
        //Query("DELETE FROM postitdata WHERE postid = ?;",[$id]);
        //Query("DELETE FROM todoitems WHERE id = ?;", [$id]);
    }
}
//header('location: http://localhost/hacklab/post_it/overview.php', true, 303);
//exit;
?>