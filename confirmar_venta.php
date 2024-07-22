<?
	include("conex.php");
	include("local_controla.php");
	$id_venta=$_GET['id_venta'];
	mysqli_query($mysqli,"UPDATE ventas SET confirmada='S' WHERE id_venta='$id_venta'");
	header("Location:local_info.php");
?>