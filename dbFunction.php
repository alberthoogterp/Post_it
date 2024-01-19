<?php
function Connect(){
    $dbFunctionError = "";
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "todolist";
    $mysqli = new mysqli($host,$user,$pass,$db);
    if ($mysqli -> connect_errno) {
        $dbFunctionError = "Failed to connect to MySQL: $mysqli -> connect_error";
        exit();
    }
    $returnArr["mysqli"] = $mysqli;
    $returnArr["dbFunctionError"] = $dbFunctionError;
    return $returnArr;
}

function Query($sql, $values = null){
    $dbFunctionError = "";
    try{
        $dbConnection = Connect()["mysqli"];
    }
    catch(Exception $e){
        echo "connection Error: ".$e;
    }
    try{
        $stmt = mysqli_prepare($dbConnection, $sql);
    }
    catch(Exception $e){
        echo "sql prepare error: ".$e;
    }
    $stmt -> execute($values);
    $result = $stmt -> get_result();
    if ($result === False) {
        $dbFunctionError = "Gelukt!";
    } else {
        $dbFunctionError = "dbFunctionError: " . $sql . "<br>" . $dbConnection->error;
    }
    $dbConnection -> close();
    $resultArray["result"] = $result;
    $resultArray["resultid"] = $stmt -> insert_id;
    $resultArray["dbFunctionError"] = $dbFunctionError;
    return $resultArray;
}

function checkUser(string $name):bool{
    $result = Query("SELECT name FROM users where name = (?)", [$name])["result"];
    $result = $result -> fetch_all();
    if($result  != []){
        return true;
    }
    else{
        return false;
    }
}
?>