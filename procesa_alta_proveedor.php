<?
	include("conex.php");
	include("local_controla2.php");
	$nombre=$_POST['nombre'];
	$cuit=$_POST['cuit'];
	$mail=$_POST['mail'];
	$error=0;
	if($nombre=='' or $cuit=='' or $mail=='')
	{
		$error=1;
	}
	else
	{
		if(mysql_query("INSERT INTO proveedores (nombre,cuit,mail) VALUES ('$nombre','$cuit','$mail')",$link))
		{
			$error=0;
			header("Location:local_compra.php");
		}
		else
		{
			$error=3;
		}
		
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
		echo "Dejo un campo obligatorio en blanco";
	}
	if($error==3)
	{
		echo "Error de base de datos: ".mysql_error($link);
	}
?>
</body>
</html>
