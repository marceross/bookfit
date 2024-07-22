<?
	include("conex.php");
	//session_start();	
	include("local_controla.php");	
	$categorias=mysqli_query($mysqli,"SELECT * FROM categorias ORDER BY nombre");
	//if(!isset($_GET['id_cat']))
	//{
	$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod ORDER BY productos.nombre");
	//}
	/*else
	{
		$productos=mysqli_query("SELECT productos.cod as cp, productos.nombre as np, costo, margen FROM productos, categorias WHERE cod_cat=categorias.cod AND cod_cat=".$_GET['id_cat']." ORDER BY productos.nombre",$mysqli);	
	}*/
	//Vemos cuando reg hay en la tabla temporal
	$temporales=mysqli_query($mysqli,"SELECT * FROM ventas_temporal");
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
<form action="alta_modificar2.php" method="post" enctype="multipart/form-data" name="form1">
  <strong>Modificar</strong>
<p>Producto
    <select name="cod" id="cod">
<?
	while($producto=mysqli_fetch_array($productos))
	{
?>	
      	<option value="<? echo $producto['cp'];?>"><? echo $producto['np']." ".$producto['nm']." ".$producto['descripcion']." ($".$producto['costo']*$producto['margen'].")";?></option>
<?
	}
?>        
    </select>
    <label> </label>
  </p>
<label></label>
  <p> 
    <input type="submit" name="modificar" value="MODIFICAR" id="modificar">
  </p>
</form>
<p> <a href="local_datos.php">HOME (local_datos)</a> </p>
<p> <a href="local_kill_session.php">DESCONECTAR</a></p>
</body>
</html>
