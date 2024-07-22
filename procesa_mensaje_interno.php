<?
	include ("conex.php");
	include("local_controla.php");
	include("biblioteca.php");
	$id_usuario=$_SESSION['usuario_act'];
	$texto_mensaje=$_POST['texto_mensaje'];
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	//Cargamos el mensaje
	if(!mysql_query("INSERT INTO mensajes_internos (mensaje, fecha, confirmado, id_usuario_rem) VALUES ('$texto_mensaje','$fecha','N','$id_usuario')",$link))
	{
		echo mysql_error($link);
		exit();
	}
	$id_mensaje=mysql_insert_id($link);
	//Caramos los destinatarios
	//Busco el maximo ID de usuario
	$usuarios=mysql_query("SELECT MAX(id_usuario) FROM usuarios",$link);
	$usuario=mysql_fetch_array($usuarios);
	$maximo=$usuario[0];
	for($i=1;$i<=$maximo;$i++)
	{
		if(isset($_POST['dest'][$i]))
		{
			$id_destinatario=$_POST['dest'][$i];
			if(!mysql_query("INSERT INTO mensajes_internos_destinatarios (id_usuario,id_mensaje) VALUES ('$id_destinatario','$id_mensaje')",$link))
			{
				echo mysql_error($link)."<br>";				
				exit();
			}
		}
	}
	header("Location:ver_mensajes.php");
?>