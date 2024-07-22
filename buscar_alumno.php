<?
  include("conex.php");	
	include("local_controla.php");
  include("biblioteca.php");
	$dni_cli=$_GET['dni_cli'];
    $idhorario=$_GET['idhorario'];

  $clientes=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni=".$dni_cli);
  if(mysqli_num_rows($clientes)==1)
  {
    $cliente=mysqli_fetch_array($clientes);
  ?>    
    <td><? echo $cliente['apellido'];?></td>
    <td><? echo $cliente['nombre'];?></td>
    <td><button id="btn_agregar_alumno" onclick="agregar_alumno('<? echo $dni_cli;?>', '<? echo $idhorario;?>')">Agregar</button></td>
  <?
  }  
  else
  {
    echo "no encontrado";
  }
?>