<?
	include("conex.php");
	include("local_controla.php");
	$id_mensaje=$_GET['id'];
	mysql_query("UPDATE mensajes_internos SET confirmado='S' WHERE id_mensaje='$id_mensaje'",$link);
	header("Location:ver_mensajes.php");
?>