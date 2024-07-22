<?
session_name('app_admin');
include("conex.php");	
include("local_controla2.php");
include("biblioteca.php");
$ventas=mysqli_query($mysqli,"SELECT ventas.id_venta, ventas.id_usuario, ventas.fecha, ventas.hora, ventas.id_forma, ventas_detalle.id_venta, ventas_detalle.cod_producto, ventas_detalle.cantidad, ventas_detalle.precio, productos.nombre, usuarios.usuario, ventas_forma_pago.nombre FROM ventas, ventas_detalle, productos, usuarios, ventas_forma_pago WHERE ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=ventas_forma_pago.id_forma ORDER BY ventas.fecha DESC LIMIT 300");
if(isset($_GET['exp']))
{
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=archivo.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
}
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><strong>VENTAS</strong></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="ver_ventas.php?exp=1">Exportar a Excel</a></p>
<table width="100%" border="1" align="center" bordercolor="#999999">
  <tr> 
    <td width="15%"><div align="center">Fecha</div></td>
    <td width="8%"><div align="center">Hora</div></td>
    <td width="6%"><div align="center">Codigo</div></td>
    <td width="28%"><div align="center">Producto</div></td>
    <td width="8%"><div align="center">Precio</div></td>
    <td width="8%"><div align="center">Cantidad</div></td>
    <td width="13%"><div align="center">forma de pago</div></td>
    <td width="14%"><div align="center">Vendedor</div></td>
  </tr>
  <?
while($venta=mysqli_fetch_array($ventas))
{
?>
  <tr> 
    <td><div align="center"><? echo formato_latino ($venta[2]);?></div></td>
    <td><div align="center"><? echo $venta[3];?></div></td>
    <td><div align="center"><? echo $venta[0];?></div></td>
    <td><div align="center"><? echo $venta[9];?></div></td>
    <td><div align="center"><? echo $venta[8];?></div></td>
    <td><div align="center"><? echo $venta[7];?></div></td>
    <td><div align="center"><? echo $venta[11];?></div></td>
    <td><div align="center"><? echo $venta[10];?></div></td>
  </tr>
  <?
}
?>
</table>
</body>
</html>
