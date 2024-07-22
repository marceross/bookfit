<?
	include("conex.php");
	//session_start();
	include("local_controla.php");
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<form name="form1" method="post" action="local_procesa_cajaaporte.php">
  <p><strong>CAJA</strong> <strong>APORTE</strong><br>
    <br>
    <br>
    BILLETES 
    <input type="text" name="billetes" id="billetes">
  </p>
  <p>MONEDAS 
    <input type="text" name="monedas" id="monedas">
    <br>
    <br>
    concepto 
    <input name="concepto" type="text" id="concepto" size="40">
    <br>
  </p>
  <p>
    <input name="enviar" type="submit" id="enviar" value="enviar">
  </p>
  </form>
</body>
</html>
