<?
	include("conex.php");
	//session_start();
	include("local_controla.php");
	//Buscamos el saldo anterior
	$ultima_caja=mysqli_query($mysqli,"SELECT MAX(id_cierre) FROM caja_cierre");
	$max_caja=mysqli_fetch_array($ultima_caja);
	$saldos=mysqli_query($mysqli,"SELECT * FROM caja_cierre WHERE id_cierre=".$max_caja[0]);
	$saldo=mysqli_fetch_array($saldos);
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<form name="form1" method="post" action="local_procesa_caja.php">
  <p><strong>CAJA HOY</strong><br>
    Saldo anterior: Billetes: <? echo number_format($saldo['billetes']);?> Monedas: <? echo number_format($saldo['monedas']);?><br>
    Saldo inicial<br>
    BILLETES 
    <input type="text" name="billetes" id="billetes">
  </p>
  <p>MONEDAS 
    <input type="text" name="monedas" id="monedas">
    <br>
  </p>
  <p>
    <input name="enviar" type="submit" id="enviar" value="enviar">
  </p>
  </form>
</body>
</html>
