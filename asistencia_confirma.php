<?php
include("conex.php");
include("local_controla.php"); // session_name(''); session_start(); se agrega si no está el controla
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();
$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
$id_usuario=$_SESSION['usuario_act'];
$idhorario=$_GET['idhorario'];
$registros=mysqli_query($mysqli, "SELECT actividad_reservas.registrados_dni AS reg_dni, actividad_reservas.fecha AS fecha_reserva, actividad_reservas_asist.registrados_dni AS reg_dni2 FROM actividad_reservas LEFT JOIN actividad_reservas_asist ON actividad_reservas.registrados_dni=actividad_reservas_asist.registrados_dni  WHERE actividad_reservas.actividad_horarios_id_horario='$idhorario'");
while($registro=mysqli_fetch_array($registros))
{
    $dni=$registro['reg_dni'];
    $fecha=$registro['fecha_reserva'];
    if(!is_null($registro['reg_dni2']))
    {
        $asiste=1;
    }
    else    
    {
        $asiste=0;
    }
    
    mysqli_query($mysqli,"UPDATE actividad_reservas SET asiste='$asiste', id_usuario='$id_usuario' WHERE registrados_dni='$dni' AND fecha='$fecha' AND actividad_horarios_id_horario='$idhorario'");    
}

//ACA BORRAR DATOS DE ASISTENCIA
mysqli_query($mysqli, "DELETE FROM actividad_reservas_asist WHERE actividad_horarios_id_horario='$idhorario'");
header("Location:app_profe.php");