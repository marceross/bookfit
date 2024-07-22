<?php
include("conex.php");	
//include("local_controla_admin2.php");
include("biblioteca.php");
$tipo=$_GET['tipo'];

date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();
$fecha_final=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
$fecha_inicial=$array_fecha['year']."/".$array_fecha['mon']."/01";


if($tipo=='ingresos')
{

    mysqli_query($mysqli, "DELETE FROM indicadores_clientes_temp");

    //Tabla Clientes
    $ingresos_cliente=mysqli_query($mysqli,"SELECT ventas.fecha, dni, registrados.nombre, apellido, COUNT(*) AS cant_compras, SUM(ventas_detalle.precio) AS total_pesos
    FROM ventas, ventas_detalle, productos, registrados
    WHERE (ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_registrados=registrados.dni  AND ventas.id_forma=1) AND (ventas.fecha>='$fecha_inicial' AND ventas.fecha<='$fecha_final')
    GROUP BY dni
    ORDER BY fecha DESC");

    while($ingreso_cliente=mysqli_fetch_array($ingresos_cliente))
    {
        $dni=$ingreso_cliente[1];
        $nombre=$ingreso_cliente[2];
        $ingreso=$ingreso_cliente[5];
        mysqli_query($mysqli, "INSERT INTO indicadores_clientes_temp (dni, nombre, ingreso) VALUES ('$dni', '$nombre', '$ingreso')");
    }
    exit();
?>
    <table class="table table-striped table-hover">
        <caption>Detalle de ingreso por cliente</caption>
        <thead>
            <tr>
            <th scope="col">Fecha</th>
            <th scope="col">Dni</th>
            <th scope="col">Nombre</th>
            <th scope="col">Compras</th>
            <th scope="col">Ingreso</th>            
            <!--<th scope="col">Variacion porcentual</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            while($ingreso_cliente=mysqli_fetch_array($ingresos_cliente))
            {
            ?>
            <tr>
            <td><? echo $ingreso_cliente['fecha'];?></td>
            <td><? echo $ingreso_cliente['dni'];?></td>
            <th scope="row"><? echo $ingreso_cliente['nombre']." ".$ingreso_cliente['apellido'];?></th>
            <td><? echo $ingreso_cliente['cant_compras'];?></td>
            <td>$<? echo $ingreso_cliente['total_pesos'];?></td>            
            <!--<td><span class="badge badge-success">+ 20 %</span></td>-->
            </tr>
            <?
            }
            ?>
        </tbody>
        </table>
<?
}
if($tipo=="reservas")
{
    //Clientes reservas
    $reservas_cliente=mysqli_query($mysqli,"SELECT dni, registrados.nombre, apellido, COUNT(*) AS cant_reservas
    FROM registrados, actividad_reservas
    WHERE actividad_reservas.registrados_dni=registrados.dni  AND (actividad_reservas.fecha>='$fecha_inicial' AND actividad_reservas.fecha<='$fecha_final')
    GROUP BY dni
    ORDER BY cant_reservas DESC");
?>
       <table class="table table-striped table-hover">
        <caption>Reservas por cliente</caption>
        <thead>
            <tr>
            <th scope="col">Dni</th>
            <th scope="col">Nombre</th>            
            <th scope="col">Reservas</th>            
            <!--<th scope="col">Variacion porcentual</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            while($reserva_cliente=mysqli_fetch_array($reservas_cliente))
            {
            ?>
            <tr>
            <td><? echo $reserva_cliente['dni'];?></td>
            <th scope="row"><? echo $reserva_cliente['nombre']." ".$reserva_cliente['apellido'];?></th>
            <td><? echo $reserva_cliente['cant_reservas'];?></td>            
            <!--<td><span class="badge badge-success">+ 20 %</span></td>-->
            </tr>
            <?
            }
            ?>
        </tbody>
        </table>
<?
}
if($tipo=="asistencias")
{
    //Clientes asistencias
    $asistencias_cliente=mysqli_query($mysqli,"SELECT dni, registrados.nombre, apellido, COUNT(*) AS cant_asistencias
    FROM registrados, actividad_reservas
    WHERE actividad_reservas.registrados_dni=registrados.dni  AND (actividad_reservas.fecha>='$fecha_inicial' AND actividad_reservas.fecha<='$fecha_final') AND asiste=1
    GROUP BY dni
    ORDER BY cant_asistencias DESC");

?>
    <table class="table table-striped table-hover">
        <caption>Asistencias por cliente</caption>
        <thead>
            <tr>
            <th scope="col">Dni</th>
            <th scope="col">Nombre</th>            
            <th scope="col">Asistencias</th>            
            <!--<th scope="col">Variacion porcentual</th>-->
            </tr>
        </thead>
        <tbody>
            <?php
            while($asistencia_cliente=mysqli_fetch_array($asistencias_cliente))
            {
            ?>
            <tr>
            <td><? echo $asistencia_cliente['dni'];?></td>
            <th scope="row"><? echo $asistencia_cliente['nombre']." ".$asistencia_cliente['apellido'];?></th>
            <td><? echo $asistencia_cliente['cant_asistencias'];?></td>            
            <!--<td><span class="badge badge-success">+ 20 %</span></td>-->
            </tr>
            <?
            }
            ?>
        </tbody>
        </table>    
<?
}
?>