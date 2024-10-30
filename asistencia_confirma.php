<?php

session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha = getdate();
$fecha = strval($array_fecha['year']) . "/" . strval($array_fecha['mon']) . "/" . strval($array_fecha['mday']);
$id_usuario = $_SESSION['usuario_act'];
$idhorario = $_GET['idhorario'];

// Inicializamos una variable para contar los puntos
$puntos = 0;

// Seleccion de los registros de la actividad
$registros = mysqli_query($mysqli, "SELECT DISTINCT actividad_reservas.registrados_dni AS reg_dni, actividad_reservas.fecha AS fecha_reserva, actividad_reservas_asist.registrados_dni AS reg_dni2 FROM actividad_reservas LEFT JOIN actividad_reservas_asist ON actividad_reservas.registrados_dni=actividad_reservas_asist.registrados_dni WHERE actividad_reservas.actividad_horarios_id_horario='$idhorario' AND actividad_reservas.fecha = '$fecha'");

while ($registro = mysqli_fetch_array($registros)) {
    $dni = $registro['reg_dni'];
    $fecha = $registro['fecha_reserva'];
    
    // Determinamos si la persona asistio o no
    $asiste = !is_null($registro['reg_dni2']) ? 1 : 0;

    // Actualizacion de la asistencia en actividad_reservas
    mysqli_query($mysqli, "UPDATE actividad_reservas SET asiste='$asiste', id_usuario='$id_usuario' WHERE registrados_dni='$dni' AND fecha='$fecha' AND actividad_horarios_id_horario='$idhorario'");
    
    // Si la asistencia fue confirmada, incrementamos la variable de puntos
    if ($asiste == 1) {
        $puntos += 1;
    }
}

// Actualizamos los puntos del usuario en la tabla usuarios
if ($puntos > 0) {
    mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos + $puntos WHERE id_usuario = '$id_usuario'");
}

// Eliminar datos de asistencia en actividad_reservas_asist
mysqli_query($mysqli, "DELETE FROM actividad_reservas_asist WHERE actividad_horarios_id_horario='$idhorario'");

// Redireccionar
header("Location:app_profe.php");



/*
session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha = getdate();
$fecha = strval($array_fecha['year']) . "/" . strval($array_fecha['mon']) . "/" . strval($array_fecha['mday']);
$id_usuario = $_SESSION['usuario_act'];
$idhorario = $_GET['idhorario'];

// Aseguramos que la variable puntos inicie en cero
$puntos = 0;

// Selecci車n de los registros de la actividad
$registros = mysqli_query($mysqli, "SELECT actividad_reservas.registrados_dni AS reg_dni, actividad_reservas.fecha AS fecha_reserva, actividad_reservas_asist.registrados_dni AS reg_dni2 FROM actividad_reservas LEFT JOIN actividad_reservas_asist ON actividad_reservas.registrados_dni=actividad_reservas_asist.registrados_dni WHERE actividad_reservas.actividad_horarios_id_horario='$idhorario'");

echo "Iniciando proceso de actualizaci車n de asistencia y puntos<br>";

while ($registro = mysqli_fetch_array($registros)) {
    $dni = $registro['reg_dni'];
    $fecha = $registro['fecha_reserva'];
    
    // Determinamos si la persona asisti車 o no
    $asiste = !is_null($registro['reg_dni2']) ? 1 : 0;

    // Actualizaci車n de la asistencia en actividad_reservas
    mysqli_query($mysqli, "UPDATE actividad_reservas SET asiste='$asiste', id_usuario='$id_usuario' WHERE registrados_dni='$dni' AND fecha='$fecha' AND actividad_horarios_id_horario='$idhorario'");
    
    // Mostramos informaci車n de depuraci車n
    echo "Procesando DNI: $dni - Asisti車: $asiste<br>";

    // Si la asistencia fue confirmada, incrementamos la variable de puntos
    if ($asiste == 1) {
        $puntos += 1;
        echo "Incrementando puntos: $puntos<br>"; // Mostramos el valor de puntos en cada iteraci車n
    }
}

// Antes de actualizar, mostramos el total de puntos calculados
echo "Puntos totales calculados para actualizar: $puntos<br>";

// Actualizamos los puntos del usuario en la tabla usuarios si hay puntos acumulados
if ($puntos > 0) {
    mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos + $puntos WHERE id_usuario = '$id_usuario'");
    echo "Puntos actualizados correctamente en la base de datos<br>";
}

// Eliminar datos de asistencia en actividad_reservas_asist y resetear puntos
mysqli_query($mysqli, "DELETE FROM actividad_reservas_asist WHERE actividad_horarios_id_horario='$idhorario'");

// Comentamos la redirecci車n para depuraci車n
// header("Location:app_profe.php");
*/


/*
session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha = getdate();
$fecha = strval($array_fecha['year']) . "/" . strval($array_fecha['mon']) . "/" . strval($array_fecha['mday']);
$id_usuario = $_SESSION['usuario_act'];
$idhorario = $_GET['idhorario'];

// Inicializamos la variable de puntos en cero al inicio de cada proceso
$puntos = 0;

// Selecci車n de los registros de la actividad
$registros = mysqli_query($mysqli, "SELECT actividad_reservas.registrados_dni AS reg_dni, actividad_reservas.fecha AS fecha_reserva, actividad_reservas_asist.registrados_dni AS reg_dni2 FROM actividad_reservas LEFT JOIN actividad_reservas_asist ON actividad_reservas.registrados_dni=actividad_reservas_asist.registrados_dni WHERE actividad_reservas.actividad_horarios_id_horario='$idhorario'");

while ($registro = mysqli_fetch_array($registros)) {
    $dni = $registro['reg_dni'];
    $fecha = $registro['fecha_reserva'];
    
    // Determinamos si la persona asisti車 o no
    $asiste = !is_null($registro['reg_dni2']) ? 1 : 0;

    // Actualizaci車n de la asistencia en actividad_reservas
    mysqli_query($mysqli, "UPDATE actividad_reservas SET asiste='$asiste', id_usuario='$id_usuario' WHERE registrados_dni='$dni' AND fecha='$fecha' AND actividad_horarios_id_horario='$idhorario'");
    
    // Si la asistencia fue confirmada, incrementamos la variable de puntos
    if ($asiste == 1) {
        $puntos += 1;
    }
}

// Actualizamos los puntos del usuario en la tabla usuarios
if ($puntos > 0) {
    mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos + $puntos WHERE id_usuario = '$id_usuario'");
}

// Eliminar datos de asistencia en actividad_reservas_asist y resetear puntos
$puntos = 0; // Reiniciamos la variable para asegurarnos de que no se acumule para el siguiente ciclo
mysqli_query($mysqli, "DELETE FROM actividad_reservas_asist WHERE actividad_horarios_id_horario='$idhorario'");

// Redireccionar
header("Location:app_profe.php");*/



