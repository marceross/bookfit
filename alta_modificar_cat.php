<?
	include("conex.php");
	//session_start();	
	include("local_controla.php");	
	$categorias=mysql_query("SELECT * FROM categorias ORDER BY nombre",$link);
	//if(!isset($_GET['id_cat']))
	//{
	//$productos=mysql_query("SELECT productos.cod as cp, productos.nombre as np, costo, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod ORDER BY productos.nombre",$link);
	//}
	/*else
	{
		$productos=mysql_query("SELECT productos.cod as cp, productos.nombre as np, costo, margen FROM productos, categorias WHERE cod_cat=categorias.cod AND cod_cat=".$_GET['id_cat']." ORDER BY productos.nombre",$link);	
	}*/
	//Vemos cuando reg hay en la tabla temporal
	$temporales=mysql_query("SELECT * FROM ventas_temporal",$link);
	$cant_registros=mysql_num_rows($temporales);
	$_SESSION['cant_items']=$cant_registros+1;
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="alta_modificar_cat2.php" method="post" enctype="multipart/form-data" name="form1">
  <strong>Modificar</strong>
<p>Categoria 
    <select name="cod" id="cod">
        <?
	while($categoria=mysql_fetch_array($categorias))
	{
?>
        <option value="<? echo $categoria['cod'];?>" selected="selected"> <? echo $categoria['nombre'];?> 
        </option>
        <?
	}
?>
        <option value="0"> </option>
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
