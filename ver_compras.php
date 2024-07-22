<?
	session_name('app_admin');
	include("conex.php");	
	include("local_controla2.php");
include("biblioteca.php");
$compra=mysqli_query($mysqli,"SELECT compra.id_compra, compra.fecha, compra.id_proveedor, compra.id_usuario, compra_detalle.cod_producto, compra_detalle.cantidad, compra_detalle.costo, productos.nombre, proveedores.nombre, usuarios.usuario FROM compra, compra_detalle, productos, proveedores, usuarios WHERE compra.id_compra=compra_detalle.id_compra AND compra_detalle.cod_producto=productos.cod AND proveedores.id_proveedor=compra.id_proveedor AND compra.id_usuario=usuarios.id_usuario ORDER BY compra.fecha DESC");
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
<p><strong>compras</strong></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="ver_compras.php?exp=1">Exportar a Excel</a></p>
<table width="100%" border="1" align="center" bordercolor="#999999">
  <tr> 
    <td width="18%"><div align="center">Producto</div></td>
    <td width="5%"><div align="center">Costo</div></td>
    <td width="8%"><div align="center">Cantidad</div></td>
    <td width="12%"><div align="center">Proveedor</div></td>
    <td width="18%"><div align="center">Fecha de compra</div></td>
    <td width="21%"><div align="center">Usuario</div></td>
  </tr>
<?
while($compr=mysqli_fetch_array($compra))
{
?>
  <tr> 
    <td><div align="center"><? echo $compr[7];?></div></td>
    <td><div align="center"><? echo $compr[6];?></div></td>
    <td><div align="center"><? echo $compr[5];?></div></td>
    <td><div align="center"><? echo $compr[8];?></div></td>
    <td><div align="center"><? echo formato_latino ($compr[1]);?></div></td>
    <td><div align="center"><? echo $compr[9];?></div></td>
  </tr>
<?
}
?>
</table>
</body>
</html>
