<?php

include("conex_profefit.php");

   $dni=$_POST['dni'];
    
    $longitude=$_POST["longitude"];
    $latitude=$_POST["latitude"];

   /* echo "$dni";
    echo "$longitude";
    echo "$latitude";
    exit;
    //$dni='29583715';*/


    /*$latitude='-31.41392606194603';//27 y corro
    $longitude='-64.1943953307791';*/



    //Actualizamos coordenadas
    //mysqli_query($mysqli,"UPDATE registrados SET nombre='marce' WHERE dni='$dni'")





    mysqli_query($mysqli,"UPDATE registrados SET latitude=$latitude and longitude=$longitude WHERE dni=$dni")

?>