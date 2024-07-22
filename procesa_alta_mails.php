<?
include("conex.php");
$m=$_POST['m'];
$array_fecha=getdate();
$fecha=strval($array_fecha['year'])."-".strval($array_fecha['mon'])."-".strval($array_fecha['mday']);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<link REL="SHORTCUT ICON" HREF="http://www.lokales.com.ar/lokico.ico">
<head>
<link href="http://www.lokales.com.ar/lokico.ico" rel="shortcut icon"/>
<title>LOKALES nuestro ESPOT!</title>
<meta name="keywords" content="deportes, surf, skate, mountainboard, mountainboarding, escalada, trial, roller, kitesurf, tablas, boards"><meta name="description" content="COMPLEJO DEPORTIVO EXTREMO --- ACTION SPORTS COMPLEX"><META NAME="ROBOTS" CONTENT="ALL">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="75%" height="266" border="0" align="center">
  <tr>
    <td><div align="center">
<p><img src="logo_lokales.gif" width="319" height="231" border="0" usemap="#Map2"></p>
      </div></td>
  </tr>
</table>
<table width="75%" border="0" align="center">
  <tr>
    <td><div align="center">
	
	<?
	if($m=='')
{
    echo"ERROR: FALTA COMPLETAR EL ESPACIO";
}
	
elseif(mysql_query("INSERT INTO mails(mail,fecha)VALUES ('$m','$fecha')",$link))
{
	mail("mibanez23@hotmail.com","SUSCRIPCION LOKALES",$m,"From:".$m);
	echo "SE HA REGISTRADO CON EXITO!";
}
else
{
	echo "EL MAIL YA ESTA REGISTRADO, GRACIAS";
}

?>

	</div></td>
  </tr>
  <tr>
    <td><div align="center"><img src="volver.gif" width="157" height="32" border="0" usemap="#Map"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<div align="center"></div>
<map name="Map">
  <area shape="rect" coords="0,0,157,32" href="index.htm">
</map>
<map name="Map2">
  <area shape="circle" coords="157,118,116" href="index.html">
</map>
</body>
</html>

