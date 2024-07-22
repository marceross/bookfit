
<?php

include("conex.php");

$cod=$_GET['cod'];
if ($cod=='1')
{
    //Cuento el click de boton actividades
    mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=clicks+1 WHERE cod='$cod'");
    header("Location:app_actividades.php");
    
}

if ($cod=='2')
{
    //Cuento el click de mail
    mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=clicks+1 WHERE cod='$cod'");
    header("Location:mailto:hola@lokales.com.ar");
}

if ($cod=='3')
{
    //Cuento el click de mail
    mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=clicks+1 WHERE cod='$cod'");
    header("Location:https://www.facebook.com/lokales");
}

if ($cod=='4')
{
    //Cuento el click de mail
    mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=clicks+1 WHERE cod='$cod'");
    header("Location:https://www.instagram.com/lokalestraining/");
}

if ($cod=='5')
{
    //Cuento el click de inscripcion
    mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=clicks+1 WHERE cod='$cod'");
    header("Location:login_inscripcion.php
");
}

?>