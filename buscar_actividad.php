<?
include("conex.php");	
include("local_controla_app.php");
include("biblioteca.php");
$dni_cli=$_GET['dni_cli'];

  $clientes=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni=".$dni_cli);
  if(mysqli_num_rows($clientes)==1)
  {
    $cliente=mysqli_fetch_array($clientes);
  ?>
    <label>Nombre</label>
    <input type="nombre" name="nom" id="nom" value="<? echo $cliente['nombre'].' '.$cliente['apellido'];?>" class="form-control" required>
    <label>Mail</label>
    <input type="email" name="mai" id="mai" value="<? echo $cliente['mail'];?>" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
  <?
  }
  else
  {
  ?>
    <label>Nombre</label>
    <input type="nombre" name="nom" id="nom" value="" class="form-control" required> 
    <label>Mail</label>
    <input type="email" name="mai" id="mai" value="" class="form-control" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
  <?
  }
?>



<?/*
include("conex.php");	
include("local_controla_app.php");
include("biblioteca.php");
$id_act=$_GET['id_act'];

  $horarios=mysqli_query($mysqli, "SELECT * FROM actividad_horarios WHERE actividad_id_horario='$id_act'");
  if(mysqli_num_rows($horarios)>1)
  {
    $horario=mysqli_fetch_array($horarios);
  ?>
    <input type="desc" name="desc" id="desc" value="<? echo $horario['desc_especifica'];?>" class="form-control">
  <?
  }
  else
  {
  ?>
  <?
  }*/
?>