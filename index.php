<?php
/*
$counter = file_get_contents('./count.txt') + 1;
file_put_contents('./count.txt', $counter);
*/

include("conex.php");
session_start();
if(empty($_SESSION['visited'])){
    //$counter = file_get_contents('./count.txt') + 1;
    //file_put_contents('./count.txt', $counter);
    mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=clicks+1 WHERE cod=0");
}

$_SESSION['visited'] = true;
?>

<!DOCTYPE html>
<head>
<title>LOKALES TRAINING SPOT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link href="css/index.css" rel="stylesheet" type="text/css">
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<html>
    <body>
        
    <div style="display: none" id="hideAll">&nbsp;</div>
    <script type="text/javascript">
        document.getElementById("hideAll").style.display = "block";
    
        window.onload = function() 
  { document.getElementById("hideAll").style.display = "none"; }
    </script> 

    <main class="main">
        <div class="logo"><b>L<span>OK</span>A<span>L</span>ES</b></div>
        <div class="logo"><c><a href="https://www.lokales.com.ar/index_procesa.php?cod=1" id="btn-act">SKATE - ACRO - ESCALADA</a></c></div>
        
        <!--<a href="https://www.lokales.com.ar/index_procesa.php?cod=1" id="btn-twtr">ACTIVIDADES</a>-->
        <a href="https://www.lokales.com.ar/index_procesa.php?cod=5" id="btn-twtr">INSCRIPCION</a>
    
        <!--<div class="description">Busca la actividad que te guste, selecciona el horario que te quede bien y reserva antes de ir!</div>-->
    </main>

    <footer class="footer">
        <a href="https://www.lokales.com.ar/index_procesa.php?cod=2" class="footer__item">hola@lokales.com.ar</a>
        <!--<a href="#" class="footer__item">Quienes somos</a>-->
        <!--<a href="#" class="footer__item">Patrocinio</a>-->
        <!--<a href="#" class="footer__item">Trabaja con nosotros</a>-->

        <a href="https://www.lokales.com.ar/index_procesa.php?cod=3" class="fa fa-facebook"></a>
        <a href="https://www.lokales.com.ar/index_procesa.php?cod=4" class="fa fa-instagram"></a>
    </footer>

    <!--<script src="index.js"></script>-->
    </body>
</html>