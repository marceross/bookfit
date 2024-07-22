<?
include("conex.php");

$emp=$_POST['emp'];
$act=$_POST['act'];
$tel=$_POST['tel'];
$mai=$_POST['mai'];
$nom=$_POST['nom'];
$com=$_POST['com'];

$array_fecha=getdate();
$fecha=strval($array_fecha['year'])."-".strval($array_fecha['mon'])."-".strval($array_fecha['mday']);
?>

<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"></head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="75%" height="266" border="0" align="center">
  <tr>
    <td><div align="center">
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p><img src="file:///D|/LOKALES/logo_lokales.gif" width="319" height="231" border="0" usemap="#Map2"></p>
        <p>&nbsp;</p>
        <p>&nbsp; </p>
      </div></td>
  </tr>
</table>
<table width="75%" border="0" align="center">
  <tr>
    <td><div align="center">
	
	<?
	if($emp=='' or $act=='' or $tel=='' or $mai=='' or $nom=='' or $com=='')
{
    echo"ERROR: CAMPO VACIO, VOLVER ATRAS";
}
	
elseif(mysql_query("INSERT INTO contacto(empresa,actividad,telefono,mail,nombre,comentario,fecha)VALUES ('$emp','$act','$tel','$mai','$nom','$com','$fecha')",$link))
	{
	echo "SE HA ENVIADO CON EXITO";
	}
	else
	{
	mail("mibanez23@hotmail.com","LOKALES CONTACTO",$nom,$com,"From:".$mai);
	}
?>

	</div></td>
  </tr>
  <tr>
    <td><div align="center"><img src="file:///D|/LOKALES/volver.gif" width="157" height="32" border="0" usemap="#Map"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<div align="center">
<p>&nbsp; </p>
  <p align="center"><em><font size="2">&copy; 2008 LoKales&reg; de HIROSS S. de 
    H.</font></em></p>
</div>
<map name="Map">
  <area shape="rect" coords="-1,0,156,32" href="file:///D|/LOKALES/park.html">
</map>
<map name="Map2">
  <area shape="circle" coords="157,116,114" href="file:///D|/LOKALES/park.html">
</map>
</body>
</html>
