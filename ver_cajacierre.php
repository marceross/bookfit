<?
include ("conex.php");
include("local_controla2.php");
include("biblioteca.php");
$caja=mysqli_query($mysqli,"SELECT caja_cierre.billetes, caja_cierre.monedas, caja_cierre.fecha, caja_cierre.hora, caja_cierre.id_usuario, usuarios.id_usuario, usuarios.usuario FROM caja_cierre, usuarios WHERE caja_cierre.id_usuario=usuarios.id_usuario ORDER BY caja_cierre.fecha DESC");
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
<p><strong>CAJA CIERRE</strong></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="ver_cajacierre.php?exp=1">Exportar a Excel</a></p>
<table width="100%" border="1" align="center" bordercolor="#999999">
  <tr> 
    <td width="18%"><div align="center">Fecha</div></td>
    <td width="5%"><div align="center">Hora</div></td>
    <td width="8%"><div align="center">Usuario</div></td>
    <td width="12%"><div align="center">Billetes</div></td>
    <td width="18%"><div align="center">Monedas</div></td>
  </tr>
  <?
while($caj=mysqli_fetch_array($caja))
{
?>
  <tr> 
    <td><div align="center"><? echo formato_latino ($caj[2]);?></div></td>
    <td><div align="center"><? echo $caj[3];?></div></td>
    <td><div align="center"><? echo $caj[6];?></div></td>
    <td><div align="center"><? echo $caj[0];?></div></td>
    <td><div align="center"><? echo $caj[1];?></div></td>
  </tr>
  <?
}
?>
</table>
<p>&nbsp;</p>
</body>
</html>
