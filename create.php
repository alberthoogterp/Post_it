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
$fileSizeError = "";
$fileName = "";

if(($_SERVER["REQUEST_METHOD"] == "POST")){
    $dbError = "";
    $title = $_POST["title"];
    $titleError = validate($title, inputType::TITLE);
    $noteText = null;
    if(isset($_POST["note"])){
        $noteText = $_POST["note"];
        $noteError = validate($noteText, inputType::TEXT);
    }
    $dateDue = null;
    if(isset($_POST["datedue"])){
        $dateDue = $_POST["datedue"];
    }
    $priority = false;
    if(isset($_POST["priority"])){
        $priority = true;
    }
    $repeatAfter = null;
    if(isset($_POST["repeatAfterAmount"])){
        $repeatAfterAmount = $_POST["repeatAfterAmount"];
        $repeatAfterType = $_POST["repeatAfterType"];
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
    if(!$titleError && !$noteError && !$fileSizeError && ($fileError == 0 || $fileError == 4)){
        $resultId = Query("INSERT INTO todoitems (title, note, datedue, priority, repeatafter) VALUES (?,?,?,?,?);",[$title, $noteText, $dateDue, $priority, $repeatAfter])["resultid"];
        $userId = Query("SELECT id FROM users WHERE name = (?)",[$_SESSION["username"]])["result"] -> fetch_assoc();
        $userId = $userId["id"];
        Query("INSERT INTO users_todoitems (user_id, todoitem_id, permission) VALUES (?,?,?)",[$userId, $resultId, "CREATOR"]);
        $dbError = $dbFunctionError;
        $title = "";
        $noteText = "";
        if($fileError == 0){
            $fileData = file_get_contents($_FILES["bestand"]["tmp_name"]);
            Query("INSERT INTO todoitemsfiles (todoitems_id, filename, filetype, filedata) VALUES (?,?,?,?)",[$resultId, $fileName, $fileType, $fileData]);

        }  
        header('location: http://localhost/hacklab/post_it/overview.php', true, 303);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta title="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo-item Creator</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <?php
        createtodolist(todolistType::CREATE);
        ?>
        <p>
            <input type="submit" value="Create"/>
            <span><?php echo $dbError; ?></span>
        </p>
    </form>
    <form action="overview.php" method="get">
        <input type="submit" value="cancel"/>
    </form>
</body>
</html>