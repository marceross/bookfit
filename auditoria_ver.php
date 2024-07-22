<?
include ("conex.php");
include("local_controla2.php");
include("biblioteca.php");
$auditoria=mysqli_query($mysqli,"SELECT auditoria.cod, cod_producto, usu, men, fecha, id_usuario, usuarios.usuario, nombre, productos.cod, productos.descripcion FROM auditoria, productos, usuarios WHERE usu=usuarios.id_usuario AND cod_producto=productos.cod ORDER BY auditoria.fecha DESC");
if(isset($_GET['exp']))


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
<p><strong>CAJA APORTE</strong></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="ver_cajaaporte.php?exp=1">Exportar a Excel</a></p>
<table width="95%" border="1" align="center" bordercolor="#999999">
  <tr>
    <td width="6%" align="center"><div align="center">Fecha</div></td>
    <td width="9%" align="center"><div align="center">Cod. modificacion</div></td>
    <td width="15%" align="center"><div align="center">Producto nombre</div></td>
    <td width="6%" align="center">Cod. prod.</td>
    <td width="36%" align="center">Descripcion</td>
    <td width="17%" align="center">Mensaje</td>
    <td width="11%" align="center"><div align="center">Vendedor</div></td>
  </tr>
  <?
while($auditori=mysql_fetch_array($auditoria))
{
?>
  <tr>
    <td><div align="center"><? echo formato_latino ($auditori[4]);?></div></td>
    <td><div align="center"><? echo $auditori[0];?></div></td>
    <td><div align="center"><? echo $auditori[7];?></div></td>
    <td><div align="center"><? echo $auditori[8];?></div></td>
    <td><div align="center"><? echo $auditori[9];?></div></td>
    <td><div align="center"><? echo $auditori[3];?></div></td>
    <td><div align="center"><? echo $auditori[6];?></div></td>
  </tr>
  <?
}
?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
