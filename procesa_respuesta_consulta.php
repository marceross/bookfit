<?
	include("conex.php");
	include("local_controla.php");
	$nro_consulta=$_GET['cons'];
	//Leemos el mail del que consulto
	$consultas=mysql_query("SELECT * FROM consultas WHERE numero='$nro_consulta'",$link);
	$consulta=mysql_fetch_array($consultas);
	$respuesta=$_POST['respuesta'];
	$error=0;
	if(!mysql_query("UPDATE consultas SET respuesta='$respuesta' WHERE numero='$nro_consulta'",$link))
	{
		$error=1;
	}
	else
	{
		mail($consulta['mail'],"RESPUESTA CONSULTA",$respuesta,"From:gravital@boardhouse.com.ar");
		header("Location:ver_consultas.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?
if($error==1)
{
	echo "La respuesta no se pudo enviar";
}
?>
</body>
</html>
