<?
// common settings Once per month(0 0 1 * *)
//0 minutes
//0 hour
//1 day
// * month
// * weekend
// /usr/local/bin/php /home/ae1dg5284blm/public_html/lokales/cron_job_indicadores.php

include("conex.php");
//include("biblioteca.php");

date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();

//para cálculo de consultas
$fecha_final=$array_fecha['year']."/".$array_fecha['mon']."/01";
$time=strtotime($fecha_final);
$fecha_inicial=date("Y-m-d", strtotime("-1 month", $time));

//Ventas totales
$ventas_totales=mysqli_query($mysqli,"SELECT SUM(ventas_detalle.precio) AS total_ventas, COUNT(*) AS cant_ventas FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
$venta_total=mysqli_fetch_array($ventas_totales);
$total_ventas=$venta_total['total_ventas'];
$cant_ventas=$venta_total['cant_ventas'];

//Puntos activos
$puntos_activos=mysqli_query($mysqli,"SELECT SUM(credito) as total_puntos, COUNT(*) as socios_activos FROM registrados WHERE vencimiento>'$fecha_final'");
$puntos_activo=mysqli_fetch_array($puntos_activos);
$total_puntos=$puntos_activo['total_puntos'];
$socios_activos=$puntos_activo['socios_activos'];

//Cantidad de reservas
$reservas=mysqli_query($mysqli,"SELECT COUNT(*) as cant_reservas FROM actividad_reservas WHERE fecha>='$fecha_inicial' AND fecha<='$fecha_final'");
$reserva=mysqli_fetch_array($reservas);
$cant_reservas=$reserva['cant_reservas'];

//Cantidad de asistencias
$asistencias=mysqli_query($mysqli,"SELECT COUNT(*) as cant_asistencias FROM actividad_reservas WHERE (fecha>='$fecha_inicial' AND fecha<='$fecha_final') AND asiste=1");
$asistencia=mysqli_fetch_array($asistencias);
$cant_asistencias=$asistencia['cant_asistencias'];

/*echo $fecha_final."<br>";
echo $fecha_inicial."<br>";
echo $total_ventas."<br>";
echo $cant_ventas."<br>";
echo $total_puntos."<br>";
echo $cant_reservas."<br>";
echo $cant_asistecias."<br>";
exit();*/

mysqli_query($mysqli, "INSERT INTO indicadores (fecha, ventas_cantidad, ventas_efectivo, puntos_activos, clientes_activos, reservas, asistencia) VALUES ('$fecha_final', '$cant_ventas', '$total_ventas', '$total_puntos', '$socios_activos', '$cant_reservas', '$cant_asistencias')");
?>