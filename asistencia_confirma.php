<?php

session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();
$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
$id_usuario=$_SESSION['usuario_act'];
$idhorario=$_GET['idhorario'];

// Selección de los registros de la actividad
$registros = mysqli_query($mysqli, "SELECT actividad_reservas.registrados_dni AS reg_dni, actividad_reservas.fecha AS fecha_reserva, actividad_reservas_asist.registrados_dni AS reg_dni2 FROM actividad_reservas LEFT JOIN actividad_reservas_asist ON actividad_reservas.registrados_dni=actividad_reservas_asist.registrados_dni WHERE actividad_reservas.actividad_horarios_id_horario='$idhorario'");

while ($registro = mysqli_fetch_array($registros)) {
    $dni = $registro['reg_dni'];
    $fecha = $registro['fecha_reserva'];
    $asiste = !is_null($registro['reg_dni2']) ? 1 : 0;

    // Actualización de la asistencia en actividad_reservas
    mysqli_query($mysqli, "UPDATE actividad_reservas SET asiste='$asiste', id_usuario='$id_usuario' WHERE registrados_dni='$dni' AND fecha='$fecha' AND actividad_horarios_id_horario='$idhorario'");
    
    // Si la asistencia fue confirmada, actualizamos los puntos del usuario
    if ($asiste == 1) {
        // Obtener el total de asistencias actuales del usuario activo
        $resultado_asistencias = mysqli_query($mysqli, "SELECT COUNT(*) AS total_asistencias FROM actividad_reservas WHERE id_usuario='$id_usuario' AND asiste=1");
        $fila_asistencias = mysqli_fetch_assoc($resultado_asistencias);
        $total_asistencias = $fila_asistencias['total_asistencias'];

        // Calcular los puntos adicionales
        $puntos_adicionales = 0.5; // Por cada asistencia
        if ($total_asistencias % 10 == 0) {
            $puntos_adicionales += 1; // 1 punto adicional cada 10 asistencias
        }

        // Actualizar los puntos del usuario en la tabla usuarios
        mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos + $puntos_adicionales WHERE id_usuario = '$id_usuario'");
    }
}

// Eliminar datos de asistencia en actividad_reservas_asist
mysqli_query($mysqli, "DELETE FROM actividad_reservas_asist WHERE actividad_horarios_id_horario='$idhorario'");

// Redireccionar
header("Location:app_profe.php");
