<?
  include("conex.php");	
	include("local_controla.php");
  include("biblioteca.php");
	$dni_cli=$_GET['dni_cli'];

  $clientes=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni=".$dni_cli);
  if(mysqli_num_rows($clientes)==1)
  {
    $cliente=mysqli_fetch_array($clientes);
  ?>
    <input type="text" name="nombre_apellido" id="nombre_apellido" value="<? echo $cliente['nombre'].' '.$cliente['apellido'];?>" class="form-control" readonly>
  <?
  }  
  else
  {
    echo "no encontrado";
  }
?>