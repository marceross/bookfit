<?php
$databaseHost = '...';
$databaseName = '...';
$databaseUsername = '...';
$databasePassword = '...';
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
mysqli_query($mysqli,"SET NAMES 'utf8'");
mysqli_select_db($mysqli, $databaseName);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


?>