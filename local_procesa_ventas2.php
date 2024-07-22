<?
	session_name('app_admin');
	include("conex.php");
	include("local_controla.php");
	//Meses en espanol
	//$nombre_meses[1]="Enero";
	//$nombre_meses[2]="Febrero";
	//$nombre_meses[3]="Marzo";
	//$nombre_meses[4]="Abril";
	//$nombre_meses[5]="Mayo";
	//$nombre_meses[6]="Junio";
	//$nombre_meses[7]="Julio";
	//$nombre_meses[8]="Agosto";
	//$nombre_meses[9]="Septiembre";
	//$nombre_meses[10]="Octubre";
	//$nombre_meses[11]="Noviembre";
	//$nombre_meses[12]="Diciembre";		
	$id_usuario=$_SESSION['usuario_act'];
	if(isset($_POST['registrado']))
	{
		$dni_registrado=$_POST['registrado'];
	}
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	
	
	//$mes_pagado=$nombre_meses[$array_fecha['mon']];	//$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	//Buscamos el ultimo mes pagado por el cliente
	//$clientes=mysqli_query($mysqli,"SELECT * FROM registrados WHERE dni='$dni_registrado'");
	//$cliente=mysqli_fetch_array($clientes);
	/*$ultimo_mes_pagado=0;
	for($i=1;$i<=12;$i++)
	{
		if($nombre_meses[$i]==$cliente['mes_pagado'])
		{
			$ultimo_mes_pagado=$i;
		}
	}
	
	if($ultimo_mes_pagado==0)
	{
		$mes_pagado=$nombre_meses[$array_fecha['mon']];
	}
	else
	{
		if($ultimo_mes_pagado<12)
		{
			$mes_pagado=$nombre_meses[$ultimo_mes_pagado+1];	
		}
		else
		{
			$mes_pagado=$nombre_meses[1];
		}
	}
	*/
	
	$hora4=date("H:i:s",(time()-3*3600));
	$id_forma=$_SESSION['pago'];
	if($_SESSION['efectivo']<>"")
	{
		$efectivo=$_SESSION['efectivo'];
	}
	else
	{
		$efectivo=0;
	}
	if($_SESSION['descuento']<>"")
	{
		$descuento=$_SESSION['descuento'];
	}
	else
	{
		$descuento=0;
	}
	if($_SESSION['recargo']<>"")
	{		
		$recargo=$_SESSION['recargo'];
	}
	else
	{
		$recargo=0;
	}
	//Creamos el registro en la tabla ventas
	if(mysqli_query($mysqli,"INSERT INTO ventas (id_usuario, fecha, hora, id_forma, descuento, recargo, efectivo, id_registrados) VALUES ('$id_usuario', '$fecha', '$hora4', '$id_forma', '$descuento', '$recargo', '$efectivo', '$dni_registrado')"))
	{
		$id_venta=mysqli_insert_id($mysqli);
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
		
		
		$error=0;
		//Cargamos el detalle de la venta
		$temporales=mysqli_query($mysqli,"SELECT * FROM ventas_temporal WHERE id_usuario='$id_usuario'");
		while($temporal=mysqli_fetch_array($temporales))
		{
			if($temporal['cant']>0)
			{
				$cod_producto=$temporal['cod_producto'];
				$cantidad=$temporal['cant'];
				//Buscamos y calculamos el precio del producto
				$productos=mysqli_query($mysqli,"SELECT * FROM productos, categorias WHERE categorias.cod=cod_cat AND productos.cod='$cod_producto'");
				$producto=mysqli_fetch_array($productos);
				$precio=$producto['costo']*$producto['margen'];
				if(!mysqli_query($mysqli,"INSERT INTO ventas_detalle (id_venta, cod_producto, cantidad, precio) VALUES ('$id_venta', '$cod_producto', '$cantidad', '$precio')"))
				{
					echo mysqli_error($mysqli);
				}
				//Actualizamos stock
				mysqli_query($mysqli,"UPDATE productos SET stock=stock-'$cantidad' WHERE cod='$cod_producto'");
				if(!mysqli_query($mysqli,"UPDATE registrados SET mes_pagado='$fecha' WHERE dni='$dni_registrado'"))
				{
					echo mysqli_error($mysqli);
				}
				//Borramos la tabla temporal
				mysqli_query($mysqli,"DELETE FROM ventas_temporal");
				//Volvemos a inicio y destruimos la variable autentificado
				if($_SESSION['tipo_usuario_act']==2)
				{
					$_SESSION['pago']=1;
					$_SESSION['efectivo']="";
					$_SESSION['recargo']="";
					$_SESSION['descuento']="";	
					header("Location:local_ventas.php");
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
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>


</head>

<body>
<?
	if($error==0)
	{
		echo "ERROR!!! la venta NO se cargo, desconectar y volver a cargar.";
	}	
	if($error==3)
	{
		echo "Error de base de datos: ".mysqli_error($mysqli);
	}		
?>
</body>
</html>
