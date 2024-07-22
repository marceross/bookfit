<?php
session_name('app_admin');
session_start();
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba');
//Borramos la tabla temporal de ventas
mysqli_query($mysqli,"DELETE FROM ventas_temporal");
mysqli_query($mysqli,"DELETE FROM compra_temporal");
# recategorizar las variables provenientes de formulario form1.html
$usuario=$_POST['usuario'];
$clave=$_POST['clave'];
# hacer una consulta a la base de datos SELECT
$r=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE usuario='$usuario' AND clave='$clave'");
$datos_usuario=mysqli_fetch_array($r);
if(mysqli_num_rows($r)==0)
{
	echo "NOMBRE DE USUARIO O PASS INCORRECTO";
}
else
{	
	$_SESSION['autentificado']="SI";
	$_SESSION['usuario_act']=$datos_usuario['id_usuario'];	
	$_SESSION['tipo_usuario_act']=$datos_usuario['id_tipo_usuario'];
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	//Busca si hay una caja ABIERTA
	$cajas=mysqli_query($mysqli,"SELECT * FROM caja WHERE cerrada='N'");
	if(mysqli_num_rows($cajas)==0)
	{
		//Entra aca si NO hay ninguna caja abierta
		if($datos_usuario['id_tipo_usuario']==2)
		{
			header("Location:local_caja.php");
		}
		if($datos_usuario['id_tipo_usuario']==1)
		{
			header("Location:app_admin.php");
			//header("Location:local_datos.php");
		}
		if($datos_usuario['id_tipo_usuario']==3)
		{
			header("Location:app_profe.php");
		}
	}
	else
	{		
		if($datos_usuario['id_tipo_usuario']==2)
		{
			header("Location:local_ventas.php");
		}
		if($datos_usuario['id_tipo_usuario']==1)
		{
			header("Location:app_admin.php");
			//header("Location:local_datos.php");
		}
		if($datos_usuario['id_tipo_usuario']==3)
		{
			header("Location:app_profe.php");
		}
	}
}
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<p>&nbsp;</p>
</body>
</html>
