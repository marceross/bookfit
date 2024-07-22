<?
	include("conex.php");
	//session_start();
	include("local_controla.php");	
	$billetes=$_POST['billetes'];
	$monedas=$_POST['monedas'];
	$concepto=$_POST['concepto'];
	$comentarios=$_POST['comentarios'];
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	//$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	$hora4=date("H:i:s",(time()+4*3600));
	$id_usuario=$_SESSION['usuario_act'];
	$error=0;
	if($billetes=='' or $monedas=='' or $comentarios=='')
	{
		$error=1;
	}
	else
	{
		if(mysqli_query($mysqli,"INSERT INTO caja_extraccion (billetes, monedas, concepto, fecha, hora, id_usuario, comentarios) VALUES ('$billetes','$monedas','$concepto','$fecha','$hora4','$id_usuario', '$comentarios')"))
		{
			$error=0;
			header("Location:local_ventas.php");
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
		echo "Error de base de datos: ".mysqli_error($mysqli);
	}
?>
</body>
</html>
