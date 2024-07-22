<?
	session_name('app_admin');
	include("conex.php");	
	include("local_controla2.php");
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	//Varialbe que lleva el total
	if(!isset($_SESSION['total_compra']))
	{
		$_SESSION['total_compra']=0;
	}
	if(!isset($_SESSION['dia']))
	{
		$_SESSION['dia']=$array_fecha['mday'];
		$_SESSION['mes']=$array_fecha['mon'];
		$_SESSION['anio']=$array_fecha['year'];	
		$_SESSION['id_proveedor']=0;		
	}
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	$id_usuario=$_SESSION['usuario_act'];
	$proveedores=mysqli_query($mysqli,"SELECT * FROM proveedores ORDER BY nombre");	
	//$categorias=mysql_query($mysqli,"SELECT * FROM categorias ORDER BY nombre");	
	//if(!isset($_GET['id_cat']))
	//{
	$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod ORDER BY productos.nombre, marcas.nombre");
	/*}
	else
	{
		$productos=mysql_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, margen FROM productos, categorias WHERE cod_cat=categorias.cod AND cod_cat=".$_GET['id_cat']." ORDER BY productos.nombre");	
	}*/
	//Vemos cuantos reg hay en la tabla temporal
	$temporales=mysqli_query($mysqli,"SELECT * FROM compra_temporal");
	$cant_registros=mysqli_num_rows($temporales);
	$_SESSION['cant_items']=$cant_registros+1;
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="local_procesa_compra.php" method="post" enctype="multipart/form-data" name="form1">
  <p><strong>COMPRA</strong></p>
  <p>
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
					for($i=2021;$i<=2025;$i++)
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
    fecha factura</p>
  <p>Proveedor 
    <select name="proveedor" id="proveedor">
<?
	while($proveedor=mysqli_fetch_array($proveedores))
	{
		if($_SESSION['id_proveedor']<>$proveedor['id_proveedor'])
		{
?>
      		<option value="<? echo $proveedor['id_proveedor'];?>"><? echo $proveedor['nombre'];?></option>
<?
		}
		else
		{
?>
			<option selected value="<? echo $proveedor['id_proveedor'];?>"><? echo $proveedor['nombre'];?></option>		
<?
		}            
	}
?>
    </select>
    <a href="alta_proveedor.php">nuevo proveedor</a></p>
  <p>
<?
	for($i=0;$i<$cant_registros+1;$i++)
	{
		//mysqli_data_seek($categorias,0);
		mysqli_data_seek($productos,0);
		$temporal=mysqli_fetch_array($temporales);
		
?>
<br>
Producto
  <select name="producto[<? echo $i;?>]" id="producto">
        <?
	while($producto=mysqli_fetch_array($productos))
	{
		if($producto['cp']<>$temporal['cod_producto'])
		{
?>
        <option value="<? echo $producto['cp'];?>"><? echo $producto['np']." ".$producto['nm']." ".$producto['descripcion']." ($".$producto['costo'].")";?></option>
        <?
		}
		else
		{
?>
        <option selected value="<? echo $producto['cp'];?>"><? echo $producto['np']." ".$producto['nm']." ".$producto['descripcion']." ($".$producto['costo'].")";?></option>
        <?
		}            	
	}
?>
      </select>
    Cantidad
  <label>
  <input name="cant[<? echo $i;?>]" type="text" id="cant" size="9" value="<? echo $temporal['cantidad'];?>">
  </label>
 Costo 
 <input name="costo[<? echo $i;?>]" type="text" id="costo" size="9" value="<? echo $temporal['costo'];?>">
 <?
	}
?>
  </p>
  <p>
    <label></label>
  TOTAL: <? echo $_SESSION['total_compra'];?></p>
  <p> 
    <input type="submit" name="agregar" value="agregar" id="agregar">
    mas casillas</p>
  <p>&nbsp;</p>
  <p> 
    <input type="submit" name="terminar" value="cargar" id="terminar">
  </p>
</form>
<p>
<a href="local_datos.php">HOME (local_datos)</a>
</p>
<p>
<a href="local_kill_session.php">DESCONECTAR</a>
</p>
</body>
</html>
