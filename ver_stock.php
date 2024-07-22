<?
include ("conex.php");
include("local_controla2.php");
include("biblioteca.php");
$stock=mysql_query("SELECT categorias.nombre as cn, productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod ORDER BY categorias.nombre",$link);
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
<p><strong>STOCK</strong></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="ver_stock.php?exp=1">Exportar a Excel</a></p>
<table width="100%" border="1" align="center" bordercolor="#999999">
  <tr> 
    <td width="10%"><div align="center">Categoria</div></td>
    <td width="28%"><div align="center">Producto</div></td>
    <td width="15%"><div align="center">Marca</div></td>
    <td width="31%"><div align="center">Descripcion</div></td>
    <td width="8%"><div align="center">Costo</div></td>
    <td width="8%"><div align="center">Stock</div></td>
  </tr>
  <?
while($stoc=mysql_fetch_array($stock))
{
?>
  <tr> 
    <td><div align="center"><? echo $stoc['cn'];?></div></td>
    <td><div align="center"><? echo $stoc['np'];?></div></td>
    <td><div align="center"><? echo $stoc['nm'];?></div></td>
    <td><div align="center"><? echo $stoc['descripcion'];?></div></td>
    <td><div align="center"><? echo $stoc['costo'];?></div></td>
    <td><div align="center"><? echo $stoc['stock'];?></div></td>
  </tr>
  <?
}
?>
</table>
<p>&nbsp;</p>
</body>
</html>
