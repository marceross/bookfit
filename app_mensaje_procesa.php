<?
	include ("conex.php");
	include("local_controla_app.php");
	include("biblioteca.php");
	$id_usuario=$_SESSION['usuario_act'];
	$texto_mensaje=$_POST['texto_mensaje'];
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']).":".strval($array_fecha['seconds']);
	$marca_tiempo=$fecha." ".$hora;
	
	if(!isset($_GET['id_conversacion']))// si no existe id_conversacion
	{
		//si la conversacion es nueva
		$id_dest=$_POST['pro'];
		mysqli_query($mysqli, "INSERT INTO mensajes_conversaciones(fecha_ultimo_msj) VALUES ('$marca_tiempo')");
		$id_conversacion=mysqli_insert_id($mysqli);
	}
	else
	{		
		//si la conversacion ya existe
		$id_conversacion=$_GET['id_conversacion'];
		//Buscamos el destinatario
		$mensajes_conversaciones=mysqli_query($mysqli, "SELECT * FROM mensajes WHERE id_conversacion='$id_conversacion' AND id_dest<>'$id_usuario'");
		$mensaje_conversacion=mysqli_fetch_array($mensajes_conversaciones);
		$id_dest=$mensaje_conversacion['id_dest'];
	}
	
	//Cargamos el mensaje
	if(!mysqli_query($mysqli, "INSERT INTO mensajes (mensaje, fecha, hora, id_usuario_rem, id_dest, id_conversacion) VALUES ('$texto_mensaje','$fecha', '$hora', '$id_usuario', '$id_dest', '$id_conversacion')"))
	{
		echo mysqli_error($mysqli);
		exit();
	}			
	if(!mysqli_query($mysqli, "UPDATE mensajes_conversaciones SET fecha_ultimo_msj='$marca_tiempo' WHERE id_conversacion='$id_conversacion'"))
	{
		echo mysqli_error($mysqli);
		exit();
	}
	header("Location:app_mensajes.php");
?>