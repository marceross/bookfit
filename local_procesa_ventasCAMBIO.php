<?
	include("conex.php");
	//Variable que nos dice si hay que cargar la venta o no
	$cargar_venta="no";	
	include("local_controla.php");
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$ventas_fact=mysql_query("SELECT * FROM ventas_facturacion WHERE mes=".$array_fecha['mon'],$link);
	$venta_fact=mysql_fetch_array($ventas_fact);
	if(!$reg=mysql_query("SELECT dni, registrados.nombre as nomreg, apellido, actividad.nombre as nomact, profesor.nombre as nomprofe FROM registrados, actividad, profesor WHERE actividad=id_actividad AND profesor=id_profesor",$link))
	{
		echo mysql_error($link);
		exit();
	}
	//Boton agregar
	if(isset($_POST['agregar']) or isset($_POST['terminar']))
	{
		$mostrar_registrados=0;
		
		//Limpio la tabla temporal
		$id_usuario=$_SESSION['usuario_act'];
		mysql_query("DELETE FROM ventas_temporal WHERE id_usuario='$id_usuario'",$link);
		for($i=0;$i<$_SESSION['cant_items'];$i++)
		{
			$productos_categorias=mysql_query("SELECT * FROM productos WHERE cod=".$_POST['producto'][$i],$link);
			$producto_categoria=mysql_fetch_array($productos_categorias);
			// categorias para editar... son las que van a mostrar el nombre del socio antes de facturar y van a mostrar el mensaje de facturacion
			if($producto_categoria['cod_cat']==5 or $producto_categoria['cod_cat']==8)
			{
				$mostrar_registrados=1;
			}
						
			$_SESSION['cod_producto']=$cod_producto=$_POST['producto'][$i];
			$_SESSION['cantidad']=$cantidad=$_POST['cant'][$i];		
			if($cantidad<>"")
			{
				$cargar_venta="si";
				mysql_query("INSERT INTO ventas_temporal (cod_producto, cant, id_usuario) VALUES ('$cod_producto','$cantidad','$id_usuario')",$link);
			}
		}
		if(isset($_POST['agregar']))
		{
			header("Location:local_ventas.php");
		}
	}	
	if(isset($_POST['terminar']) and $cargar_venta=="si")
	{	
		
		$_SESSION['recargo']=$recargo=$_POST['recargo'];
		$_SESSION['descuento']=$descuento=$_POST['descuento'];
		$_SESSION['pago']=$pago=$_POST['pago'];
		$_SESSION['efectivo']=$efectivo=$_POST['efectivo'];
		//Mostrar resumen
		if(!$productos_temporales=mysql_query("SELECT productos.cod as cp, productos.nombre as np, costo, descripcion, margen, marcas.nombre as nm, cant, productos.cod_cat as cate FROM productos, categorias, marcas, ventas_temporal WHERE productos.cod=ventas_temporal.cod_producto AND productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND id_usuario='$id_usuario' ORDER BY productos.nombre",$link))
		{
			echo mysql_error($link);
			exit();
		}
?>
<table border="1">
<tr><td>Producto</td>
<td>Cantidad</td>
<td>Precio Un.</td>
<td>Subtotal</td>
</tr>
<?
		$total=0;
		while($producto_temporal=mysql_fetch_array($productos_temporales))
		{
			$total=$total+$producto_temporal['costo']*$producto_temporal['margen']*$producto_temporal['cant'];		
?>		
	<tr><td>    
<?    
			echo $producto_temporal['np']." ".$producto_temporal['nm']." ".$producto_temporal['descripcion']." ($".round ($producto_temporal['costo']*$producto_temporal['margen']).")<br>";
?>
		</td>			
        <td><? echo $producto_temporal['cant'];?></td>
        <td><? echo "$".round ($producto_temporal['costo']*$producto_temporal['margen']);?></td>
        <td><? echo "$".round ($producto_temporal['costo']*$producto_temporal['margen']*$producto_temporal['cant']);?></td>
        </tr>
<?        
		}
		$total_definitivo=$total+($total-$efectivo)*$recargo/100-$total*$descuento/100;
		$_SESSION['tot_venta']=$total_definitivo;
?>
	<tr><td>RECARGO</td><td></td><td></td><td><? echo "$".($total-$efectivo)*$recargo/100;?></td></tr>
    <tr><td>DESCUENTO</td><td></td><td></td><td><? echo "$".$total*$descuento/100;?></td></tr>
	<tr><td>TOTAL</td><td></td><td></td><td><? echo "$".$total_definitivo;?></td></tr>
</table>		
<?
	$formas_pago=mysql_query("SELECT * FROM ventas_forma_pago WHERE id_forma=".$_POST['pago'],$link);
	$forma_pago=mysql_fetch_array($formas_pago);
	echo "Forma de pago: ".$forma_pago['nombre']."<br>";
	if($_POST['pago']<>1)
	{
		echo "En efectivo: $".$_POST['efectivo'];
		echo " Saldo: $".($total_definitivo-$_POST['efectivo'])."<p></p>";
	}
?>
<font color="#FF0000" size="6"><strong>
<?
//mensaje sobre facturacion
//$facturatipo=mysql_query("SELECT productos.id_proveedor, proveedores.id_proveedor, tipo FROM productos, proveedores WHERE productos.id_proveedor=proveedores.id_proveedor AND cod=".$_SESSION['cod_producto'],$link);
//$facturatip=mysql_fetch_array($facturatipo);
//if($facturatip['tipo']==1 or $_POST['pago']<>1)
if($mostrar_registrados==1)
{
	if($venta_fact['ventamesgim']<$venta_fact['ventapgim'])
	{
		echo "FACTURAR";	
	}
?>
	<form action="local_procesa_ventas2.php" method="post" enctype="multipart/form-data">
	<select name="registrado" id="registrado">
      <?
	while($re=mysql_fetch_array($reg))
	{
?>
      <option value="<? echo $re['dni'];?>"><? echo $re['nomreg']." ".$re['apellido']." ".$re['nomact']." ".$re['nomprofe'];?></option>	
      <?
		           	
	}

?>      
    </select>
    
    <input value="Confirmar" type="submit" />
    </form>
<?
	//{
	//	echo "FACTURAR";
	//}
}
else
{
	if($venta_fact['ventamesbar']<$venta_fact['ventapbar'])
	{
		echo "FACTURAR";	
	}
}
?>
</strong></font>
<?
		/*		
		$id_usuario=$_SESSION['usuario_act'];
		//Obtiene la fecha y hora
		$array_fecha=getdate();
		$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
		//$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
		$hora4=date("H:i:s",(time()+4*3600));
		$id_forma=$_POST['pago'];
		//Creamos el registro en la tabla ventas
		if(mysql_query("INSERT INTO ventas (id_usuario, fecha, hora, id_forma) VALUES ('$id_usuario', '$fecha', '$hora4', '$id_forma')",$link))
		{
			$id_venta=mysql_insert_id($link);
			$error=0;
			//Cargamos el detalle de la venta
			$temporales=mysql_query("SELECT * FROM ventas_temporal",$link);
			while($temporal=mysql_fetch_array($temporales))
			{
				if($temporal['cant']>0)
				{
					$cod_producto=$temporal['cod_producto'];
					$cantidad=$temporal['cant'];
					//Buscamos y calculamos el precio del producto
					$productos=mysql_query("SELECT * FROM productos, categorias WHERE categorias.cod=cod_cat AND productos.cod='$cod_producto'",$link);
					$producto=mysql_fetch_array($productos);
					$precio=$producto['costo']*$producto['margen'];
					if(!mysql_query("INSERT INTO ventas_detalle (id_venta, cod_producto, cantidad, precio) VALUES ('$id_venta', '$cod_producto', '$cantidad', '$precio')",$link))
					{
						echo mysql_error($link);
					}
					//Actualizamos stock
					mysql_query("UPDATE productos SET stock=stock-'$cantidad' WHERE cod='$cod_producto'",$link);
					//Borramos la tabla temporal
					mysql_query("DELETE FROM ventas_temporal",$link);
					//Volvemos a inicio y destruimos la variable autentificado
					if($_SESSION['tipo_usuario_act']==2)
					{
						unset($_SESSION['autentificado']);
						header("Location: local_inicio.php");
					}
					else
					{
						header("Location:local_datos.php");
					}
				}
			}
		}
		else
		{
			$error=3;
		}*/
		
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<p></p>
<table width="463" border="0" cellspacing="0">
  <tr>
    <td width="290"><a href="local_ventas.php">Volver</a></td>
    <?
	if($mostrar_registrados==0)
	{
	?>
    	<td width="169"><a href="local_procesa_ventas2.php?bar=1">Confirmar</a></td>
    <?
	}
	?>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><strong>1- COBRAR</strong> (tarjeta o efectivo, Atencion IVA en caso de factura A)<br />
  <strong>2- FACTURAR</strong> ( &quot;A&quot; solo si traen comprobante de inscripcion o se logra bajar de la pagina de afip)<br />
  (el monto en la factura es el que figura en el TOTAL, si es premio se factura $0 o como si fuera una venta con 100% de descuento)<br />
<strong>3- CONFIRMAR VENTAS</strong> (antes revisar monto, recargo, descuento y forma de pago) </p>
</body>
</html>
