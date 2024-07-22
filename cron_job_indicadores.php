<?php
// Cron job: Once per month (0 0 1 * *)

// Include necessary files
include("conex.php");

// Set timezone and get the current date
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha = getdate();

// Set initial and final dates for the month
$fecha_final = $array_fecha['year'] . "/" . $array_fecha['mon'] . "/01";
$time = strtotime($fecha_final);
$fecha_inicial = date("Y-m-d", strtotime("-1 month", $time));

// Function to fetch a single value from a query
function fetch_single_value($mysqli, $query, $field) {
    $result = mysqli_query($mysqli, $query);
    if (!$result) {
        die('Invalid query: ' . mysqli_error($mysqli));
    }
    $row = mysqli_fetch_assoc($result);
    return $row[$field] ?? 0;
}

// Fetch visits and leads metrics
$leads_visit = fetch_single_value($mysqli, "SELECT clicks AS leads_visits FROM indicador_botones WHERE cod=0", 'leads_visits');
$leads_act = fetch_single_value($mysqli, "SELECT clicks AS leads_actividades FROM indicador_botones WHERE cod=1", 'leads_actividades');
$leads_mail = fetch_single_value($mysqli, "SELECT clicks AS leads_mail FROM indicador_botones WHERE cod=2", 'leads_mail');
$leads_fb = fetch_single_value($mysqli, "SELECT clicks AS leads_facebook FROM indicador_botones WHERE cod=3", 'leads_facebook');
$leads_ig = fetch_single_value($mysqli, "SELECT clicks AS leads_instagram FROM indicador_botones WHERE cod=4", 'leads_instagram');

// Fetch prospects
$prosp = fetch_single_value($mysqli, "SELECT COUNT(*) as prospects FROM registrados WHERE vencimiento='0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')", 'prospects');

// Fetch new clients (conversion)
$cliente_n = fetch_single_value($mysqli, "SELECT COUNT(*) as clientes_nuevos FROM registrados WHERE vencimiento<>'0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')", 'clientes_nuevos');

// Fetch retention (recompras)
$fecha_final2 = $array_fecha['year'] . "/" . $array_fecha['mon'] . "/" . $array_fecha['mday'];
$fecha_inicial2 = $array_fecha['year'] . "/" . $array_fecha['mon'] . "/01";
$time3 = strtotime($fecha_inicial2);
$fecha_inicial_3meses = date("Y-m-d", strtotime("-3 month", $time3));

$recomp = fetch_single_value($mysqli, "SELECT COUNT(dni) AS recompra FROM registrados WHERE dni IN (SELECT id_registrados FROM ventas WHERE (fecha>='$fecha_inicial_3meses' AND fecha<='$fecha_final2') GROUP BY id_registrados HAVING COUNT(id_registrados)>=2)", 'recompra');

// Fetch total sales
$ventas_totales_query = "SELECT SUM(ventas_detalle.precio) AS total_ventas, COUNT(*) AS cant_ventas FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')";
$ventas_totales_result = mysqli_query($mysqli, $ventas_totales_query);
if (!$ventas_totales_result) {
    die('Invalid query: ' . mysqli_error($mysqli));
}
$ventas_totales = mysqli_fetch_assoc($ventas_totales_result);
$total_ventas = $ventas_totales['total_ventas'] ?? 0;
$cant_ventas = $ventas_totales['cant_ventas'] ?? 0;

// Fetch active points and members
$puntos_activos_query = "SELECT SUM(credito) as total_puntos, COUNT(*) as socios_activos FROM registrados WHERE vencimiento>'$fecha_final'";
$puntos_activos_result = mysqli_query($mysqli, $puntos_activos_query);
if (!$puntos_activos_result) {
    die('Invalid query: ' . mysqli_error($mysqli));
}
$puntos_activos = mysqli_fetch_assoc($puntos_activos_result);
$total_puntos = $puntos_activos['total_puntos'] ?? 0;
$socios_activos = $puntos_activos['socios_activos'] ?? 1; // Avoid division by zero
$puntos_prom = $total_puntos / $socios_activos;
$gasto_prom = $cant_ventas > 0 ? $total_ventas / $cant_ventas : 0; // Avoid division by zero

// Fetch reservations and attendances
$cant_reservas = fetch_single_value($mysqli, "SELECT COUNT(*) as cant_reservas FROM actividad_reservas WHERE fecha>='$fecha_inicial' AND fecha<='$fecha_final'", 'cant_reservas');
$cant_asistencias = fetch_single_value($mysqli, "SELECT COUNT(*) as cant_asistencias FROM actividad_reservas WHERE (fecha>='$fecha_inicial' AND fecha<='$fecha_final') AND asiste=1", 'cant_asistencias');

// Insert metrics into the indicadores table
$insert_query = "INSERT INTO indicadores (fecha, ventas_cantidad, ventas_efectivo, puntos_activos, clientes_activos, visits, l_act, l_mail, l_fb, l_ig, prospects, conversion, retencion, puntos_promedio, gasto_promedio, reservas, asistencia) 
VALUES ('$fecha_final', '$cant_ventas', '$total_ventas', '$total_puntos', '$socios_activos', '$leads_visit', '$leads_act', '$leads_mail', '$leads_fb', '$leads_ig', '$prosp', '$cliente_n', '$recomp', '$puntos_prom', '$gasto_prom', '$cant_reservas', '$cant_asistencias')";
if (!mysqli_query($mysqli, $insert_query)) {
    die('Error inserting data: ' . mysqli_error($mysqli));
}

// Reset button clicks
if (!mysqli_query($mysqli, "UPDATE indicador_botones SET clicks=0")) {
    die('Error resetting clicks: ' . mysqli_error($mysqli));
}

mysqli_close($mysqli);
?>
