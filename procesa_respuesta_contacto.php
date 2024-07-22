<?
	include("conex.php");
	include("local_controla.php");
	$nro_contacto=$_GET['cont'];
	//Leemos el mail del que consulto
	$contacto=mysql_query("SELECT * FROM contacto WHERE codigo='$nro_contacto'",$link);
	$contact=mysql_fetch_array($contacto);
	$respuesta=$_POST['respuesta'];
	$error=0;
	if(!mysql_query("UPDATE contacto SET respuesta='$respuesta' WHERE codigo='$nro_contacto'",$link))
	{
		$error=1;
	}
	else
	{
		mail($contact['mail'],"RESPUESTA CONTACTO",$respuesta,"From:gravital@boardhouse.com.ar");
		header("Location:ver_contacto.php");
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
