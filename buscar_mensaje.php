<?
include("conex.php");	
include("biblioteca.php");
$men=$_GET['men'];

$mensajes=mysqli_query($mysqli, "SELECT usuario FROM mensajes WHERE mensaje=".$men);
$mensajes=mysqli_query($mysqli, "SELECT mensaje FROM mensajes WHERE mensaje LIKE '%$men%');
  if(mysqli_num_rows($clientes)==1)
  {
    $mensaje=mysqli_fetch_array($mensajes);
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