<?php
session_name('app_admin');
session_start();


include ("conex.php");
include("local_controla.php");
include("biblioteca.php");
$id_usuario=$_SESSION['usuario_act'];
$ultima_fecha=mysqli_query($mysqli,"SELECT MAX(Fecha) FROM caja");
$ult_fecha=mysqli_fetch_array($ultima_fecha);
$ventas=mysqli_query($mysqli,"SELECT ventas.id_venta, ventas.id_usuario, ventas.fecha, ventas.hora, ventas.id_forma, ventas_detalle.id_venta, ventas_detalle.cod_producto, ventas_detalle.cantidad, ventas_detalle.precio, productos.nombre, usuarios.usuario, ventas_forma_pago.nombre, ventas.efectivo, ventas.descuento, ventas.recargo, productos.descripcion, productos.costo, productos.cod, confirmada FROM ventas, ventas_detalle, productos, usuarios, ventas_forma_pago WHERE ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario AND ventas.id_usuario='$id_usuario' AND ventas.id_forma=ventas_forma_pago.id_forma AND fecha='".$ult_fecha[0]."' ORDER BY ventas.id_venta DESC");
//Sumamos las ventas (prueba MARCE)
$total_ventas=0;
while($venta=mysqli_fetch_array($ventas))
{
	$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
}
$caja=mysqli_query($mysqli,"SELECT caja_extraccion.billetes, caja_extraccion.monedas, caja_extraccion_concepto.nombre, caja_extraccion.fecha, caja_extraccion.hora, caja_extraccion.id_usuario, usuarios.id_usuario, usuarios.usuario, comentarios FROM caja_extraccion, usuarios, caja_extraccion_concepto WHERE concepto=id_concepto AND caja_extraccion.id_usuario=usuarios.id_usuario AND caja_extraccion.id_usuario='$id_usuario' ORDER BY caja_extraccion.fecha DESC");
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><strong>REDES SOCIALES</strong><br>
  <a href="http://www.twitter.com/gravitalhouse">TWITTER</a> lokalesspot (...)<br>
  <a href="https://login.secureserver.net/index.php?app=wbe&logout=1">WEBMAIL</a> contacto@lokales.com.ar (...)<br>
  <a href="http://www.facebook.com/lokales">FACEBOOK</a>mibanez23@hotmail.com (...)<br>
  INSTAGRAM lokalestraining
</p>
<p><strong>CONSULTAS WEB<br>
</strong><a href="ver_contacto.php">ver_contacto</a><? echo "(".$cant_contactos.")";?><br>
<a href="ver_consultas.php">ver_consultas</a><? echo "(".$cant_consultas.")";?></p>
  <a href="tareas.htm">tareas</a>
<p><strong>PAGOS</strong><br>
  <a href="local_cajaextraccion.php">caja extraccion</a> (-)<br>
<a href="local_cajaaporte.php">caja aporte</a> (+) solo cambio, o para pagos, NO cargar ventas!</p>
<p><a href="local_ventas.php">HOME</a><a href="local_ventas.php"><br>
</a><font size="4"><a href="local_kill_session.php">DESCONECTAR</a> <font size="3">(para 
cambiar de usuario)</font></font></p>
<p><strong>VENTAS</strong></p>
<table width="95%" border="1" align="center" bordercolor="#999999">
  <tr>
	<td width="33%"><font color="#009900" size="4">($ <? echo $total_ventas;?>)</font></td>
  </tr>
  <tr>
    <td width="6%" align="center"><div align="center">Fecha</div></td>
    <td width="5%" align="center"><div align="center">Hora</div></td>
    <td width="4%" align="center"><div align="center">Cod. venta</div></td>
    <td width="10%" align="center"><div align="center">Producto</div></td>
    <td width="6%" align="center">Cod. prod.</td>
    <td width="6%" align="center">Descripcion</td>
    <td width="6%" align="center"><div align="center">Precio</div></td>
    <td width="5%" align="center"><div align="center">Cantidad</div></td>
    <td width="6%" align="center">Efectivo</td>
    <td width="6%" align="center">Descuento</td>
    <td width="6%" align="center">Recargo</td>
    <td width="6%" align="center"><div align="center">forma de pago</div></td>
    <td width="23%" align="center"><div align="center">Vendedor</div></td>
    <td width="5%" align="center">Confirmar</td>
  </tr>
  <?php
  if(mysqli_num_rows($ventas)>0)//busca que no haya datos, que sea cero el valor que arroja la consulta
  {
	mysqli_data_seek($ventas,0);//nos posiciona en el inicio de la consulta para el nuevo array
  }
while($venta=mysqli_fetch_array($ventas))
{
?>
  <tr> 
    <td><div align="center"><? echo formato_latino ($venta[2]);?></div></td>
    <td><div align="center"><? echo $venta[3];?></div></td>
    <td><div align="center"><? echo $venta[0];?></div></td>
    <td><div align="center"><? echo $venta[9]/*." ".$venta['nm']*/;?></div></td>
    <td><div align="center"><? echo $venta[17];?></div></td>
    <td><div align="center"><? echo $venta[15];?></div></td>
    <td><div align="center"><? echo $venta[8];?></div></td>
    <td><div align="center"><? echo $venta[7];?></div></td>
    <td><div align="center"><? echo $venta[12];?></div></td>
    <td><div align="center"><? echo $venta[13];?></div></td>
    <td><div align="center"><? echo $venta[14];?></div></td>
    <td><div align="center"><? echo $venta[11];?></div></td>
    <td><div align="center"><? echo $venta[10];?></div></td>
    <td><div align="center">
    <?php
	if($venta['confirmada']<>'S')
	{
	?>
    	<a href="confirmar_venta.php?id_venta=<? echo $venta['id_venta'];?>"><img src="Check-icon.png" width="32" height="32" border="0"></a>
    <?php
	}
	?>
    </div></td>
  </tr>
  <?php
}
?>
</table>
<p><strong>EXTRACCION</strong></p>
<form name="form1" method="post" action="ver_cajaextraccion.php">
  <label>Fecha 1 </label>
  <label>dia 
  <select name="d1" id="d1">
    <?php
	  		for($i=1;$i<=31;$i++)
	  		{
	  	?>
    <option value="<? echo $i;?>"><? echo $i;?></option>
    <?php
		}
		?>
  </select>
  mes 
  <select name="m1" id="m1">
    <?php
	  		for($i=1;$i<=12;$i++)
	  		{
	  	?>
    <option value="<? echo $i;?>"><? echo $i;?></option>
    <?php
		}
		?>
  </select>
  a�o 
  <select name="a1" id="a1">
    <?php
	  		for($i=2008;$i<=2030;$i++)
	  		{
	  	?>
    <option value="<? echo $i;?>"><? echo $i;?></option>
    <?php
		}
		?>
  </select>
  </label>
  <p> 
    <label>Fecha 2 </label>
    <label>dia 
    <select name="d2" id="d2">
      <?php
	  		for($i=1;$i<=31;$i++)
	  		{
	  	?>
      <option value="<? echo $i;?>"><? echo $i;?></option>
      <?php
		}
		?>
    </select>
    </label>
    <label>mes 
    <select name="m2" id="m2">
      <?php
	  		for($i=1;$i<=12;$i++)
	  		{
	  	?>
      <option value="<? echo $i;?>"><? echo $i;?></option>
      <?php
		}
		?>
    </select>
    </label>
    <label>a�o 
    <select name="a2" id="a2">
      <?php
	  		for($i=2008;$i<=2030;$i++)
	  		{
	  	?>
      <option value="<?php echo $i;?>"><? echo $i;?></option>
      <?php
		}
		?>
    </select>
    </label>
  </p>
  <p> 
    <label> 
    <input type="submit" name="Submit" value="VER">
    </label>
  </p>
</form>
<table width="100%" border="1" align="center" bordercolor="#999999">
  <tr> 
    <td width="18%"><div align="center">Fecha</div></td>
    <td width="5%"><div align="center">Hora</div></td>
    <td width="8%"><div align="center">Usuario</div></td>
    <td width="12%"><div align="center">Billetes</div></td>
    <td width="18%"><div align="center">Monedas</div></td>
    <td width="18%"><div align="center">Concepto</div></td>
    <td width="18%"><div align="center">Comentarios</div></td>
  </tr>
  <?php
while($caj=mysqli_fetch_array($caja))
{
?>
  <tr> 
    <td><div align="center"><? echo formato_latino ($caj[3]);?></div></td>
    <td><div align="center"><? echo $caj[4];?></div></td>
    <td><div align="center"><? echo $caj[7];?></div></td>
    <td><div align="center"><? echo $caj[0];?></div></td>
    <td><div align="center"><? echo $caj[1];?> </div></td>
    <td><div align="center"><? echo $caj[2];?></div></td>
    <td><div align="center"><? echo $caj['comentarios'];?></div></td>
  </tr>
  <?php
}
?>
</table>
</body>
</html>
