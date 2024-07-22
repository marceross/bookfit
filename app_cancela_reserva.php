<?
    include("local_controla_app.php");// es el controla de app_reservas
	include("conex.php");
	date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
	$id_reserva=$_GET['idr'];

	$datos_reserva=mysqli_query($mysqli, "SELECT * FROM actividad_reservas, actividad_horarios WHERE actividad_horarios_id_horario=id_horario AND id_reserva='$id_reserva'");
	$dato_reserva=mysqli_fetch_array($datos_reserva);
	$costo=$dato_reserva['costo'];

	//echo "costo: ".$costo;
	//exit();

	mysqli_query($mysqli,"DELETE FROM actividad_reservas WHERE id_reserva ='$id_reserva'");
	//mysqli_query($mysqli, "UPDATE registrados SET credito=credito+".$costo." WHERE dni='".$_SESSION['usuario_act'])."'"; original
	mysqli_query($mysqli, "UPDATE registrados SET credito=credito+".$costo." WHERE dni=".$_SESSION['usuario_act']);
	//mysqli_query($mysqli, "UPDATE registrados SET credito=credito-".$_SESSION['total_costo']." WHERE dni=".$_SESSION['usuario_act']); de app_reserva_procesa 	DUDA... FALTA COMILLA DOBLE AL FINAL???
	header("Location:app_agenda.php");
	exit();
	
?>