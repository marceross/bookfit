<?
// common settings Once per month(0 0 1 * *)
//0 minutes
//0 hour
//1 day
// * month
// * weekend
// /usr/local/bin/php /home/ae1dg5284blm/public_html/lokales/cron_job_indicadores.php

// para probar, correr el archivo cambiando fecha_inicial

include("conex.php");
//include("biblioteca.php");

date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();

//FECHAS para cálculo de consultas
$fecha_final=$array_fecha['year']."/".$array_fecha['mon']."/01";
$time=strtotime($fecha_final);
$fecha_inicial=date("Y-m-d", strtotime("-1 month", $time));

//Visits
$leads=mysqli_query($mysqli,"SELECT clicks AS leads_visits FROM indicador_botones WHERE cod=0");
$lead=mysqli_fetch_array($leads);
$leads_visit=$lead['leads_visits'];

//Leads, clicks en boton ACTIVIDADES
$leads1=mysqli_query($mysqli,"SELECT clicks AS leads_actividades FROM indicador_botones WHERE cod=1");
$lead1=mysqli_fetch_array($leads1);
$leads_act=$lead1['leads_actividades'];

// Leads, clicks en boton MAIL
$leads2=mysqli_query($mysqli,"SELECT clicks AS leads_mail FROM indicador_botones WHERE cod=2");
$lead2=mysqli_fetch_array($leads2);
$leads_mail=$lead2['leads_mail'];

// Leads, clicks en boton FACEBOOK
$leads3=mysqli_query($mysqli,"SELECT clicks AS leads_facebook FROM indicador_botones WHERE cod=3");
$lead3=mysqli_fetch_array($leads3);
$leads_fb=$lead3['leads_facebook'];

// Leads, clicks en boton INSTAGRAM
$leads4=mysqli_query($mysqli,"SELECT clicks AS leads_instagram FROM indicador_botones WHERE cod=4");
$lead4=mysqli_fetch_array($leads4);
$leads_ig=$lead4['leads_instagram'];


/*
echo $leads_visit. "<br>";
echo $leads_act. "<br>";
echo $leads_mail. "<br>";
echo $leads_fb. "<br>";
echo $leads_ig. "<br>";
exit();

$leads=mysqli_query($mysqli,"SELECT clicks AS leads_actividades FROM indicador_botones WHERE cod=1 and clicks AS leads_mail FROM indicador_botones WHERE cod=2 and clicks AS leads_facebook FROM indicador_botones WHERE cod=3 and clicks AS leads_instagram FROM indicador_botones WHERE cod=4 ");
$lead=mysqli_fetch_array($leads);
$leadact=$lead['leads_actividades'];
$leadmail=$lead['leads_mail'];
$leadfb=$lead['leads_facebook'];
$leadig=$lead['leads_instagram'];

echo $leadact. "<br>";
echo $leadmail. "<br>";
echo $leadfb. "<br>";
echo $leadig. "<br>";
exit();*/

/* Prospects */
$prospects=mysqli_query($mysqli, "SELECT COUNT(*) as prospects FROM registrados WHERE  vencimiento='0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
$prospect=mysqli_fetch_array($prospects);
$prosp=$prospect['prospects'];

/* Conversion*/
$clientes_nuevos=mysqli_query($mysqli, "SELECT COUNT(*) as clientes_nuevos FROM registrados WHERE  vencimiento<>'0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
$cliente_nuevo=mysqli_fetch_array($clientes_nuevos);
$cliente_n=$cliente_nuevo['clientes_nuevos'];

/* Retención*/
// fecha de indicador RETENCION
$fecha_final2=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
$fecha_inicial2=$array_fecha['year']."/".$array_fecha['mon']."/01";

$time3=strtotime($fecha_inicial2);
$fecha_inicial_3meses=date("Y-m-d", strtotime("-3 month", $time3));



$recompras=mysqli_query($mysqli,"SELECT COUNT(dni) AS recompra FROM registrados WHERE dni IN (SELECT id_registrados FROM ventas WHERE (fecha>='$fecha_inicial_3meses' AND fecha<='$fecha_final2') GROUP BY id_registrados HAVING COUNT(id_registrados)>=2)");
$recompra=mysqli_fetch_array($recompras);
$recomp=$recompra['recompra'];

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
$puntos_prom=$total_puntos/$socios_activos;
$gasto_prom=$total_ventas/$cant_ventas;

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
echo $fecha_inicial2."<br>";
echo $fecha_final2."<br>";
exit();*/

mysqli_query($mysqli, "INSERT INTO indicadores (fecha, ventas_cantidad, ventas_efectivo, puntos_activos, clientes_activos, visits, l_act, l_mail, l_fb, l_ig, prospects, conversion, retencion, puntos_promedio, gasto_promedio, reservas, asistencia) VALUES ('$fecha_final', '$cant_ventas', '$total_ventas', '$total_puntos', '$socios_activos', '$leads_visit', '$leads_act', '$leads_mail', '$leads_fb', '$leads_ig', '$prosp', '$cliente_n', '$recomp',  '$puntos_prom', '$gasto_prom', '$cant_reservas', '$cant_asistencias')");

// PONER EL CONTADOR EN CERO todos los meses
mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=0");

?>