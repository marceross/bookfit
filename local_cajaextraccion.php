<?
	include("conex.php");
	//session_start();
	include("local_controla.php");
	$concepto=mysqli_query($mysqli,"SELECT * FROM caja_extraccion_concepto ORDER BY nombre");
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<form name="form1" method="post" action="local_procesa_cajaextraccion.php">
  <p><strong>EXTRACCION CAJA</strong><br>
    <br>
    <br>
    BILLETES 
    <input type="text" name="billetes" id="billetes">
  </p>
  <p>MONEDAS 
    <input type="text" name="monedas" id="monedas">
  </p>
  <p>Concepto 
    <select name="concepto" id="concepto">
<?
	while($concept=mysqli_fetch_array($concepto))
	{
		if($_SESSION['id_concepto']<>$proveedor['id_concepto'])
		{
?>
      		<option value="<? echo $concept['id_concepto'];?>"><? echo $concept['nombre'];?></option>
<?
		}
		else
		{
?>
			<option selected value="<? echo $concept['id_concepto'];?>"><? echo $concept['nombre'];?></option>		
<?
		}            
	}
?>
    </select>
    <br>
    <br>
    Comentarios 
    <input name="comentarios" type="text" id="comentarios" size="40">
  </p>
  </p>
  <p>
    <input name="enviar" type="submit" id="enviar" value="enviar">
  </p>
  </form>
</body>
</html>
