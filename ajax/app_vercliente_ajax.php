<?php
session_name("app_admin");
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


include("../conex.php");
include("../local_controla.php"); // para el admin


$dni=$_POST['dni'];


if($_POST['auto_reserva'] == 1){
            
            
            $res = 'reserva_auto=1';
            
            mysqli_query($mysqli,"UPDATE registrados SET  $res WHERE dni='$dni'");
            echo 1;
        }else{
            $res = 'reserva_auto=""';
            mysqli_query($mysqli,"UPDATE registrados SET  $res WHERE dni='$dni'");
            echo "0";
        }



?>

