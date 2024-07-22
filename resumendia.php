<?
include ("conex.php");
include("local_controla2.php");
include("biblioteca.php");
$_SESSION['total_compra']=0;
//Ventas
$ultima_fecha=mysql_query("SELECT MAX(Fecha) FROM caja",$link);
$ult_fecha=mysql_fetch_array($ultima_fecha);
$ventas=mysql_query("SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$ult_fecha[0]."' AND id_forma=1",$link);
//Sumamos las ventas
//+($venta['efectivo'])
$total_ventas=0;
while($venta=mysql_fetch_array($ventas))
{
	$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
}
//Sumamos parte en efectivo de las ventas hechas con tarjeta 
$ventas_tarjetas=mysql_query("SELECT * FROM ventas WHERE fecha='".$ult_fecha[0]."' AND (id_forma=2 OR id_forma=3) AND efectivo>0",$link);
//Sumamos al total de ventas
while($venta_tarjeta=mysql_fetch_array($ventas_tarjetas))
{
	$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
}
//Caja actual
	$caja_actual=mysql_query("SELECT billetes, monedas, fecha FROM caja_cierre WHERE fecha='".$fecha_solicitada."' AND hora>='19:00:00' AND hora<='23:59:00'",$link);
	$datos_caja_actual=mysql_fetch_array($caja_actual);
	$consultas=mysql_query("SELECT * FROM consultas WHERE respuesta=''",$link);
	$cant_consultas=mysql_num_rows($consultas);
	$contactos=mysql_query("SELECT * FROM contacto WHERE respuesta=''",$link);
	$cant_contactos=mysql_num_rows($contactos);
//Aportes
$aportes=mysql_query("SELECT * FROM caja_aporte WHERE fecha='".$ult_fecha[0]."'",$link);
$total_aportes=0;
while($aporte=mysql_fetch_array($aportes))
{
	$total_aportes=$total_aportes+$aporte['billetes']+$aporte['monedas'];
}
//Extracciones
$extracciones=mysql_query("SELECT * FROM caja_extraccion WHERE fecha='".$ult_fecha[0]."'",$link);
$total_extracciones=0;
while($extraccion=mysql_fetch_array($extracciones))
{
	$total_extracciones=$total_extracciones+$extraccion['billetes']+$extraccion['monedas'];
}
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>
<body>
<table width="298" border="1" align="center">
  <tr>
    <td width="197" align="right"><? echo formato_latino ($ult_fecha['fecha']);?></td>
    <td width="85">&nbsp;</td>
  </tr>
  <tr>
    <td><font color="#333333" size="4"><strong>CAJA</strong></font></td>
    <td><font size="4">($ <? echo $datos_caja_actual['billetes']+$datos_caja_actual['monedas'];?>)</font></td>
  </tr>
  <tr>
    <td><font color="#009900" size="4"><strong>VENTAS (+)</strong></font></td>
    <td><font color="#009900" size="4">($ <? echo $total_ventas;?>)</font></td>
  </tr>
  <tr>
    <td><font color="#009900" size="4"><strong>APORTE (+)</strong></font></td>
    <td><font color="#009900" size="4">($ <? echo $total_aportes;?>)</font></td>
  </tr>
  <tr>
    <td><font color="#FF0000" size="4"><strong>EXTRACCION (-)</strong></font></td>
    <td><font color="#FF0000" size="4">($ <? echo $total_extracciones;?>)</font></td>
  </tr>
  <tr>
    <td><font color="#333333" size="4"><strong>CIERRE</strong></font></td>
    <td><?
	$caja_final=$datos_caja_anterior['billetes']+$datos_caja_anterior['monedas']+$total_ventas+$total_aportes-$total_extracciones;
	echo $caja_final;
?></td>
  </tr>
  <tr>
    <td>Contada</td>
    <td><? echo $datos_caja_actual['billetes']+$datos_caja_actual['monedas'];?></td>
  </tr>
  <tr>
    <td>error</td>
    <td><? echo ($datos_caja_actual['billetes']+$datos_caja_actual['monedas'])-($caja_final);?></td>
  </tr>
</table>
</body>
</html>