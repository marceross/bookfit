<?
	session_name('app_admin');
	session_start();
	include("conex.php");	
	include("local_controla.php");	
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	$id_usuario=$_SESSION['usuario_act'];
	$mensajes=mysqli_query($mysqli,"SELECT * FROM mensajes_internos, mensajes_internos_destinatarios WHERE confirmado<>'S' AND mensajes_internos.id_mensaje=mensajes_internos_destinatarios.id_mensaje AND mensajes_internos_destinatarios.id_usuario='$id_usuario'");
	$cant_mensajes=mysqli_num_rows($mensajes);
	$categorias=mysqli_query($mysqli,"SELECT * FROM categorias ORDER BY nombre");
	$formas=mysqli_query($mysqli,"SELECT * FROM ventas_forma_pago ORDER BY nombre");
	$consultas=mysqli_query($mysqli,"SELECT * FROM consultas WHERE respuesta=''");
	$cant_consultas=mysqli_num_rows($consultas);
	$contactos=mysqli_query($mysqli,"SELECT * FROM contacto WHERE respuesta=''");
	$cant_contactos=mysqli_num_rows($contactos);
	//if(!isset($_GET['id_cat']))
	//{
		$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND activo='S' ORDER BY productos.nombre, marcas.nombre");
	//}
	/*else
	{
		$productos=mysqli_query("SELECT productos.cod as cp, productos.nombre as np, costo, margen FROM productos, categorias WHERE cod_cat=categorias.cod AND cod_cat=".$_GET['id_cat']." ORDER BY productos.nombre",$mysqli);	
	}*/
	//Vemos cuando reg hay en la tabla temporal
	$temporales=mysqli_query($mysqli,"SELECT * FROM ventas_temporal WHERE id_usuario='$id_usuario'");
	$cant_registros=mysqli_num_rows($temporales);
	$_SESSION['cant_items']=$cant_registros+1;
?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="js/jquery-3.6.0.js"></script>
</head>

<body>
<form action="app_profeventames_procesa.php" method="post" enctype="multipart/form-data" name="form1">
  <table width="100%" border="0" align="center" bordercolor="#999999">
    <tr> 
      <td width="62%"><div align="left"></div></td>
      <td width="38%"><div align="center">mensajes <a href="ver_mensajes.php">ver</a> 
          <? echo "(".$cant_mensajes.")";?></div></td>
    </tr>
  </table>
  <?
	for($i=0;$i<$cant_registros+1;$i++)
	{
		mysqli_data_seek($categorias,0);
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
      <option value="<? echo $producto['cp'];?>"><? echo $producto['np']." ".$producto['nm']." ".$producto['descripcion']." ($".round ($producto['costo']*$producto['margen']).")"/*." ".$producto['stock']*/;?></option>
      <?
		}
		else
		{
?>
      <option selected value="<? echo $producto['cp'];?>"><? echo $producto['np']." ".$producto['nm']." ".$producto['descripcion']." ($".round ($producto['costo']*$producto['margen']).")"/*." ".$producto['stock']*/;?></option>	
      <?
		}            	
	}
?>        
    </select>
  Cantidad 
  <label>
  <input name="cant[<? echo $i;?>]" type="text" id="cant" size="9" value="<? echo $temporal['cant'];?>">
  </label>
  <?
	}
?>  
  <p>
    <label>
    <input type="submit" name="agregar" id="agregar" value="agregar casillas">
    </label>
  </p>
  <p>forma de pago 
    <select name="pago" id="pago">
<?
	while($forma=mysqli_fetch_array($formas))
	{
		if($forma['id_forma']<>$_SESSION['pago'])
		{
?>   
			<option value="<? echo $forma['id_forma'];?>" ><? echo $forma['nombre'];?></option>
<?
		}
		else
		{
?>		
			<option selected="selected" value="<? echo $forma['id_forma'];?>" ><? echo $forma['nombre'];?></option>
<?
		}            
	}
?>        
    </select>
  </p>
  <p>Efectivo 
    <input name="efectivo" type="text" id="efectivo" size="9" value="<? echo $_SESSION['efectivo'];?>">
  0000.00</p>
  <p>Recargo 
    <input name="recargo" type="text" id="recargo" size="9" value="<? echo $_SESSION['recargo'];?>">
  %</p>
  <p>Descuento 
    <input name="descuento" type="text" id="descuento" size="9" value="<? echo $_SESSION['descuento'];?>">
  %  </p>
  <p> 
    <input type="submit" name="terminar" value="Siguiente &gt;&gt;" id="terminar">
  </p>
</form>
<p>&nbsp;</p>
<p><strong><a href="asistencia.php">ASISTENCIA</a></strong></p>
<p>&nbsp;</p>
<p><a href="registrar.php">cargar cliente</a> 	<a href="registrados.php">ver cliente</a></p>
<p><a href="local_info.php">info local</a></p>
<form name="form2" method="post" action="local_procesa_cierrecaja.php">
  <strong>CIERRE CAJA</strong><br>
  Billetes
  <input name="billetes" type="text" id="billetes" size="5">
  Monedas
  <input name="monedas" type="text" id="monedas" size="5">
  <input name="enviar" type="submit" id="enviar" value="enviar">
</form>
<p> <font size="4"><a href="local_kill_session.php">DESCONECTAR</a> <font size="3">(para 
  cambiar de usuario)</font></font></p>
</body>
</html>
