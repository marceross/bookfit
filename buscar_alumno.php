<?php

session_name('app_admin');
session_start();
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
    <td><?php echo $cliente['apellido'];?></td>
    <td><?php echo $cliente['nombre'];?></td>
    <td><button id="btn_agregar_alumno" onclick="agregar_alumno('<?php echo $dni_cli;?>', '<?php echo $idhorario;?>')">Agregar</button></td>
  <?php
  }  
  else
  {
    echo "no encontrado";
  }
?>