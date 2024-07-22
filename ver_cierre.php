<?
include ("conex.php");
include("local_controla2.php");
include("biblioteca.php");
//Leemos la fecha del dia
if(isset($_POST['dia']))
{
	$_SESSION['dia']=$_POST['dia'];
	$_SESSION['mes']=$_POST['mes'];	
	$_SESSION['anio']=$_POST['anio'];
	$_SESSION['fecha']=$_POST['anio']."/".$_POST['mes']."/".$_POST['dia'];
}
if(!isset($_SESSION['dia']))
{	
	$_SESSION['fecha'] = date('Y-m-d',time()-(24*60*60));
}
$_SESSION['dia']=substr($_SESSION['fecha'],8,2);
$_SESSION['mes']=substr($_SESSION['fecha'],5,2);
$_SESSION['anio']=substr($_SESSION['fecha'],0,4);
$_POST['dia']=$_SESSION['dia'];
$_POST['mes']=$_SESSION['mes'];
$_POST['anio']=$_SESSION['anio'];
if(isset($_GET['accion']))
{
	if($_GET['accion']=='atras')
	{	
		$_SESSION['fecha'] = date('Y-m-d',strtotime($_SESSION['fecha'])-(24*60*60));
		$_SESSION['dia']=substr($_SESSION['fecha'],8,2);
		$_SESSION['mes']=substr($_SESSION['fecha'],5,2);
		$_SESSION['anio']=substr($_SESSION['fecha'],0,4);
		$_POST['dia']=$_SESSION['dia'];
		$_POST['mes']=$_SESSION['mes'];
		$_POST['anio']=$_SESSION['anio'];
	}
}
if(!isset($_POST['dia']))
{
	//Caja inicial
	$ultima_fecha=mysqli_query($mysqli,"SELECT MAX(Fecha) FROM caja WHERE fecha<>CURDATE()");
	$ult_fecha=mysqli_fetch_array($ultima_fecha);
	$caja_anterior=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja WHERE fecha='".$fecha_solicitada."' AND hora>='08:00:00' AND hora<='15:00:00'");
	$datos_caja_anterior=mysqli_fetch_array($caja_anterior);
	//Ventas
	$ventas=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$fecha_solicitada."' AND id_forma=1 AND hora>='08:00:00' AND hora<='15:00:00'");
	//Sumamos las ventas
	//+($venta['efectivo'])
	$total_ventas=0;
	while($venta=mysqli_fetch_array($ventas))
	{
		$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
	}
	//Sumamos parte en efectivo de las ventas hechas con tarjeta 
	$ventas_tarjetas=mysqli_query($mysqli,"SELECT * FROM ventas WHERE fecha='".$fecha_solicitada."' AND (id_forma=2 OR id_forma=3) AND efectivo>0 AND hora>='08:00:00' AND hora<='15:00:00'");
	//Sumamos al total de ventas
	while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
	{
		$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
	}
	//Aportes
	$aportes=mysqli_query($mysqli,"SELECT * FROM caja_aporte WHERE fecha='".$fecha_solicitada."' AND hora>='08:00:00' AND hora<='15:00:00'");
	$total_aportes=0;
	while($aporte=mysqli_fetch_array($aportes))
	{
		$total_aportes=$total_aportes+$aporte['billetes']+$aporte['monedas'];
	}
	//Extracciones
	$extracciones=mysqli_query($mysqli,"SELECT * FROM caja_extraccion WHERE fecha='".$fecha_solicitada."' AND hora>='08:00:00' AND hora<='15:00:00'");
	$total_extracciones=0;
	while($extraccion=mysqli_fetch_array($extracciones))
	{
		$total_extracciones=$total_extracciones+$extraccion['billetes']+$extraccion['monedas'];
	}
	//Caja actual
	$ultima_fecha=mysqli_query($mysqli,"SELECT MAX(Fecha) FROM caja_cierre WHERE fecha<>CURDATE()");
	$ult_fecha=mysqli_fetch_array($ultima_fecha);
	$caja_actual=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja_cierre WHERE fecha='".$fecha_solicitada."' AND hora>='12:00:00' AND hora<='15:00:00'");
	$datos_caja_actual=mysqli_fetch_array($caja_actual);
	//$caja=mysqli_query("SELECT caja_aporte.billetes, caja_aporte.monedas, caja_aporte.fecha, caja.billetes, caja.monedas, caja.fecha, caja_extracion.billetes, caja_extraccion.monedas, caja_extraccion.fecha, ventas. ventas_forma_pago.nombre  FROM caja_aporte, caja, caja_extraccion, ventas, ventas_detalle, ventas_forma_pago WHERE caja_aporte.id_usuario=usuarios.id_usuario ORDER BY caja_aporte.fecha DESC",$mysqli);
}
else
{
	$fecha_solicitada=$_POST['anio']."-".$_POST['mes']."-".$_POST['dia'];
	//Caja inicial
	$caja_anterior=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja WHERE fecha='".$fecha_solicitada."' AND hora>='08:00:00' AND hora<='15:00:00'");
	$datos_caja_anterior=mysqli_fetch_array($caja_anterior);
	//Ventas
	$ventas=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$fecha_solicitada."' AND id_forma=1 AND hora>='08:00:00' AND hora<='15:00:00'");
	//Sumamos las ventas
	//+($venta['efectivo'])
	$total_ventas=0;
	while($venta=mysqli_fetch_array($ventas))
	{
		$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
	}
	//Sumamos parte en efectivo de las ventas hechas con tarjeta 
	$ventas_tarjetas=mysqli_query($mysqli,"SELECT * FROM ventas WHERE fecha='".$fecha_solicitada."' AND (id_forma=2 OR id_forma=3) AND efectivo>0 AND hora>='08:00:00' AND hora<='15:00:00'");
	//Sumamos al total de ventas
	while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
	{
		$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
	}
	//Aportes
	$aportes=mysqli_query($mysqli,"SELECT * FROM caja_aporte WHERE fecha='".$fecha_solicitada."' AND hora>='08:00:00' AND hora<='15:00:00'");
	$total_aportes=0;
	while($aporte=mysqli_fetch_array($aportes))
	{
		$total_aportes=$total_aportes+$aporte['billetes']+$aporte['monedas'];
	}
	//Extracciones
	$extracciones=mysqli_query($mysqli,"SELECT * FROM caja_extraccion WHERE fecha='".$fecha_solicitada."' AND hora>='08:00:00' AND hora<='15:00:00'");
	$total_extracciones=0;
	while($extraccion=mysqli_fetch_array($extracciones))
	{
		$total_extracciones=$total_extracciones+$extraccion['billetes']+$extraccion['monedas'];
	}
	//Caja actual
	$caja_actual=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja_cierre WHERE fecha='".$fecha_solicitada."' AND hora>='12:00:00' AND hora<='15:00:00'");
	$datos_caja_actual=mysqli_fetch_array($caja_actual);
	//$caja=mysqli_query("SELECT caja_aporte.billetes, caja_aporte.monedas, caja_aporte.fecha, caja.billetes, caja.monedas, caja.fecha, caja_extracion.billetes, caja_extraccion.monedas, caja_extraccion.fecha, ventas. ventas_forma_pago.nombre  FROM caja_aporte, caja, caja_extraccion, ventas, ventas_detalle, ventas_forma_pago WHERE caja_aporte.id_usuario=usuarios.id_usuario ORDER BY caja_aporte.fecha DESC",$mysqli);
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
<p><strong>CIERRE CAJA</strong></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="ver_cierre.php?exp=1">Exportar a Excel</a></p>
<form name="form1" method="post" action="ver_cierre.php">
  <div align="center">buscar fecha 
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
    <option value="<? echo $d;?>"> <? echo $d;?> </option>
    <?
	}
	else
	{
?>
    <option selected value="<? echo $d;?>"> <? echo $d;?> </option>
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
    <option value="<? echo $d;?>"> <? echo $d;?> </option>
    <?
		}
		else
		{
?>
    <option selected value="<? echo $d;?>"> <? echo $d;?> </option>
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
    <option value="<? echo $i;?>"> <? echo $i;?> </option>
    <?
						}
						else
						{
?>
    <option selected value="<? echo $i;?>"> <? echo $i;?> </option>
    <?
						}                            
					}
?>
  </select>
    dd/mm/aaaa 
    <label>
    <input type="submit" name="button" id="button" value="Aceptar">
    </label>
    <br>
  <a href="ver_cierre.php?accion=atras">&lt;&lt; Atras </a></div>
</form>
<p align="center">&nbsp;</p>
<p align="center">CIERRE MA&Ntilde;ANA</p>
<table width="415" border="1" align="center">
  <?
//while($caj=mysqli_fetch_array($caja))
//{
?>
  <tr> 
    <td width="112"><div align="left"></div></td>
    <td width="127"><div align="center"><strong>fecha</strong><br>
      </div></td>
    <td width="281"><div align="center"><strong>$</strong></div></td>
  </tr>
  <tr> 
    <td><div align="left"><strong>caja ma&ntilde;ana</strong></div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $datos_caja_anterior['billetes']+$datos_caja_anterior['monedas'];?></td>
  </tr>
  <tr> 
    <td><div align="left"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFCC"> 
    <td><div align="left"><strong><font color="#009900">ventas (+)</font></strong></div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $total_ventas;?></td>
  </tr>
  <tr> 
    <td><div align="left"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="left"><strong><font color="#009900">aporte (+)</font></strong><br>
      </div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $total_aportes;?></td>
  </tr>
  <tr bgcolor="#FFFFCC"> 
    <td><div align="left"><strong><font color="#FF0000">extraccion (-)</font></strong><br>
      </div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $total_extracciones;?></td>
  </tr>
  <tr> 
    <td><div align="left"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="left"><strong>caja final</strong><br>
      </div></td>
    <td>&nbsp;</td>
    <td>
<?
	$caja_final=$datos_caja_anterior['billetes']+$datos_caja_anterior['monedas']+$total_ventas+$total_aportes-$total_extracciones;
	echo $caja_final;
?>    
	</td>
  </tr>
  <tr> 
    <td><div align="left">caja contada<br>
      </div></td>
    <td><? 
	if(!isset($_POST['dia']))
	{
		echo formato_latino($fecha_solicitada);
	}
	else
	{
		echo formato_latino($fecha_solicitada);
	}
	?></td>
    <td><? echo $datos_caja_actual['billetes']+$datos_caja_actual['monedas'];?></td>
  </tr>
  <tr> 
    <td><div align="left"></div></td>
    <td>error</td>
    <td><? echo ($datos_caja_actual['billetes']+$datos_caja_actual['monedas'])-($caja_final);?></td>
  </tr>
   <?
//}
?>
</table>

<?
//	CALCULO PARA LA TARDE--------------------------------------------------------------------------
if(!isset($_POST['dia']))
{
	//Caja inicial
	$ultima_fecha=mysqli_query($mysqli,"SELECT MAX(Fecha) FROM caja WHERE fecha<>CURDATE()");
	$ult_fecha=mysqli_fetch_array($ultima_fecha);
	$caja_anterior=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja WHERE fecha='".$fecha_solicitada."' hora>='15:00:00' AND hora<='23:59:00'");
	$datos_caja_anterior=mysqli_fetch_array($caja_anterior);
	//Ventas
	$ventas=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$fecha_solicitada."' AND id_forma=1 AND hora>='15:00:00' AND hora<='23:59:00'");
	//Sumamos las ventas
	//+($venta['efectivo'])
	$total_ventas=0;
	while($venta=mysqli_fetch_array($ventas))
	{
		$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
	}
	//Sumamos parte en efectivo de las ventas hechas con tarjeta 
	$ventas_tarjetas=mysqli_query($mysqli,"SELECT * FROM ventas WHERE fecha='".$fecha_solicitada."' AND (id_forma=2 OR id_forma=3) AND efectivo>0 AND hora>='15:00:00' AND hora<='23:59:00'");
	//Sumamos al total de ventas
	while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
	{
		$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
	}
	//Aportes
	$aportes=mysqli_query($mysqli,"SELECT * FROM caja_aporte WHERE fecha='".$fecha_solicitada."' AND hora>='15:00:00' AND hora<='23:59:00'");
	$total_aportes=0;
	while($aporte=mysqli_fetch_array($aportes))
	{
		$total_aportes=$total_aportes+$aporte['billetes']+$aporte['monedas'];
	}
	//Extracciones
	$extracciones=mysqli_query($mysqli,"SELECT * FROM caja_extraccion WHERE fecha='".$fecha_solicitada."' AND hora>='15:00:00' AND hora<='23:59:00'");
	$total_extracciones=0;
	while($extraccion=mysqli_fetch_array($extracciones))
	{
		$total_extracciones=$total_extracciones+$extraccion['billetes']+$extraccion['monedas'];
	}
	//Caja actual
	$ultima_fecha=mysqli_query($mysqli,"SELECT MAX(Fecha) FROM caja_cierre WHERE fecha<>CURDATE()");
	$ult_fecha=mysqli_fetch_array($ultima_fecha);
	$caja_actual=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja_cierre WHERE fecha='".$fecha_solicitada."' AND hora>='19:00:00' AND hora<='23:59:00'");
	$datos_caja_actual=mysqli_fetch_array($caja_actual);
	//$caja=mysqli_query("SELECT caja_aporte.billetes, caja_aporte.monedas, caja_aporte.fecha, caja.billetes, caja.monedas, caja.fecha, caja_extracion.billetes, caja_extraccion.monedas, caja_extraccion.fecha, ventas. ventas_forma_pago.nombre  FROM caja_aporte, caja, caja_extraccion, ventas, ventas_detalle, ventas_forma_pago WHERE caja_aporte.id_usuario=usuarios.id_usuario ORDER BY caja_aporte.fecha DESC",$mysqli);
}
else
{
	//Caja inicial	
	$caja_anterior=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja WHERE fecha='".$fecha_solicitada."' AND hora>='15:00:00' AND hora<='23:59:00'");
	$datos_caja_anterior=mysqli_fetch_array($caja_anterior);
	//Ventas
	$ventas=mysqli_query($mysqli,"SELECT * FROM ventas, ventas_detalle WHERE ventas.id_venta=ventas_detalle.id_venta AND fecha='".$fecha_solicitada."' AND id_forma=1 AND hora>='15:00:00' AND hora<='23:59:00'");
	//Sumamos las ventas
	//+($venta['efectivo'])
	$total_ventas=0;
	while($venta=mysqli_fetch_array($ventas))
	{
		$total_ventas=$total_ventas+($venta['precio']*$venta['cantidad'])-(($venta['descuento']*($venta['precio']*$venta['cantidad'])/100));
	}
	//Sumamos parte en efectivo de las ventas hechas con tarjeta 
	$ventas_tarjetas=mysqli_query($mysqli,"SELECT * FROM ventas WHERE fecha='".$fecha_solicitada."' AND (id_forma=2 OR id_forma=3) AND efectivo>0 AND hora>='15:00:00' AND hora<='23:59:00'");
	//Sumamos al total de ventas
	while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
	{
		$total_ventas=$total_ventas+$venta_tarjeta['efectivo'];
	}
	//Aportes
	$aportes=mysqli_query($mysqli,"SELECT * FROM caja_aporte WHERE fecha='".$fecha_solicitada."' AND hora>='15:00:00' AND hora<='23:59:00'");
	$total_aportes=0;
	while($aporte=mysqli_fetch_array($aportes))
	{
		$total_aportes=$total_aportes+$aporte['billetes']+$aporte['monedas'];
	}
	//Extracciones
	$extracciones=mysqli_query($mysqli,"SELECT * FROM caja_extraccion WHERE fecha='".$fecha_solicitada."' AND hora>='15:00:00' AND hora<='23:59:00'");
	$total_extracciones=0;
	while($extraccion=mysqli_fetch_array($extracciones))
	{
		$total_extracciones=$total_extracciones+$extraccion['billetes']+$extraccion['monedas'];
	}
	//Caja actual
	$caja_actual=mysqli_query($mysqli,"SELECT billetes, monedas, fecha FROM caja_cierre WHERE fecha='".$fecha_solicitada."' AND hora>='19:00:00' AND hora<='23:59:00'");
	$datos_caja_actual=mysqli_fetch_array($caja_actual);
	//$caja=mysqli_query("SELECT caja_aporte.billetes, caja_aporte.monedas, caja_aporte.fecha, caja.billetes, caja.monedas, caja.fecha, caja_extracion.billetes, caja_extraccion.monedas, caja_extraccion.fecha, ventas. ventas_forma_pago.nombre  FROM caja_aporte, caja, caja_extraccion, ventas, ventas_detalle, ventas_forma_pago WHERE caja_aporte.id_usuario=usuarios.id_usuario ORDER BY caja_aporte.fecha DESC",$mysqli);
}	
?>
<p align="center">CIERRE TARDE</p>
<table width="415" border="1" align="center">
  <?
//while($caj=mysqli_fetch_array($caja))
//{
?>
  <tr>
    <td width="112"><div align="left"></div></td>
    <td width="127"><div align="center"><strong>fecha</strong><br>
    </div></td>
    <td width="281"><div align="center"><strong>$</strong></div></td>
  </tr>
  <tr>
    <td><div align="left"><strong>caja tarde</strong></div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $datos_caja_anterior['billetes']+$datos_caja_anterior['monedas'];?></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td><div align="left"><strong><font color="#009900">ventas (+)</font></strong></div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $total_ventas;?></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="left"><strong><font color="#009900">aporte (+)</font></strong><br>
    </div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $total_aportes;?></td>
  </tr>
  <tr bgcolor="#FFFFCC">
    <td><div align="left"><strong><font color="#FF0000">extraccion (-)</font></strong><br>
    </div></td>
    <td><? echo formato_latino ($datos_caja_anterior['fecha']);?></td>
    <td><? echo $total_extracciones;?></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="left"><strong>caja final</strong><br>
    </div></td>
    <td>&nbsp;</td>
    <td><?
	$caja_final=$datos_caja_anterior['billetes']+$datos_caja_anterior['monedas']+$total_ventas+$total_aportes-$total_extracciones;
	echo $caja_final;
?>
    </td>
  </tr>
  <tr>
    <td><div align="left">caja contada<br>
    </div></td>
    <td><?
	if(!isset($_POST['dia']))
	{
		echo formato_latino($ult_fecha[0]);
	}
	else
	{
		echo formato_latino($fecha_solicitada);
	}
	 ?></td>
    <td><? echo $datos_caja_actual['billetes']+$datos_caja_actual['monedas'];?></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
    <td>error</td>
    <td><? echo ($datos_caja_actual['billetes']+$datos_caja_actual['monedas'])-($caja_final);?></td>
  </tr>
  <?
//}
?>
</table>
<p><br>
  <?
	if(mysqli_num_rows($ventas)>0)
	{
		mysqli_data_seek($ventas,0);
	}
	if(mysqli_num_rows($ventas_tarjetas)>0)
	{
		mysqli_data_seek($ventas_tarjetas,0);	
	}
?>
</p>
<table width="200" border="1" align="center">
  <tr>
    <td>ventas</td>
  </tr>
<?
	while($venta=mysqli_fetch_array($ventas))
	{
?>  
  <tr>
    <td><? echo $venta['precio']*$venta['cantidad'];?></td>
  </tr>
<?
	}
	while($venta_tarjeta=mysqli_fetch_array($ventas_tarjetas))
	{
?>  
  <tr>
    <td><? echo $venta_tarjeta['efectivo'];?></td>
  </tr>
<?
	}
?>  
</table>
<p align="center">&nbsp;</p>
</body>
</html>
