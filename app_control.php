<?
include("local_controla.php");
include("conex.php");
include("biblioteca.php");

date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();

//MES ACTUAL

$fecha_final=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
$fecha_inicial=$array_fecha['year']."/".$array_fecha['mon']."/01";

//copiado de ver_ventas.php
$ventas=mysqli_query($mysqli,"SELECT ventas.id_venta, ventas.id_usuario, ventas.fecha, ventas.hora, ventas.id_forma, ventas_detalle.id_venta, ventas_detalle.cod_producto, ventas_detalle.cantidad, ventas_detalle.precio, productos.nombre, usuarios.usuario, ventas_forma_pago.nombre FROM ventas, ventas_detalle, productos, usuarios, ventas_forma_pago WHERE ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=ventas_forma_pago.id_forma ORDER BY ventas.fecha DESC LIMIT 100");

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
//$asistencias=mysqli_query($mysqli,"SELECT COUNT(*) as cant_asistencias FROM actividad_reservas WHERE (fecha>='$fecha_inicial' AND fecha<='$fecha_final') AND asiste=1");
//$asistencia=mysqli_fetch_array($asistencias);


/* Leads */
$leads=mysqli_query($mysqli,"SELECT clicks FROM indicador_botones WHERE cod=1");
$lead=mysqli_fetch_array($leads);

/* Prospects */
$prospects=mysqli_query($mysqli, "SELECT COUNT(*) as prospects FROM registrados WHERE vencimiento='0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
$total_prospect=mysqli_fetch_array($prospects);

/* Conversion*/
$clientes_nuevos=mysqli_query($mysqli, "SELECT COUNT(*) as clientes_nuevos FROM registrados WHERE vencimiento<>'0000-00-00' AND (fecha>='$fecha_inicial' AND fecha<='$fecha_final')");
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
WHERE (ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=1) AND (fecha='$fecha_final')
GROUP BY usuarios.usuario
ORDER BY total_pesos DESC");

//Tabla asistencias
$asistencias=mysqli_query($mysqli,"SELECT usuarios.usuario, COUNT(*) as cant_asistencias FROM actividad_reservas, usuarios WHERE (actividad_reservas.id_usuario=usuarios.id_usuario  AND asiste=1) AND (fecha='$fecha_final')
GROUP BY usuarios.usuario
ORDER BY cant_asistencias DESC");
//$asistencia=mysqli_fetch_array($asistencias)


////////////////////////// consulta para ver las ventas de un profesor entre fechas
/*"SELECT usuarios.usuario, COUNT(*) AS cant_ventas, SUM(ventas_detalle.precio) AS total_pesos, SUM(cantp) as total_puntos
FROM ventas, ventas_detalle, productos, usuarios
WHERE (ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=1) AND (fecha>='2022-01-01' AND fecha<='2022-02-11')
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
$puntos_activos_mesanterior=mysqli_query($mysqli,"SELECT SUM(credito) as total_puntos, COUNT(*) as socios_activos FROM registrados WHERE vencimiento>'$fecha_final_mesanterior'");
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
<h6>Profesores</h6>
<button type="button" class="btn btn-info btn-sm">Ventas</button>

    <table class="table table-striped table-hover">
    <caption>Detalle de ventas por profesor</caption>
    <thead>
        <tr>
        <th scope="col">Nom</th>
        <th scope="col">Q</th>
        <th scope="col">∑ puntos</th>
        <th scope="col">∑ $</th>
        <th scope="col">AVG</th>
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
            </tr>
        <?php
            }
        ?>
    </tbody>
    </table>
</div>

<div class="container">
    <h6>Asistencias</h6>
    <button type="button" class="btn btn-info btn-sm">Asistencias</button>

    <table class="table table-striped table-hover">
    <caption>Detalle de asistencias por profesor</caption>
    <thead>
        <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Cantidad de asistencias</th>
        </tr>
    </thead>
    <tbody>
        <?
            while ($asistencia=mysqli_fetch_array($asistencias))
            {
        ?>
            <tr>
            <th scope="row"><? echo $asistencia['usuario'];?></th>
            <td><? echo $asistencia['cant_asistencias'];?></td>
            </tr>
            <?
            }
            ?>
    </tbody>
    </table>
</div>


<div class="container">
<h6>Ventas</h6>
<button type="button" class="btn btn-info btn-sm">Ventas</button>

<table class="table table-striped table-hover">
<caption>Ventas totales</caption>
    <thead>
        <tr>
            <th scope="col">Fecha</th>
            <th scope="col">Hora</th>
            <th scope="col">Codigo</th>
            <th scope="col">Producto</th>
            <th scope="col">Precio</th>
            <th scope="col">Cantidad</th>
            <th scope="col">forma de pago</th>
            <th scope="col">Vendedor</th>
        </tr>
    </thead>
    <tbody>
    <?
    while($venta=mysqli_fetch_array($ventas))
    {
    ?>
    <tr> 
        <td><? echo formato_latino ($venta[2]);?></td>
        <td><? echo $venta[3];?></td>
        <td><? echo $venta[0];?></td>
        <td><? echo $venta[9];?></td>
        <td><? echo $venta[8];?></td>
        <td><? echo $venta[7];?></td>
        <td><? echo $venta[11];?></td>
        <td><? echo $venta[10];?></td>
    </tr>
  <?
}
?>
</tbody>
</table>
</div>



<script>
$(document).ready(function(){						
   //Pintamos de rojo o verde los resultados
   //jquery
    $('span.resultados').each(function(idx, el) {        
        valor=$(el).html().replace("$", "");        1
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
