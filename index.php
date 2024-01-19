<?php
require __DIR__ . '/permissionCheck.php';
session_start();
session_unset();
checkLogin();
?>

