<?php
require_once __DIR__ . '/dbFunction.php';
require_once __DIR__ . '/inputValidation.php';
require_once __DIR__ . '/permissionCheck.php';
require_once __DIR__ . '/todolistCreation.php';
checkLogin();

$titleError = "";
$noteError = "";
$dbError = "";
$title = "";
$noteText = "";
$fileError = 0;
$dateDue = null;
$priority = false;
$repeatAfter = null;
$fileSizeError = "";
$fileName = "";
var_dump($_POST);

$dbError = "";
if(isset($_POST["title"])){
    $title = $_POST["title"];
    $titleError = validate($title, inputType::TITLE);
}
if(isset($_POST["note"])){
    $noteText = $_POST["note"];
    $noteError = validate($noteText, inputType::TEXT);
}
if(isset($_POST["datedue"])){
    $dateDue = $_POST["datedue"];
}
if(isset($_POST["priority"])){
    $priority = true;
}
if(isset($_POST["repeatafter"])){
    $repeatAfterAmount = $_POST["repeatafter"];
    $repeatAfterType = $_POST["repeataftertype"];
    $multiplier = 1;
    if($repeatAfterType == "minutes"){
        $multiplier = 1;
    }
    else if($repeatAfterType == "hours"){
        $multiplier = 60;
    }
    else if($repeatAfterType == "days"){
        $multiplier = 1440;
    }
    $repeatAfter = $multiplier * (int)$repeatAfterAmount;
}
if(isset($_FILES["bestand"])){
    $fileName = $_FILES["bestand"]["name"];
    $fileType = $_FILES["bestand"]["type"];
    $fileSize = $_FILES["bestand"]["size"];
    $fileError = $_FILES["bestand"]["error"];
    $fileSizeError = validate($fileSize, inputType::FILE);
}
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"]) && !$titleError && !$noteError && !$fileSizeError && ($fileError == 0 || $fileError == 4)){
    
    
    //header('location: http://localhost/hacklab/post_it/overview.php', true, 303);
    //exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo-item Update</title>
</head>
<body>
    <form action="" method="POST">
    <?php
        createtodolist(todolistType::UPDATE, $_POST);
    ?>
        <input type="submit"  name="update" value="Update"/>
        <input type="submit" formaction="overview.php" value="cancel"/>
    </form>
</body>
</html>