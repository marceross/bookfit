<?
	include("conex.php");
	include("local_controla.php"); 	// session_name('app_admin'); session_start(); se agrega si no está el controla	
	$id_usuario=$_SESSION['usuario_act'];
	$error=0;
	if(isset($_POST['dni_buscado']))
	{
		if($_POST['dni_buscado']<>'')
		{
			$dni_registrado=$_POST['dni_buscado'];
		}
		else
		{
			$error=1;
		}
	}

	if($error==0)
	{

		//Obtiene la fecha y hora
		$array_fecha=getdate();
		$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
		//$hora4=date("H:i:s",(time()-3*3600));
		$hora4=date("H:i:s",time());
		$id_forma=$_SESSION['pago'];
		
		//--------------------------------------------------------------------
		//Creamos el registro en la tabla ventas
		//if(mysqli_query($mysqli,"INSERT INTO ventas (id_usuario, fecha, hora, id_forma, descuento, recargo, efectivo, id_registrados) VALUES ('$id_usuario', '$fecha', '$hora4', '$id_forma', '$descuento', '$recargo', '$efectivo', '$dni_registrado')"))
		if(mysqli_query($mysqli,"INSERT INTO ventas (id_usuario, fecha, hora, id_forma, id_registrados) VALUES ('$id_usuario', '$fecha', '$hora4', '$id_forma', '$dni_registrado')"))
		{
			$id_venta=mysqli_insert_id($mysqli);
			
			//--------------------------------------------------------------------
			//Actualizamos venta mensual
			if(isset($_GET['bar']))
			{
				if(!mysqli_query($mysqli,"UPDATE ventas_facturacion SET ventamesbar=ventamesbar + ".$_SESSION['tot_venta']." WHERE mes=".$array_fecha['mon']))
				{
					echo mysqli_error($mysqli);
					exit();
				}
			}
			else
			{
				if(!mysqli_query($mysqli,"UPDATE ventas_facturacion SET ventamesgim=ventamesgim + ".$_SESSION['tot_venta']." WHERE mes=".$array_fecha['mon']))
				{
					echo mysqli_error($mysqli);
					exit();
				}		
			}
			
			
			//Cargamos el detalle de la venta
			$temporales=mysqli_query($mysqli,"SELECT * FROM ventas_temporal WHERE id_usuario='$id_usuario'");
			while($temporal=mysqli_fetch_array($temporales))
			{
				if($temporal['cant']>0)
				{
					$cod_producto=$temporal['cod_producto'];
					$cantidad=$temporal['cant'];

					//--------------------------------------------------------------------
					//Buscamos y calculamos el precio del producto
					$productos=mysqli_query($mysqli,"SELECT * FROM productos, categorias WHERE categorias.cod=cod_cat AND productos.cod='$cod_producto'");
					$producto=mysqli_fetch_array($productos);
					$precio=$producto['costo']*$producto['margen'];
					if(!mysqli_query($mysqli,"INSERT INTO ventas_detalle (id_venta, cod_producto, cantidad, precio) VALUES ('$id_venta', '$cod_producto', '$cantidad', '$precio')"))
					{
						echo mysqli_error($mysqli);
					}


					//--------------------------------------------------------------------
					// Actualizo crédito
					/*$registrados=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$dni_registrado'");
					$registrado=mysqli_fetch_array($registrados);
					
					$credito_viejo=$registrado['credito'];
					$credito_compra=$producto['cantp'];*/

					/*if(strtotime($registrado['vencimiento'])<strtotime($fecha))
					{					
						//si esta vencida se suman solo los puntos nuevos
						$credito_nuevo=($credito_viejo-$credito_viejo+$credito_compra);
					}
					else//si no esta vencida se suman los puntos nuevos a los viejos
					{
						$credito_nuevo=($credito_viejo+$credito_compra); 							
					}


					
					if(!mysqli_query($mysqli,"UPDATE registrados SET credito='$credito_nuevo' WHERE dni='$dni_registrado'"))
					{
						echo  "Error: ".mysqli_error($mysqli);
						exit();
					}*/


					//--------------------------------------------------------------------
					//Actualizo stock
					mysqli_query($mysqli,"UPDATE productos SET stock=stock-'$cantidad' WHERE cod='$cod_producto'");


					//--------------------------------------------------------------------
					//Actualizo fecha de vencimiento y credito
					$duracion=$producto['duracion'];// paquete		
					
					//Busco la fecha de vencimiento actual
					$registrados=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$dni_registrado'");
					$registrado=mysqli_fetch_array($registrados);
					$credito_viejo=$registrado['credito'];
					$credito_compra=$producto['cantp'];

					//Verifico si la fecha de vencimiento actual esta vencida
					//echo "Vto: ".strtotime($registrado['vencimiento'])." Hoy: ".strtotime($fecha);
					//exit();
					if(strtotime($registrado['vencimiento'])<strtotime($fecha))
					{					
						//si esta vencida se suman solo los dias que compra
						$credito_nuevo=($credito_viejo-$credito_viejo+$credito_compra);
						$vencimiento=date("Y-m-d",strtotime($fecha."+ ".$duracion." days"));
					}
					else//si no esta vencida se suman a la fecha vieja los dias que se agregan
					{
						$credito_nuevo=($credito_viejo+$credito_compra);
						$vencimiento=date("Y-m-d",strtotime($registrado['vencimiento']."+ ".$duracion." days")); 							
					}
					
					if(!mysqli_query($mysqli,"UPDATE registrados SET vencimiento='$vencimiento', credito='$credito_nuevo' WHERE dni='$dni_registrado'"))
					{
						echo mysqli_error($mysqli);
						exit();
					}


					//Borro la tabla temporal
					//mysqli_query($mysqli,"DELETE FROM ventas_temporal");
					//Volvemos a inicio y destruimos la variable autentificado
					
					$_SESSION['pago']=1;				
					header("Location:app_profe.php");
					
				}
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

</head>
<body>
	<?php
		if($error==1)
		{
			echo  "El campo DNI es obligatorio";
		}
	?>
</body>
</html>

<?
// ERA local_procesa_ventas2.php
?>
