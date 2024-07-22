<?
  include("conex.php");	
  include("biblioteca.php");
	$dni_cli=$_GET['dni_cli'];

  $clientes=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni=".$dni_cli);
  if(mysqli_num_rows($clientes)==1)
  {
    $cliente=mysqli_fetch_array($clientes);
  ?>
    <input type="text" class="form-control nom" id="nom" name="nom" value="<? echo 'Hola'. ' '.$cliente['nombre'].', '.'YA ESTÁS!'?>" placeholder="Nombre" required>
  <?
  }  
  else
  {
    ?>
    <input type="text" class="form-control" id="nom" name="nom" placeholder="Nombre" required>
    <?
  }
?>