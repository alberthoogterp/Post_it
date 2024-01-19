<?php
require_once __DIR__ . '/dbFunction.php';
require_once __DIR__ . '/inputValidation.php';
require_once __DIR__ . '/permissionCheck.php';


function createtodolist(todolistType $type, array $postcopy=[], $filequery = null, array $filecopy = []){
    $titleError = "";
    $noteError = "";
    $dbError = "";
    $title = "";
    $noteText = "";
    $fileError = 0;
    $fileSizeError = "";
    $fileName = "";

    if(isset($postcopy["title"])){
        $title = $postcopy["title"];
    }
    $titleError = validate($title, inputType::TITLE);
    $noteText = null;
    if(isset($postcopy["note"])){
        $noteText = $postcopy["note"];
        $noteError = validate($noteText, inputType::TEXT);
    }
    $dateDue = null;
    if(isset($postcopy["datedue"])){
        $dateDue = $postcopy["datedue"];
    }
    $priority = false;
    if(isset($postcopy["priority"])){
        if($postcopy["priority"]){
            $priority = true;
        }
    }
    if(isset($_POST["filename"])){
        $fileName = $_POST["filename"];
    }
    $repeatAfter = null;
    if(isset($postcopy["repeatafter"])){
        $repeatAfterAmount = $postcopy["repeatafter"];
        if($type == todolistType::CREATE){
            $repeatAfterType = $postcopy["repeatAfterType"];
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
        else{
            $repeatAfter = $postcopy["repeatafter"];
            if($repeatAfter % 1440 == 0){
                $repeatAfterType = "days";
                $repeatAfter /= 1440;
            }
            else if($repeatAfter % 60 == 0){
                $repeatAfterType = "hours";
                $repeatAfter /= 60;
            }
            else{
                $repeatAfterType = "minutes";
            }
        }
    }
    if(isset($filecopy["bestand"])){
        $fileName = $filecopy["bestand"]["name"];
        $fileType = $filecopy["bestand"]["type"];
        $fileSize = $filecopy["bestand"]["size"];
        $fileError = $filecopy["bestand"]["error"];
        $fileSizeError = validate($fileSize, inputType::FILE);
    }
    
    ?>
        <p>
            <label for="title">titel:</label>
            <?php
            if($type == todolistType::OVERVIEW){
                ?>
                <input readonly type="text" name="title" id="title" value="<?php echo htmlentities($title, ENT_QUOTES, 'UTF-8')?>" autocomplete="off" />
                <?php
            }
            else{
                ?>
                <input type="text" name="title" id="title" value="<?php echo htmlentities($title, ENT_QUOTES, 'UTF-8')?>" autocomplete="off" />
                <span><?php echo $titleError; ?></span>
                <?php
            }
            ?>
        </p>
        <p>
            <label for="note">Tekst:</label>
        </p>
        <p>
            <?php
            if($type == todolistType::OVERVIEW){
                ?>
                <textarea readonly name="note" rows=4 cols=20 id="note"><?php echo htmlentities($noteText, ENT_QUOTES, 'UTF-8')?></textarea>
                <?php
            }
            else{
                ?>
                <textarea name="note" rows=4 cols=20 id="note"><?php echo htmlentities($noteText, ENT_QUOTES, 'UTF-8')?></textarea>
                <span><?php echo $noteError; ?></span>
                <?php
            }
            ?>
        </p>
        <p>
            <?php
            if($type == todolistType::CREATE){
                ?>
                <input type="text" value="<?php if(isset($_POST["filename"])){echo $_POST["filename"];}?>">
                <input id="fileInput" name="bestand" type="file"/>
                <span><?php echo $fileSizeError; ?></span>
                <?php
            }
            else if($type == todolistType::UPDATE){
                ?>
                <span><?php echo $fileName?></span>
                <input id="fileInput" name="bestand" type="file"/>
                <span><?php echo $fileSizeError; ?></span>
                <?php
            }
            else{
                ?>
                <div>
                <span><?php createFileContainer($filequery)?></span>
                </div>
                <?php
            }
            ?>
        </p>
        <p>
            <lable for="datedue">Due date:</lable>
            <?php
            if($type == todolistType::OVERVIEW){
                ?>
                <input readonly id="datedue" name="datedue" type="datetime-local" value="<?php echo $dateDue ?>"/>
                <?php
            }
            else{
                ?>
                <input id="datedue" name="datedue" type="datetime-local" value="<?php echo $dateDue ?>"/>
                <?php
            }
            ?>
        </p>
        <p>
            <label for="repeatAfter">Repeat after:</label>
            <?php
            if($type == todolistType::CREATE){
                ?>
                <input id="repeatAfter" name="repeatafter" type="number" min="0" oninput="validity.valid||(value='');"/>
                <select name="repeataftertype">
                    <option value="minutes">minutes</option>
                    <option value="hours">hours</option>
                    <option value="days">days</option>
                </select>
                <?php
            }
            else{
                ?>
                <input readonly id="repeatAfter" name="repeatafter" type="number" value="<?php echo $repeatAfter?>"/>
                <select name="repeataftertype">
                    <option selected="<?php echo $repeatAfterType?>">
                    <option value="minutes">minutes</option>
                    <option value="hours">hours</option>
                    <option value="days">days</option>
                </select>
                <?php
            }
            ?>
        </p>
        <p>
            <label for="priority">Priority:</label>
            <?php
            if($type == todolistType::CREATE){
                ?>
                <input id="priority" name="priority" type="checkbox"/>
                <?php
            }
            else if($type == todolistType::UPDATE){
                ?>
                <input id="priority" name="priority" type="checkbox" <?php if($priority){echo "checked";} ?>/>
                <?php
            }
            else{
                ?>
                <input disabled id="priority" type="checkbox" <?php if($priority){echo "checked";} ?>/>
                <input hidden id="priority" name="priority" type="checkbox" <?php if($priority){echo "checked";} ?>/>
                <?php
            }
            ?>
        </p>
        
    <?php
}

function createFileContainer($fileData){
    $imgTypes = ["image/jpeg", "image/png", "image/gif"];
    $audioTypes = ["audio/mpeg", "audio/ogg", "audio/wav"];
    $videoTypes = ["video/mp4", "video/ogg", "video/webm"];

    while($row = $fileData -> fetch_assoc()){
        $filenameGet = $row["filename"];
        $filetypeGet = $row["filetype"];
        $filedataGet = $row["filedata"];

        if(in_array($filetypeGet, $imgTypes, false)){
            ?>
            <p>
                <span><?php echo $filenameGet?> </span>
            </p>
            <img src="data:<?php echo $filetypeGet?>;base64,<?php echo base64_encode($filedataGet)?>" style="max-height: 400px; max-width: 400px; object-fit: contain"/>
            <?php
        }
        else if(in_array($filetypeGet, $audioTypes, false)){
            ?>
            <p>
                <span><?php echo $filenameGet?></span>
            </p>
            <audio controls>
            <source src="data:<?php echo $filetypeGet?>;base64,<?php echo base64_encode($filedataGet)?>" type="<?php echo $filetypeGet?>">
            audio element wordt niet ondersteunt
            </audio>
            <?php
        }
        else if(in_array($filetypeGet, $videoTypes, false)){
            ?>
            <p>
                <span><?php echo $filenameGet?></span>
            </p>
            <video controls style="max-height: 400px; max-width: 400px; object-fit: contain">
            <source src="data:<?php echo $filetypeGet?>;base64,<?php echo base64_encode($filedataGet)?>"/>
            video element wordt niet ondersteunt
            </video>
            <?php
        }
        ?>
        <input hidden type="text" name="filename" value="<?php echo $filenameGet?>">
        <?php
    }
}

enum todolistType:string{
    case CREATE = "CREATE";
    case UPDATE = "UPDATE";
    case OVERVIEW = "OVERVIEW";
} 
?>
