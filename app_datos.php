<?
include("local_controla.php");
include("conex.php");
include("biblioteca.php");

date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();

//MES ACTUAL

$fecha_final=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
$fecha_inicial=$array_fecha['year']."/".$array_fecha['mon']."/01";

//Ventas totales
$ventas_totales=mysqli_query($mysqli,"SELECT COUNT(*) AS cant_ventas, SUM(ventas_detalle.precio) AS total_ventas  FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
$venta_total=mysqli_fetch_array($ventas_totales);

//Puntos activos
$puntos_activos=mysqli_query($mysqli,"SELECT SUM(credito) as total_puntos, COUNT(*) as socios_activos FROM registrados WHERE vencimiento>'$fecha_final'");
$puntos_activo=mysqli_fetch_array($puntos_activos);

// si quisiera saber cuanto socios en el periodo del ultimo mes...
//$puntos_activos=mysqli_query($mysqli,"SELECT SUM(credito) as total_puntos, COUNT(*) as socios_activos FROM registrados WHERE fecha>='$fecha_inicial' AND fecha<='$fecha_final'");
//$puntos_activo=mysqli_fetch_array($puntos_activos);
//

//Cantidad de reservas
$reservas=mysqli_query($mysqli,"SELECT COUNT(*) as cant_reservas FROM actividad_reservas WHERE fecha>='$fecha_inicial' AND fecha<='$fecha_final'");
$reserva=mysqli_fetch_array($reservas);

//Cantidad de asistencias
$asistencias=mysqli_query($mysqli,"SELECT COUNT(*) as cant_asistencias FROM actividad_reservas WHERE (fecha>='$fecha_inicial' AND fecha<='$fecha_final') AND asiste=1");
$asistencia=mysqli_fetch_array($asistencias);

/* Visits */
$visits=mysqli_query($mysqli,"SELECT clicks as visits FROM indicador_botones WHERE cod=0");
$visit=mysqli_fetch_array($visits);

/* Leads */
$leads=mysqli_query($mysqli,"SELECT clicks FROM indicador_botones WHERE cod=1");
$lead=mysqli_fetch_array($leads);

/* Prospects */
$prospects=mysqli_query($mysqli, "SELECT COUNT(*) as prospects FROM registrados WHERE  vencimiento='0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
$total_prospect=mysqli_fetch_array($prospects);

/* Conversion*/
$clientes_nuevos=mysqli_query($mysqli, "SELECT COUNT(*) as clientes_nuevos FROM registrados WHERE  vencimiento<>'0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
$cliente_nuevo=mysqli_fetch_array($clientes_nuevos);

/* Retención*/
$time3=strtotime($fecha_inicial);
$fecha_inicial_3meses=date("Y-m-d", strtotime("-3 month", $time3));

$recompras=mysqli_query($mysqli,"SELECT COUNT(dni) AS recompra FROM registrados WHERE dni IN (SELECT id_registrados FROM ventas WHERE (fecha>='$fecha_inicial_3meses' AND fecha<='$fecha_final') GROUP BY id_registrados HAVING COUNT(id_registrados)>=2)");
$recompra=mysqli_fetch_array($recompras);

//Tabla Profesores
$ventas_profe=mysqli_query($mysqli,
"SELECT usuarios.usuario, COUNT(*) AS cant_ventas, SUM(ventas_detalle.precio) AS total_pesos, SUM(cantp) as total_puntos
FROM ventas, ventas_detalle, productos, usuarios
WHERE (ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=1) AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')
GROUP BY usuarios.usuario
ORDER BY total_pesos DESC");

////////////////////////// consulta para ver las ventas de un profesor entre fechas
/*"SELECT usuarios.usuario, COUNT(*) AS cant_ventas, SUM(ventas_detalle.precio) AS total_pesos, SUM(cantp) as total_puntos
FROM ventas, ventas_detalle, productos, usuarios
WHERE (ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=1) AND (fecha>='2022-06-01' AND fecha<='2022-06-05')
GROUP BY usuarios.usuario
ORDER BY total_pesos DESC"*/

//MES ANTERIOR
$time=strtotime($fecha_inicial);
$fecha_inicial_mesanterior=date("Y-m-d", strtotime("-1 month", $time));
$time2=strtotime($fecha_final);
$fecha_final_mesanterior=date("Y-m-d", strtotime("-1 month", $time2));

//echo $fecha_inicial_mesanterior."<br>";
//echo $fecha_final_mesanterior."<br>";
//exit();

//Ventas totales mes anterior
$ventas_totales_mesanterior=mysqli_query($mysqli,"SELECT SUM(ventas_detalle.precio) AS total_ventas  FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND (fecha>='$fecha_inicial_mesanterior' AND fecha<='$fecha_final_mesanterior')");
$venta_total_mesanterior=mysqli_fetch_array($ventas_totales_mesanterior);

//Puntos activos mes anterior
// Consulta de tabla indicadores
$puntos_activos_mesanterior=mysqli_query($mysqli,"SELECT puntos_activos as total_puntos, clientes_activos as socios_activos FROM indicadores ORDER BY fecha DESC LIMIT 1");

//$puntos_activos_mesanterior=mysqli_query($mysqli,"SELECT SUM(credito) as total_puntos, COUNT(*) as socios_activos FROM registrados WHERE vencimiento>'$fecha_final_mesanterior'");
$puntos_activo_mesanterior=mysqli_fetch_array($puntos_activos_mesanterior);

//Cantidad de reservas mes anterior
$reservas_mesanterior=mysqli_query($mysqli,"SELECT COUNT(*) as cant_reservas FROM actividad_reservas WHERE fecha>='$fecha_inicial_mesanterior' AND fecha<='$fecha_final_mesanterior'");
$reserva_mesanterior=mysqli_fetch_array($reservas_mesanterior);

//Cantidad de asistencias mes anterior
$asistencias_mesanterior=mysqli_query($mysqli,"SELECT COUNT(*) as cant_asistencias FROM actividad_reservas WHERE (fecha>='$fecha_inicial_mesanterior' AND fecha<='$fecha_final_mesanterior') AND asiste=1");
$asistencia_mesanterior=mysqli_fetch_array($asistencias_mesanterior);

//Tabla profes mes anterior
if(!$ventas_profe_mesanterior=mysqli_query($mysqli,
"SELECT usuarios.usuario, COUNT(*) AS cant_ventas, SUM(ventas_detalle.precio) AS total_pesos, SUM(cantp) as total_puntos
FROM ventas_detalle, productos, ventas RIGHT JOIN usuarios ON ventas.id_usuario=usuarios.id_usuario
WHERE (ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_forma=1) AND (fecha>='$fecha_inicial_mesanterior' AND fecha<='$fecha_final_mesanterior')
GROUP BY usuarios.usuario
ORDER BY total_pesos DESC"))

{
    echo "Error: ".mysqli_error($mysqli);
    exit();
}


//echo "SELECT dni, registrados.nombre, apellido, COUNT(*) AS cant_compras, SUM(ventas_detalle.precio) AS total_pesos FROM ventas, ventas_detalle, productos, registrados WHERE (ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_registrados=registrados.dni  AND ventas.id_forma=1) AND (ventas.fecha>='$fecha_inicial' AND ventas.fecha<='$fecha_final') GROUP BY dni ORDER BY dni<br><br>";


//echo "SELECT dni, registrados.nombre, apellido, COUNT(*) AS cant_asistencias FROM registrados, actividad_reservas WHERE actividad_reservas.registrados_dni=registrados.dni  AND (actividad_reservas.fecha>='$fecha_inicial' AND actividad_reservas.fecha<='$fecha_final') AND asiste=1 GROUP BY dni ORDER BY dni<br><br>";

//echo  "Ingresos: ".mysqli_num_rows($ingresos_cliente)."<br>";
//echo  "Reservas: ".mysqli_num_rows($reservas_cliente)."<br>";
//echo  "Asistencias: ".mysqli_num_rows($asistencias_cliente)."<br>";


/*Clientes reservas y asistencias totales
$reservas_cliente_totales=mysqli_query($mysqli,
"SELECT dni, nombre, apellido, COUNT(actividad_reservas.asiste) AS asistencia
FROM registrados, actividad_reservas
WHERE registrados.dni=actividad_reservas.registrados_dni AND vencimiento>'$fecha_hoy'
GROUP BY dni
ORDER BY asistencia DESC");*/


$reservas=mysqli_query($mysqli,
"SELECT COUNT(*) as cant_reservas 
FROM actividad_reservas 
WHERE fecha>='$fecha_inicial' AND fecha<='$fecha_final'");



//Tabla actividades reservas
// cant_puntos...
$actividades_reservas=mysqli_query($mysqli,
"SELECT nombre, COUNT(*) AS cant_reservas
FROM actividades, actividad_horarios, actividad_reservas,
WHERE (actividad.id_actividad=actividad_horarios.actividad_id_actividad AND actividad_horarios.actividad_id_actividad=actividad_reservas.actividad_horarios_id_horario AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=1) AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')
GROUP BY actividad.nombre
ORDER BY cant_reservas DESC");
?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">

<script src="js/jquery-3.6.0.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script>
// muestra la tabla del botón que se apretó, el botón debe tener un ID y llama al archivo crear_tabla.php
// jquery
// cambia el color del botón
function crear_tabla(tipo, id_boton)
	{        
        $('button.boton_menu').each(function(idx, el) { 
            //alert($(el).attr('id'));
            //alert(id_boton);
            if($(el).attr('id')!=id_boton)                  
            {
                $(el).css('opacity','1');            
            }
        });

		//alert("DNI: "+dni);
        if($('#'+id_boton).css('opacity')=='1')
        {
            $.ajax({			
                url: 'crear_tabla.php',
                data: 'tipo=' + tipo,
                success: function(resp) {					
                    $('#tabla_datos_clientes').html(resp);
                }
            });
            $('#'+id_boton).css('opacity','0.6');
        }
        else
        {
            $('#tabla_datos_clientes').html('');
            $('#'+id_boton).css('opacity','1');
        }
	}
</script>

</head>
<body>

<div class="container">
    <table class="table">
    <thead>
        <tr>
        <!--<th scope="col"><button type="button" class="btn btn-info btn-sm">Ventas</button></th>-->
        <th scope="col" class="btn btn-info">Ventas</th>
        <th scope="col">Puntos activos</th>
        <th scope="col">Socios activos</th>
        <th scope="col">Reservas</th>
        <th scope="col">Asistencia</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <th scope="row">$<? echo number_format($venta_total['total_ventas'],2,",",".");?><br>
        <span class="badge resultados">$<? echo number_format($venta_total['total_ventas']-$venta_total_mesanterior['total_ventas']);?></span>
        <h2><? echo " ". round((($venta_total['total_ventas']-$venta_total_mesanterior['total_ventas'])/$venta_total_mesanterior['total_ventas'])*100);?>%</h2>
        </th>
        <td><? echo $puntos_activo['total_puntos'];?><br>
        <span class="badge resultados"><? echo $puntos_activo['total_puntos']-$puntos_activo_mesanterior['total_puntos'];?></span>
        <h2><? echo " ". round((($puntos_activo['total_puntos']-$puntos_activo_mesanterior['total_puntos'])/$puntos_activo_mesanterior['total_puntos'])*100);?>%</h2>
        </td>
        <td><? echo $puntos_activo['socios_activos'];?><br>
        <span class="badge resultados"><? echo $puntos_activo['socios_activos']-$puntos_activo_mesanterior['socios_activos'];?></span>
        <h2><? echo " ". round((($puntos_activo['socios_activos']-$puntos_activo_mesanterior['socios_activos'])/$puntos_activo_mesanterior['socios_activos'])*100);?>%</h2>
        </td>
        <td><? echo $reserva['cant_reservas'];?><br>
        <span class="badge resultados"><? echo $reserva['cant_reservas']-$reserva_mesanterior['cant_reservas'];?></span>
        <h2><? echo " ". round((($reserva['cant_reservas']-$reserva_mesanterior['cant_reservas'])/$reserva_mesanterior['cant_reservas'])*100);?>%</h2>
        </td>
        <td><? echo $asistencia['cant_asistencias'];?><br>
        <span class="badge resultados"><? echo $asistencia['cant_asistencias']-$asistencia_mesanterior['cant_asistencias'];?></span>
        <h2><? echo " ". round((($asistencia['cant_asistencias']-$asistencia_mesanterior['cant_asistencias'])/$asistencia_mesanterior['cant_asistencias'])*100);?>%</h2>
        </td>
        </tr>
        
        <tr>
        <td>$<? echo number_format($venta_total_mesanterior['total_ventas'],2,",",".");?></td>
        <td><? echo $puntos_activo_mesanterior['total_puntos'];?></td>
        <td><? echo $puntos_activo_mesanterior['socios_activos'];?></td>
        <td><? echo $reserva_mesanterior['cant_reservas'];?></td>
        <td><? echo $asistencia_mesanterior['cant_asistencias'];?></td>
        </tr>
    </tbody>
    </table>
</div>


<div class="container">
    <table class="table">
    <thead>
        <tr>
        <th scope="col">Visits</th>
        <th scope="col">Leads</th>
        <th scope="col">Prospects</th>
        <th scope="col">Conversion</th>
        <th scope="col">Retención</th>
        <th scope="col">Puntos promedio</th>
        <th scope="col">Gasto promedio</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><? echo $visit['visits'];?></td>
        <td><? echo $lead['clicks'];?></td>
        <td><? echo $total_prospect['prospects'];?></td>
        <td><? echo $cliente_nuevo['clientes_nuevos'];?></td>
        <td><? echo $recompra['recompra'];?></td>
        <!--<td>$<? //echo number_format($venta_total['total_ventas']/$puntos_activo['socios_activos']);?></td>-->
        <td><? echo number_format($puntos_activo['total_puntos']/$puntos_activo['socios_activos']);?></td>
        <td>$<? echo number_format($venta_total['total_ventas']/$venta_total['cant_ventas']);?></td>
        </tr>
    </tbody>
    </table>
</div>


<div class="container">
<h6>Profesores</h6>
<button type="button" class="btn btn-info btn-sm">Ventas</button>
<button type="button" class="btn btn-info btn-sm">Asistencias</button>
    <table class="table table-striped table-hover">
    <caption>Detalle de ventas por profesor</caption>
    <thead>
        <tr>
        <th scope="col">Nom</th>
        <th scope="col">Q</th>
        <th scope="col">∑ puntos</th>
        <th scope="col">∑ $</th>
        <th scope="col">AVG</th>
        <th scope="col">% total</th>
        <th scope="col">mes anterior</th>
        <th scope="col">~ mes</th>
        <th scope="col">~ %</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while($venta_profe=mysqli_fetch_array($ventas_profe))
            {
                $venta_profe_mesanterior=mysqli_fetch_array($ventas_profe_mesanterior);
        ?>
            <tr>
            <th scope="row"><? echo $venta_profe['usuario'];?></th>
            <td><? echo $venta_profe['cant_ventas'];?></td>
            <td><? echo $venta_profe['total_puntos'];?></td>
            <td>$<? echo number_format($venta_profe['total_pesos'],2,",",".");?></td>
            <td>$<? echo number_format(($venta_profe['total_pesos']/$venta_profe['total_puntos']),2,",",".");?> </td>
            <td><? echo number_format(($venta_profe['total_pesos']/$venta_total['total_ventas'])*100,2,",",".");?>%</td>
            
            <td><? echo number_format($venta_profe_mesanterior['total_pesos'],2,",",".");?></td>
            
            <td><span class="badge resultados"><? echo number_format($venta_profe['total_pesos']-$venta_profe_mesanterior['total_pesos'],2,",",".");?></span></td>
            <td><span class="badge resultados"><? echo " ". round((($venta_profe['total_pesos']-$venta_profe_mesanterior['total_pesos'])/$venta_profe_mesanterior['total_pesos'])*100);?>%</span></td>
            </tr>
        <?php
            }
        ?>


    </tbody>
    </table>
</div>


<div class="container">
<h6>Actividades</h6>
<button type="button" class="btn btn-info btn-sm">Ingresos</button>
<button type="button" class="btn btn-info btn-sm">Reservas</button>
<button type="button" class="btn btn-info btn-sm">Asistencias</button>
<button type="button" class="btn btn-info btn-sm">Invitaciones</button>
    <table class="table table-striped table-hover">
    <caption>Detalle de ingreso por actividad</caption>
    <thead>
        <tr>
        <th scope="col">Actividad</th>
        <th scope="col">Puntos</th>
        <th scope="col">% del total</th>
        <th scope="col">Mes anterior</th>
        <th scope="col">Variacion porcentual</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <th scope="row">Acrobacia</th>
        <td>1200</td>
        <td>14 %</td>
        <td>1000</td>
        <td><span class="badge badge-success">+ 20 %</span></td>
        </tr>
    </tbody>
    </table>
</div>

<div class="container">
    <h6>Cliente</h6>
    <button type="button" class="btn btn-info btn-sm boton_menu" onClick="crear_tabla('ingresos', this.id)" id="btn_ingresos_clientes">Ingresos</button>
    <button type="button" class="btn btn-info btn-sm boton_menu" onClick="crear_tabla('reservas', this.id)" id="btn_reservas_clientes">Reservas</button>
    <button type="button" class="btn btn-info btn-sm boton_menu" onClick="crear_tabla('asistencias', this.id)" id="btn_asistencias">Asistencias</button>
    <!--<button type="button" class="btn btn-info btn-sm">Invitaciones</button>-->
    <div id="tabla_datos_clientes">
    </div>
</div>

<script>
$(document).ready(function(){						
   //Pintamos de rojo o verde los resultados
   //jquery
    $('span.resultados').each(function(idx, el) {        
        valor=$(el).html().replace("$", "");        
        if(parseFloat(valor)<0)// comprueba si en valor es positivo o negativo
        {
            //$(el).removeClass('badge-success');
            $(el).addClass('badge-danger');
            //alert('El elemento ' + idx + 'es negativo');
        }
        else
        {
            //$(el).removeClass('badge-danger');
            $(el).addClass('badge-success');
            //alert('El elemento ' + idx + 'es positivo');
        }
    });
});
</script>

</body>
</html>
