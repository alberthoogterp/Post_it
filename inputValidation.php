<?php
function validate($input, inputType $type){
    $maxFileSize = 16777215;
    $error = "";
    if($type == inputType::TITLE){
        if(strlen($input) < 1){
            echo $input;
            $error = "$input is een te korte titel";
        }
        else if(strlen($input) > 255){
            $error = "titel is te lang";
        }
    }
    else if($type == inputType::TEXT){
        
    }
    else if($type == inputType::FILE){
        if($input > $maxFileSize){
            $error = "bestand is te groot";
        }
    }
    return $error;
}

enum inputType{
    case TITLE;
    case TEXT;
    case FILE;
}
?>