<?php
session_name("app_admin");
session_start();
  include("conex.php");	
  include("biblioteca.php");
	$dni_cli=$_GET['dni_cli'];

  $clientes=mysqli_query($mysqli, "SELECT alias FROM registrados WHERE dni=".$dni_cli);
  if(mysqli_num_rows($clientes)==1)
  {
    $cliente=mysqli_fetch_array($clientes);
  ?>
    <input type="text" class="form-control" id="ali" name="ali" value="<?php echo $cliente['alias'].', '.'Ya existe!'?>" placeholder="Tu alias">
  <?php
  }  
  else
  {
    ?>
    <input type="text" class="form-control" id="ali" name="ali" placeholder="Nombre">
    <?php
  }
?>