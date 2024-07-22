<?
include ("conex.php");
include("local_controla.php");
include("biblioteca.php");
if(!isset($_POST['dia']))
{
  //Inicializamos fechas
  $array_fecha=getdate();
  $fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
  if($array_fecha['mday']<10)
  {
	  $_SESSION['dia2']="0".$array_fecha['mday'];
  }
  else
  {
		  $_SESSION['dia2']=$array_fecha['mday'];
  }
  if($array_fecha['mon']<10)
  {
	  $_SESSION['mes2']="0".$array_fecha['mon'];
  }
  else
  {
		  $_SESSION['mes2']=$array_fecha['mon'];
  }
  $_SESSION['anio2']=$array_fecha['year'];
  $_SESSION['dia']="01";
  if($array_fecha['mon']<10)
  {
	  $_SESSION['mes']="0".$array_fecha['mon'];
  }
  else
  {
		  $_SESSION['mes']=$array_fecha['mon'];
  }
  $_SESSION['anio']=$array_fecha['year'];
}
else
{
	//Fecha 1
	$_SESSION['dia']=$_POST['dia'];
	$_SESSION['mes']=$_POST['mes'];	
	$_SESSION['anio']=$_POST['anio'];
	$_SESSION['fecha1']=$_POST['anio']."/".$_POST['mes']."/".$_POST['dia'];
	//Fecha 2
	$_SESSION['dia2']=$_POST['dia2'];
	$_SESSION['mes2']=$_POST['mes2'];	
	$_SESSION['anio2']=$_POST['anio2'];
	$_SESSION['fecha2']=$_POST['anio2']."/".$_POST['mes2']."/".$_POST['dia2'];
}
if(!isset($_POST['dia']))
{
  ///*marcas.nombre as nm*//*marcas*/
  $ventas=mysqli_query($mysqli,"SELECT ventas.id_venta, ventas.id_usuario, ventas.fecha, ventas.hora, ventas.id_forma, ventas_detalle.id_venta, ventas_detalle.cod_producto, ventas_detalle.cantidad, ventas_detalle.precio, productos.nombre, usuarios.usuario, ventas_forma_pago.nombre, ventas.efectivo, ventas.descuento, ventas.recargo, productos.descripcion, productos.costo, productos.cod FROM ventas, ventas_detalle, productos, usuarios, ventas_forma_pago WHERE ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=ventas_forma_pago.id_forma ORDER BY ventas.id_venta DESC LIMIT 100");
	//Ventas
	//$ventas=mysqli_query("SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$fecha_solicitada."' AND id_forma=1 AND hora>='08:00:00' AND hora<='14:00:00'",$mysqli);
	//Sumamos las ventas
	//+($venta['efectivo'])
	$total_ventas=0;
	while($venta=mysqli_fetch_array($ventas))
	{
		$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
	}
	//Sumamos parte en efectivo de las ventas hechas con tarjeta 
	$ventas_tarjetas=mysqli_query($mysqli,"SELECT * FROM ventas WHERE fecha='".$fecha_solicitada."' AND (id_forma=2 OR id_forma=3) AND efectivo>0 AND hora>='08:00:00' AND hora<='14:00:00'");
	//Sumamos al total de ventas
	while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
	{
		$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
	}
}
else
//
//
//
//
{
	$fecha1=$_SESSION['fecha1'];
	$fecha2=$_SESSION['fecha2'];
	$ventas=mysqli_query($mysqli,"SELECT ventas.id_venta, ventas.id_usuario, ventas.fecha, ventas.hora, ventas.id_forma, ventas_detalle.id_venta, ventas_detalle.cod_producto, ventas_detalle.cantidad, ventas_detalle.precio, productos.nombre, usuarios.usuario, ventas_forma_pago.nombre, ventas.efectivo, ventas.descuento, ventas.recargo, productos.descripcion, productos.costo, productos.cod FROM ventas, ventas_detalle, productos, usuarios, ventas_forma_pago WHERE ventas.id_venta=ventas_detalle.id_venta AND ventas_detalle.cod_producto=productos.cod AND ventas.id_usuario=usuarios.id_usuario  AND ventas.id_forma=ventas_forma_pago.id_forma AND (ventas.fecha>='$fecha1' AND ventas.fecha<='$fecha2') ORDER BY ventas.id_venta DESC");
	//Ventas efectivo, id_forma=1
	$ventasefectivo=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND id_forma=1 AND (ventas.fecha>='$fecha1' AND ventas.fecha<='$fecha2')");
	//Sumamos las ventas
	$total_ventasefectivo=0;
	while($ventae=mysqli_fetch_array($ventasefectivo))
	{
		$total_ventasefectivo=$total_ventasefectivo+($ventae['precio']*$ventae['cantidad'])-(($ventae['descuento']*($ventae['precio']*$ventae['cantidad'])/100));
	}
	//Buscamos efectivo de las ventas hechas con tarjeta 
	$ventas_tarjetas=mysqli_query($mysqli,"SELECT * FROM ventas WHERE (ventas.fecha>='$fecha1' AND ventas.fecha<='$fecha2') AND (id_forma=2 OR id_forma=3) AND efectivo>0");
	//Sumamos al total de ventas efectivo
	while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
	{
		$total_ventasefectivo=$total_ventasefectivo+$venta_tarjeta['efectivo'];
	}
	//ventas debito id_forma=3
	$ventas_tarjetasdebito=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND id_forma=3 AND (ventas.fecha>='$fecha1' AND ventas.fecha<='$fecha2')");
	$total_ventasdebito=0;
	while($ventad=mysqli_fetch_array($ventas_tarjetasdebito))
	{
		$total_ventasdebito=$total_ventasdebito+($ventad['precio']*$ventad['cantidad'])-(($ventad['descuento']*($ventad['precio']*$ventad['cantidad'])/100));
	}
	//ventas credito id_forma=2
	$ventas_tarjetascredito=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND id_forma=2 AND (ventas.fecha>='$fecha1' AND ventas.fecha<='$fecha2')");
	$total_ventascredito=0;
	while($ventac=mysqli_fetch_array($ventas_tarjetascredito))
	{
		$total_ventascredito=$total_ventascredito+($ventac['precio']*$ventac['cantidad'])-(($ventac['descuento']*($ventac['precio']*$ventac['cantidad'])/100));
	}
	//depositos en cuenta id_forma=4
	$ventas_banco=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND id_forma=4 AND (ventas.fecha>='$fecha1' AND ventas.fecha<='$fecha2')");
	$total_ventasbanco=0;
	while($ventab=mysqli_fetch_array($ventas_banco))
	{
		$total_ventasbanco=$total_ventasbanco+($ventab['precio']*$ventab['cantidad'])-(($ventab['descuento']*($ventab['precio']*$ventab['cantidad'])/100));
	}
	$total_ingresos=$total_ventasefectivo+$total_ventasdebito+$total_ventascredito+$total_ventasbanco;
	//PREMIOS id_forma=5
	$premios=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND id_forma=5 AND (ventas.fecha>='$fecha1' AND ventas.fecha<='$fecha2')");
	$total_premios=0;
	while($premio=mysqli_fetch_array($premios))
	{
		$total_premios=$total_premios+($premio['precio']*$premio['cantidad']);
	}
	$total_ingresos=$total_ventasefectivo+$total_ventasdebito+$total_ventascredito+$total_ventasbanco;
	//COMPRAS sin iva
	$compras=mysqli_query($mysqli,"SELECT * FROM compra, compra_detalle WHERE compra.id_compra=compra_detalle.id_compra AND (compra.fecha>='$fecha1' AND compra.fecha<='$fecha2')");
	//Sumamos las compras
	$total_compras=0;
	while($compra=mysqli_fetch_array($compras))
	{
		$total_compras=$total_compras+($compra['costo']*$compra['cantidad']);
	}
	//SUELDOS
	////////////////////////////////////////////////////ALQUILER
$alquiler=0;
}
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
<p><strong>BALANCES</strong></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="ver_balance.php?exp=1">Exportar a Excel</a></p>
<form name="form1" method="post" action="ver_balance.php">
  <div align="center">Entre
    <select name="dia" id="dia">
      <?
					for($i=1;$i<=31;$i++)
					{
						if($i<10)
						{
							$d="0".$i;
						}
						else
						{
							$d=$i;
						}

	if($d<>$_SESSION['dia'])
	{
?>
      <option value="<? echo $d;?>"> <? echo $d;?></option>
      <?
	}
	else
	{
?>
      <option selected value="<? echo $d;?>"> <? echo $d;?></option>
      <?        
	}
  }
?>
    </select>
    /
    <select name="mes" id="mes">
      <?
	for($i=1;$i<=12;$i++)
	{
		if($i<10)
		{
			$d="0".$i;
		}
		else
		{
			$d=$i;
		}
		if($d<>$_SESSION['mes'])						
		{
?>
      <option value="<? echo $d;?>"> <? echo $d;?></option>
      <?
		}
		else
		{
?>
      <option selected value="<? echo $d;?>"> <? echo $d;?></option>
      <?            
		}
    }
?>
    </select>
    /
    <select name="anio" id="anio">
      <?
					for($i=2009;$i<=2020;$i++)
					{						
						if($i<>$_SESSION['anio'])
						{
?>
      <option value="<? echo $i;?>"> <? echo $i;?></option>
      <?
						}
						else
						{
?>
      <option selected value="<? echo $i;?>"> <? echo $i;?></option>
      <?
						}                            
					}
?>
    </select>
    y 
    <select name="dia2" id="dia2">
      <?
					for($i=1;$i<=31;$i++)
					{
						if($i<10)
						{
							$d="0".$i;
						}
						else
						{
							$d=$i;
						}

	if($d<>$_SESSION['dia2'])
	{
?>
      <option value="<? echo $d;?>"> <? echo $d;?></option>
      <?
	}
	else
	{
?>
      <option selected value="<? echo $d;?>"> <? echo $d;?></option>
      <?        
	}
  }
?>
    </select>
/
<select name="mes2" id="mes2">
  <?
	for($i=1;$i<=12;$i++)
	{
		if($i<10)
		{
			$d="0".$i;
		}
		else
		{
			$d=$i;
		}
		if($d<>$_SESSION['mes2'])						
		{
?>
  <option value="<? echo $d;?>"> <? echo $d;?></option>
  <?
		}
		else
		{
?>
  <option selected value="<? echo $d;?>"> <? echo $d;?></option>
  <?            
		}
    }
?>
</select>
/
<select name="anio2" id="anio2">
  <?
					for($i=2009;$i<=2020;$i++)
					{						
						if($i<>$_SESSION['anio2'])
						{
?>
  <option value="<? echo $i;?>"> <? echo $i;?></option>
  <?
						}
						else
						{
?>
  <option selected value="<? echo $i;?>"> <? echo $i;?></option>
  <?
						}                            
					}
?>
</select>
<label> 
  <input type="submit" name="button" id="button" value="Aceptar">
    </label>
  </div>
</form>
<br>
<table width="95%" border="1" align="center">
  <tr>
    <td width="49%" align="right"><strong>FECHA: <? echo formato_latino ($fecha1);?> - <? echo formato_latino ($fecha2);?></strong></td>
    <td width="13%" align="right">&nbsp;</td>
    <td width="13%" align="center" bgcolor="#999999">Ventas</td>
    <td width="13%" align="center" bgcolor="#999999">Descuentos (-)</td>
    <td width="12%" align="center" bgcolor="#999999">Recargos (+)</td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="right"><strong>sub-TOTALES</strong></td>
    <td bgcolor="#999999"><div align="center"><? echo $total_ventas1;?></div></td>
    <td bgcolor="#999999"><div align="center"><? echo $total_descuentos;?></div></td>
    <td bgcolor="#999999"><div align="center"><? echo $total_recargos;?></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">premios</td>
    <td bgcolor="#999999"><div align="center"><? echo $total_premios;?></div></td>
    <td bgcolor="#999999">&nbsp;</td>
    <td bgcolor="#999999">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right"><strong>TOTALES</strong></td>
    <td bgcolor="#999999"><div align="center"><strong><? echo $total1;?></strong></div></td>
    <td bgcolor="#999999"><div align="center"><strong><? echo $total2;?></strong></div></td>
    <td bgcolor="#999999"><div align="center"><strong><? echo $total3;?></strong></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">comision</td>
    <td bgcolor="#999999">&nbsp;</td>
    <td bgcolor="#999999">&nbsp;</td>
    <td bgcolor="#999999"><div align="center"><strong><? echo $comision;?></strong></div></td>
  </tr>
</table>
<br>
<table width="95%" border="1" align="center">
  <tr>
    <td width="7%">USUARIO</td>
    <td width="11%">VENTAS</td>
    <td width="12%">DESCUENTOS</td>
    <td width="10%">RECARGOS</td>
    <td width="8%">DESC. PROM.</td>
    <td width="6%">REC. PROM.</td>
    <td width="14%">COMISION</td>
    <td width="10%">SUELDO</td>
    <td width="10%">TRANSPORTE</td>
    <td width="6%">RETIRO</td>
    <td width="6%">OTROS</td>
  </tr>
  <tr>
<?
//DETALLE POR USUARIO
//while($venta=mysqli_fetch_array($ventas))
//{
?>
    <td><div align="center"><?// echo $venta[10];?></div></td>
    <td>TOTAL VENTAS</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>2%SOBRE SUS VENTAS</td>
    <td>CONSULTA</td>
    <td>CONSULTA</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>TOTAL</strong></td>
    <td>SUMATORIA</td>
    <td>SUMATORIA</td>
    <td>SUMATORIA</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>SUMATORIA</td>
    <td>SUMATORIA</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?
//}
?>
</table>
<br>
<table width="95%" border="1" align="center">
  <tr>
    <td width="24%"><p><strong>ESTADO DE RESULTADOS</strong></p></td>
    <td width="18%" align="right"><strong>FECHA: <? echo formato_latino ($fecha1);?> - <? echo formato_latino ($fecha2);?></strong></td>
    <td width="58%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>INGRESOS</strong></td>
    <td align="right"><strong>$ <? echo $total_ingresos;?></strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>VENTAS</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>EFECTIVO</td>
    <td align="right">$ <? echo $total_ventasefectivo;?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>TERJETA DE DEBITO</td>
    <td align="right">$ <? echo $total_ventasdebito;?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>TARJETA DE CREDITO</td>
    <td align="right">$ <? echo $total_ventascredito;?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>DEPOSITO BANCO</td>
    <td align="right">$ <? echo $total_ventasbanco;?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?
///////////////////////////////////////////EGRESOS TOTALES
$total_egresos=$total_compras+$alquiler+$comision+$total_premios; 
?>
  <tr>
    <td><strong>EGRESOS</strong></td>
    <td align="right"><strong>$ (<? echo $total_egresos;?>)</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>COMPRAS sin iva</td>
    <td align="right">$ (<? echo $total_compras;?>)</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>ALQUILER</td>
    <td align="right">$ (<? echo $alquiler;?>)</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>SUELDOS</td>
    <td align="right">$ ()</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>COMISIONES</td>
    <td align="right">$ (<? echo $comision;?>)</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p>PREMIOS</p></td>
    <td align="right">$ (<? echo $total_premios;?>)</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>FLETE</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>PERDIDAS</td>
    <td align="right">modificaciones de stock</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>RESULTADO</strong></td>
    <td align="right"><strong>$ <? echo $total_ingresos-$total_egresos?></strong></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p><strong>VENTAS</strong></p>
<table width="95%" border="1" align="center" bordercolor="#999999">
  <tr> 
    <td width="4%" align="center">Fecha</td>
    <td width="4%" align="center">Hora</td>
    <td width="4%" align="center">Cod. venta</td>
    <td width="7%" align="center">Producto</td>
    <td width="4%" align="center">Cod. prod.</td>
    <td width="9%" align="center">Descripcion</td>
    <td width="4%" align="center">Costo</td>
    <td width="4%" align="center">Precio</td>
    <td width="6%" align="center">Cantidad</td>
    <td width="6%" align="center">Efectivo</td>
    <td width="7%" align="center">Descuento</td>
    <td width="6%" align="center">Recargo</td>
    <td width="9%" align="center">forma de pago</td>
    <td width="7%" align="center">Vendedor</td>
    <td width="6%" align="center" bgcolor="#CCCCCC">Efectivo</td>
    <td width="7%" align="center" bgcolor="#CCCCCC">Descuento</td>
    <td width="6%" align="center" bgcolor="#CCCCCC">Recargo</td>
  </tr>
  <?
//TOTAL VENTAS 1
$total_ventas1=0;
//descuentos
$total_descuentos=0;
//recargos
$total_recargos=0;
while($venta=mysqli_fetch_array($ventas))
{
$total_ventas1=$total_ventas1+($venta[8]* $venta[7]);
$total_descuentos=$total_descuentos+$venta[8]*($venta[13]/100);
$total_recargos=$total_recargos+$venta[8]*($venta[14]/100);
$total1=$total_ventas1-$total_premios;
$total2=$total1-$total_descuentos;
$total3=$total2+$total_recargos;
$comision=$total3*0.02;
?>
  <tr> 
    <td><div align="center"><? echo formato_latino ($venta[2]);?></div></td>
    <td><div align="center"><? echo $venta[3];?></div></td>
    <td><div align="center"><? echo $venta[0];?></div></td>
    <td><div align="center"><? echo $venta[9]/*." ".$venta['nm']*/;?></div></td>
    <td><div align="center"><? echo $venta[17];?></div></td>
    <td><div align="center"><? echo $venta[15];?></div></td>
    <td><div align="center"><? echo $venta[16];?></div></td>
    <td><div align="center"><? echo $venta[8];?></div></td>
    <td><div align="center"><? echo $venta[7];?></div></td>
    <td><div align="center"><? echo $venta[12];?></div></td>
    <td><div align="center"><? echo $venta[13];?></div></td>
    <td><div align="center"><? echo $venta[14];?></div></td>
    <td><div align="center"><? echo $venta[11];?></div></td>
    <td><div align="center"><? echo $venta[10];?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><? echo $venta[8]* $venta[7];?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><? echo $venta[8]*($venta[13]/100);?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><? echo $venta[8]*($venta[14]/100);?></div></td>
  </tr>
  <?
}
?>
</table>
</body>
</html>
