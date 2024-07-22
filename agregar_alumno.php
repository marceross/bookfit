<?php

    session_name('app_admin');
session_start();
    include("conex.php");	
	include("local_controla.php");
    include("biblioteca.php");
    date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
	$dni_cli=$_GET['dni_cli'];
    $idhorario=$_GET['idhorario'];
    $array_fecha=getdate();
    $fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);

    //Vemos si tiene crédito
    //Buscamos el credito del alumno
	$datos_usuarios=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$dni_cli'");
    $dato_usuario=mysqli_fetch_array($datos_usuarios);
    $credito_actual=$dato_usuario['credito'];
    //Buscamos el costo de la clase
    $clases=mysqli_query($mysqli, "SELECT * FROM actividad_horarios WHERE id_horario='$idhorario'");    
    $clase=mysqli_fetch_array($clases);
    $costo_clase=$clase['costo'];    
    //Veriicamos si el credito es suficiente
    if($credito_actual>=$costo_clase)    
    {
        //Registramos la reserva
        if(!mysqli_query($mysqli,"INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('$dni_cli','$fecha','$idhorario')"))
        {
            $error=2;
            echo  "Errorxxxx: ".mysqli_error($mysqli);
            exit();
        }
        //Actualizamos el credito          
        if(!mysqli_query($mysqli, "UPDATE registrados SET credito=credito-".$costo_clase." WHERE dni=".$dni_cli))
        {
            echo "Error act credito: ".mysqli_error($mysqli);
            exit();
        }             
    }
    else
    {
        echo "sin_credito";
    }
 
?>