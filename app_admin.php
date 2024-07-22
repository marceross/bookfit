<?
	include("local_controla.php");//controla usuario admin, que esté logueado
  include("conex.php");	
	$_SESSION['total_compra']=0;
//Ventas
$ultima_fecha=mysqli_query($mysqli,"SELECT MAX(Fecha) FROM caja");
$ult_fecha=mysqli_fetch_array($ultima_fecha);
$ventas=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$ult_fecha[0]."' AND id_forma=1");
//Sumamos las ventas
//+($venta['efectivo'])
$total_ventas=0;
while($venta=mysqli_fetch_array($ventas))
{
	$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
}
//Sumamos parte en efectivo de las ventas hechas con tarjeta 
$ventas_tarjetas=mysqli_query($mysqli,"SELECT * FROM ventas WHERE fecha='".$ult_fecha[0]."' AND (id_forma=2 OR id_forma=3) AND efectivo>0");
//Sumamos al total de ventas
while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
{
	$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
}
//Caja actual
	$caja_actual=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja WHERE fecha='".$ult_fecha[0]."'");
	$datos_caja_actual=mysqli_fetch_array($caja_actual);
	$consultas=mysqli_query($mysqli,"SELECT * FROM consultas WHERE respuesta=''");
	$cant_consultas=mysqli_num_rows($consultas);
	$contactos=mysqli_query($mysqli,"SELECT * FROM contacto WHERE respuesta=''");
	$cant_contactos=mysqli_num_rows($contactos);
//Aportes
$aportes=mysqli_query($mysqli,"SELECT * FROM caja_aporte WHERE fecha='".$ult_fecha[0]."'");
$total_aportes=0;
while($aporte=mysqli_fetch_array($aportes))
{
	$total_aportes=$total_aportes+$aporte['billetes']+$aporte['monedas'];
}
//Extracciones
$extracciones=mysqli_query($mysqli,"SELECT * FROM caja_extraccion WHERE fecha='".$ult_fecha[0]."'");
$total_extracciones=0;
while($extraccion=mysqli_fetch_array($extracciones))
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
<strong><font color="#333333" size="4">ADMINISTRACION</font><font size="4"><br>
  </font></strong>
<table width="100%" border="1">
  <tr> 
    <td width="28%"><font color="#009900" size="4"><strong>VENTAS</strong></font></td>
    <td width="6%"><font size="4"><a href="ver_ventas.php">ver</a></font></td>
    <td width="8%"><font size="4"><a href="local_ventas.php">cargar</a></font></td>
    <td width="12%"><font size="4"><a href="local_ventas_mod.php">modificar</a></font></td>
    <td width="11%"><font size="4"><a href="local_ventas_eliminar.php">eliminar</a></font></td>
    <td width="33%"><font color="#009900" size="4">Ventas efectivo HOY ($ <? echo $total_ventas;?>)</font></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFCC"><font color="#FF0000" size="4"><strong>COMPRAS</strong></font></td>
    <td bgcolor="#FFFFCC"><font size="4"><a href="ver_compras.php">ver</a></font></td>
    <td bgcolor="#FFFFCC"><font size="4"><a href="local_compra.php">cargar</a></font></td>
    <td bgcolor="#FFFFCC"><font size="4"><a href="local_compra_mod.php">modificar</a></font></td>
    <td bgcolor="#FFFFCC"><font size="4"><a href="local_compra_eliminar.php">eliminar</a></font></td>
    <td bgcolor="#FFFFCC">&nbsp;</td>
  </tr>
  <tr> 
    <td><font color="#333333" size="4"><strong>STOCK</strong></font></td>
    <td><font size="4"><a href="ver_stock.php">ver</a></font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><font color="#333333" size="4"><strong>PEDIDO</strong></font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><font size="4"><a href="local_pedido.php">preparar</a></font></td>
    <td>&nbsp;</td>
    <td><font size="4"> por proveedor</font></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFCC"><p><font color="#333333" size="4"><strong>CAJA</strong></font></p></td>
    <td bgcolor="#FFFFCC"><font size="4"><a href="ver_caja.php">ver</a> </font></td>
    <td bgcolor="#FFFFCC"><font size="4"><a href="local_caja.php">cargar</a></font></td>
    <td bgcolor="#FFFFCC"><font size="4"><a href="local_caja_mod.php">modificar</a></font></td>
    <td bgcolor="#FFFFCC">&nbsp;</td>
    <td bgcolor="#FFFFCC"><font size="4">HOY ($ <? echo $datos_caja_actual['billetes']+$datos_caja_actual['monedas'];?>)</font></td>
  </tr>
  <tr> 
    <td><font color="#333333" size="4"> <strong>CAJA CIERRE</strong></font></td>
    <td><font size="4"> <a href="ver_cajacierre.php">ver</a></font></td>
    <td><font size="4"><a href="local_ventas.php">cargar</a></font></td>
    <td><font size="4"><a href="local_cierrecaja_mod.php">modificar</a></font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td> <font color="#009900" size="4"><strong>CAJA APORTE (+)</strong></font></td>
    <td><font size="4"><a href="ver_cajaaporte.php">ver</a></font></td>
    <td><font size="4"><a href="local_cajaaporte.php">cargar</a></font><font size="4">&nbsp;</font></td>
    <td><font size="4"><a href="local_cajaaporte_mod.php">modificar</a></font></td>
    <td>&nbsp;</td>
    <td><font color="#009900" size="4">HOY ($ <? echo $total_aportes;?>)</font></td>
  </tr>
  <tr bgcolor="#FFFFCC"> 
    <td><font color="#FF0000" size="4"> <strong>CAJA EXTRACCION (-)</strong></font></td>
    <td><font size="4"><a href="ver_cajaextraccion.php">ver</a></font></td>
    <td><font size="4"><a href="local_cajaextraccion.php">cargar</a></font></td>
    <td><p><font size="4"><a href="local_cajaextraccion_mod.php">modificar</a></font></p></td>
    <td>&nbsp;</td>
    <td><font color="#FF0000" size="4">HOY ($ <? echo $total_extracciones;?>)</font></td>
  </tr>
  <tr> 
    <td><strong><font color="#333333" size="4">CIERRE DIARIO</font></strong></td>
    <td><font size="4"><a href="ver_cierre.php">ver</a></font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><strong><font color="#333333" size="4">BALANCE</font></strong></td>
    <td><font size="4"><a href="ver_balance.php">ver</a></font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong><font color="#333333" size="4">AUDITORIA</font></strong></td>
    <td><font size="4"><a href="auditoria_ver.php">ver</a></font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><a href="manual.php">manual de tareas</a></td>
  </tr>
</table>
<br>
<table width="100%" border="1">
  <tr> 
    <td width="12%"><font color="#333333" size="4"><strong>contactos</strong></font></td>
    <td width="7%"><font size="4"><a href="ver_contacto.php">ver</a><? echo "(".$cant_contactos.")";?></font></td>
    <td width="11%"><font size="4"><a href="contactos_mod.php">modificar</a></font></td>
    <td width="70%" align="right"><a href="ver_mensajes2.php">ver_mensajes</a></td>
  </tr>
  <tr> 
    <td><font color="#333333" size="4"> <strong>consultas</strong></font></td>
    <td><font size="4"><a href="ver_consultas.php">ver</a></font><? echo "(".$cant_consultas.")";?></td>
    <td><font size="4"><a href="consultas_mod.php">modificar</a></font></td>
    <td>&nbsp;</td>
  </tr>
</table>
<font color="#666666" size="4"><strong><br>
<font color="#333333">EDICION DE DATOS</font></strong></font><br>
<table width="100%" border="1">
  <tr> 
    <td width="12%"><font color="#333333" size="4"><strong>productos</strong></font></td>
    <td width="8%"><font size="4"><a href="alta_producto.php">alta</a></font></td>
    <td width="12%"><font size="4"><a href="alta_modificar.php">modificar</a></font></td>
    <td width="7%"><font size="4"><a href="baja_producto.php">baja</a></font></td>
    <td width="61%">&nbsp;</td>
  </tr>
  <tr> 
    <td><font color="#333333" size="4"> <strong>categorias</strong></font></td>
    <td><font size="4"><a href="alta_categoria.php">alta</a></font></td>
    <td><font size="4"><a href="alta_modificar_cat.php">modificar</a></font></td>
    <td><font size="4"><a href="baja_categoria.php">baja</a></font></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p> <font size="4"><a href="local_kill_session.php">DESCONECTAR</a> </font></p>
</body>
</html>
