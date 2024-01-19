<?php
require_once __DIR__ . '/dbFunction.php';
require_once __DIR__ . '/permissionCheck.php';
require_once __DIR__ . '/todolistCreation.php';
checkLogin();

$titleError = "";
$textError = "";
$dbError = "";
$queryResult = Query("SELECT * FROM todoitems JOIN users_todoitems ON todoitems.id = users_todoitems.todoitem_id  WHERE id IN (SELECT todoitem_id FROM users_todoitems WHERE user_id = (SELECT id FROM users where name = (?)))",[$_SESSION["username"]])["result"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo-list Overview</title>
</head>
<body>
    <span> <?php echo $dbError ?></span>
    <form action="index.php" method="get">
        <input type="submit" value="logout"/>
    </form>
    <form action="create.php" method="get">
        <input type="submit" value="Create new todo-item"/>
    </form>
    <?php
    while ($row = $queryResult -> fetch_assoc()){
        $idGET = $row["id"];
        $dateCreatedGET = $row["datecreated"];
        $permission = $row["permission"];
        $fileData = Query("SELECT * FROM todoitemsfiles WHERE todoitems_id = (?)",[$idGET])["result"];
        ?>
        <p>
            <span>Created on: <?php echo $dateCreatedGET?> </span>
        </p>
        <form action="update.php" method="POST">
        <?php
        createtodolist(todolistType::OVERVIEW, $row, $fileData);
        ?>
        <?php
        if($permission == "CREATOR" || $permission == "CHANGE"){
            ?>
            <input type="submit" value="Update" />
            <?php
        }
        ?>
        </form>
        <br/><br/>
    <?php
    };
    ?>
</body>
</html>