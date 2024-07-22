<?
	session_name('app_admin');
	include("conex.php");
	//Variable que nos dice si hay que cargar la venta o no
	$cargar_venta="no";	
	include("local_controla.php");
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$ventas_fact=mysqli_query($mysqli,"SELECT * FROM ventas_facturacion WHERE mes=".$array_fecha['mon']);
	$venta_fact=mysqli_fetch_array($ventas_fact);
	if(!$reg=mysqli_query($mysqli,"SELECT dni, registrados.nombre as nomreg, apellido, actividad.nombre as nomact FROM registrados, actividad WHERE actividad=id_actividad ORDER BY	apellido"))
	/*SELECT dni, registrados.nombre as nomreg, apellido, actividad.nombre as nomact, profesor.nombre as nomprofe FROM registrados, actividad, profesor WHERE actividad=id_actividad AND profesor=id_profesor ORDER BY	apellido CONSULTA VIEJA, MOSTRANDO LOS PROFESORES*/ 
	{
		echo mysqli_error($mysqli);
		exit();
	}
	//Boton agregar
	if(isset($_POST['agregar']) or isset($_POST['terminar']))
	{
		$mostrar_registrados=0;
		
		//Limpio la tabla temporal
		$id_usuario=$_SESSION['usuario_act'];
		mysqli_query($mysqli,"DELETE FROM ventas_temporal WHERE id_usuario='$id_usuario'");
		for($i=0;$i<$_SESSION['cant_items'];$i++)
		{
			$productos_categorias=mysqli_query($mysqli,"SELECT * FROM productos WHERE cod=".$_POST['producto'][$i]);
			$producto_categoria=mysqli_fetch_array($productos_categorias);
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
				mysqli_query($mysqli,"INSERT INTO ventas_temporal (cod_producto, cant, id_usuario) VALUES ('$cod_producto','$cantidad','$id_usuario')");
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
		if(!$productos_temporales=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, descripcion, margen, marcas.nombre as nm, cant, productos.cod_cat as cate FROM productos, categorias, marcas, ventas_temporal WHERE productos.cod=ventas_temporal.cod_producto AND productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND id_usuario='$id_usuario' ORDER BY productos.nombre"))
		{
			echo mysqli_error($mysqli);
			exit();
		}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">

<script src="js/jquery-3.6.0.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script>
	function buscar_pagos(dni)
	{
		
		$.ajax({
			url: 'buscar_pagos.php',
			data: 'dni_cli=' + dni,
			success: function(resp) {
				$('#pagos_cli').html(resp)
			}
		});
	}
</script>
</head>

<table class="table">
<tr><td>Producto</td>
<td>Cantidad</td>
<td>Precio Un.</td>
<td>Subtotal</td>
</tr>
<?
		$total=0;
		while($producto_temporal=mysqli_fetch_array($productos_temporales))
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
	$formas_pago=mysqli_query($mysqli,"SELECT * FROM ventas_forma_pago WHERE id_forma=".$_POST['pago']);
	$forma_pago=mysqli_fetch_array($formas_pago);
	echo "Forma de pago: ".$forma_pago['nombre']."<br>";
	if($_POST['pago']<>1)
	{
		echo "En efectivo: $".$_POST['efectivo'];
		echo " Saldo: $".($total_definitivo-$_POST['efectivo'])."<p></p>";
	}
?>
<?
//mensaje sobre facturacion
//$facturatipo=mysqli_query("SELECT productos.id_proveedor, proveedores.id_proveedor, tipo FROM productos, proveedores WHERE productos.id_proveedor=proveedores.id_proveedor AND cod=".$_SESSION['cod_producto'],$mysqli);
//$facturatip=mysqli_fetch_array($facturatipo);
//if($facturatip['tipo']==1 or $_POST['pago']<>1)
if($mostrar_registrados==1)
{
	if($venta_fact['ventamesgim']<$venta_fact['ventapgim'])
	{
		echo "FACTURAR";	
	}
?>
	<form action="app_profeventames_procesa2.php" method="post" enctype="multipart/form-data">
	<select name="registrado" id="registrado" onchange="buscar_pagos(this.value)">
      <?
	while($re=mysqli_fetch_array($reg))
	{
?>
      <option value="<? echo $re['dni'];?>"><? echo $re['apellido']." ".$re['nomreg']." ".$re['nomact']." "/*.$re['nomprofe']*/;?></option>	
      <?
		           	
	}

?>  
	<option value="0" selected="selected">Seleccione cliente...</option>    
    </select>
    
    <input value="Confirmar" type="submit" />
    </form><br>
    <div id="pagos_cli">
    
    </div>
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
		if(mysqli_query("INSERT INTO ventas (id_usuario, fecha, hora, id_forma) VALUES ('$id_usuario', '$fecha', '$hora4', '$id_forma')",$mysqli))
		{
			$id_venta=mysqli_insert_id($mysqli);
			$error=0;
			//Cargamos el detalle de la venta
			$temporales=mysqli_query("SELECT * FROM ventas_temporal",$mysqli);
			while($temporal=mysqli_fetch_array($temporales))
			{
				if($temporal['cant']>0)
				{
					$cod_producto=$temporal['cod_producto'];
					$cantidad=$temporal['cant'];
					//Buscamos y calculamos el precio del producto
					$productos=mysqli_query("SELECT * FROM productos, categorias WHERE categorias.cod=cod_cat AND productos.cod='$cod_producto'",$mysqli);
					$producto=mysqli_fetch_array($productos);
					$precio=$producto['costo']*$producto['margen'];
					if(!mysqli_query("INSERT INTO ventas_detalle (id_venta, cod_producto, cantidad, precio) VALUES ('$id_venta', '$cod_producto', '$cantidad', '$precio')",$mysqli))
					{
						echo mysqli_error($mysqli);
					}
					//Actualizamos stock
					mysqli_query("UPDATE productos SET stock=stock-'$cantidad' WHERE cod='$cod_producto'",$mysqli);
					//Borramos la tabla temporal
					mysqli_query("DELETE FROM ventas_temporal",$mysqli);
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


<body>
<p></p>
<table class="table">
  <tr>
    <td><a href="app_profeventames.php">Volver</a></td>
    <?
	if($mostrar_registrados==0)
	{
	?>
    	<td><a href="app_profeventames_procesa2.php?bar=1">Confirmar</a></td>
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
