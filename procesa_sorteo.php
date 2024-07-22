<?
include("conex.php");
$mai=$_POST['mai'];
$r=mysql_query("SELECT * FROM registrados WHERE mail='$mai'",$link);

if($mai=='')
{
	echo "ERROR - VOLVE ATRAS Y COMPLETA EL ESPACIO CON TU MAIL";
}
elseif(mysql_num_rows($r)==0)
{
	header("location:registrate.htm");
}
else
{
	header("location:sorteo.php?mai=".$mai);
}
?>

<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>

</body>
</html>
