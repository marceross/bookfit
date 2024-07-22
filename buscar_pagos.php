<?
	include("conex.php");
	include("local_controla.php");
	include("biblioteca.php");
	$dni_cli=$_GET['dni_cli'];
	$pagos=mysqli_query($mysqli,"SELECT * FROM ventas WHERE id_registrados='$dni_cli' ORDER BY fecha DESC LIMIT 3");
	$asistencia=mysqli_query($mysqli,"SELECT * FROM actividad_asistencia WHERE dni='$dni_cli' ORDER BY fecha DESC LIMIT 8" );
		//echo $pago['fecha'];
?>
	<table class="table table-striped table-hover">
  <tr>
    <th scope="col">Fecha de pago</th>
    <th scope="col">Monto</th>
  </tr>
  <?
  while($pago=mysqli_fetch_array($pagos))
  {
	  $detalles=mysqli_query($mysqli,"SELECT SUM(cantidad*precio) AS total FROM ventas_detalle WHERE id_venta=".$pago['id_venta']);
	  $detalle=mysqli_fetch_array($detalles);
  ?>
  <tr>
    <td><? echo formato_latino ($pago['fecha']);?></td>
    <td>$ <? echo $detalle['total'];?></td>
  </tr>
  <?
  }
?>
</table>
<br>
<table class="table table-striped table-hover">
  <tr>
    <th scope="col">Asistencia</th>
  </tr>
  <?
  while($asistenci=mysqli_fetch_array($asistencia))
  {
  ?>
  <tr>
    <td><? echo formato_latino ($asistenci['fecha']);?></td>
  </tr>
  <?
  }
?>
</table>

