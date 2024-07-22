<?php
session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
include("biblioteca.php");
$array_fecha=getdate();
$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
//$dni=$_GET['dni'];
$id_usuario=$_SESSION['usuario_act'];
$dni=$_POST['dni_buscado'];
$reg=mysqli_query($mysqli,"SELECT * FROM registrados WHERE dni='$dni'");
$re=mysqli_fetch_array($reg);
$actividades=mysqli_query($mysqli,"SELECT * FROM actividad ORDER BY nombre");
$profesores=mysqli_query($mysqli,"SELECT * FROM profesor ORDER BY nombre");

// verifico si los puntos estan vencidos
if(strtotime($re['vencimiento'])<strtotime($fecha))
{
  $clase_vencido="badge-danger";
}
else
{
  $clase_vencido="badge-info";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">

<script>
//no funciona todavía
function buscar_alias(dni)
{	
  $.ajax({			
    url: 'buscar_alias.php',
    data: 'dni_cli=' + dni,
    success: function(resp) {
      $('#cliente_encontrado').html(resp)
    }
  });
}
</script>
</head>

<body>

<div class="container">

  <h6><span class="badge badge-pill badge-light"><?php echo $re['nombre'];?></span><span class="badge badge-pill badge-dark"><?php echo $re['credito'];?></span> puntos
  <span class="badge badge-pill <?php echo $clase_vencido; ?> "><?php echo formato_latino ($re['vencimiento']);?></span> vencimiento</h6>

  <form name="form1" method="post" action="app_vercliente_mod_procesa.php?dni=<?php echo $dni;?>">
  <div class="div-menu">
    <!--<div class="form-group col-md-4">
      <label for="nombre">Alias</label>
      <input name="ali" type="text" class="form-control" id="ali" value="<?// echo $re['alias'];?>" onkeyup="buscar_cliente(this.value)">
    </div>-->
    <div class="form-group col-md-6">
      <label for="nombre">Nombre</label>
      <input name="nom" type="text" class="form-control" id="nom" value="<?php echo $re['nombre'];?>" required>
    </div>
    <div class="form-group col-md-6">
      <label for="apellido">Apellido</label>
      <input name="ape" type="text" class="form-control" id="ape" value="<?php echo $re['apellido'];?>"/>
    </div>
    <div class="form-group col-md-6">
      <label for="dni">Dni</label>
      <input name="dni" type="text" class="form-control" id="dni" value="<?php echo $re['dni'];?>" readonly/>
    </div>
    <div class="form-group col-md-6">
      <label for="fecha de nacimento ">fecha de nacimento</label>
      <input name="nac" type="date" class="form-control" id="nac" value="<?php echo $re['nacimiento'];?>"/>
      MM-DD-AAAA
    </div>
    <div class="form-group col-md-6">
      <label for="celular">celular</label>
      <input name="cel" type="text" class="form-control" id="cel" value="<?php echo $re['celular'];?>"/>
    </div>
    <!--<div class="form-group col-md-6">
      <label for="comentario">comentario</label>
      <input name="com" type="text" class="form-control" id="com" value="<?php echo $re['comentario'];?>" size="80" maxlength="500"/>
    </div>-->
    <div class="form-group col-md-6">
      <label for="mail">mail</label>
    
      <!--<input name="mai" type="text" class="form-control" id="mai" value="<?// echo $re['mail'];?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>-->
      <input name="mai" type="email" class="form-control" id="mai" value="<?php echo $re['mail'];?>" required>
      <!--<a href="login_recuperar2.php" class="badge badge-info">Re-enviar mail</a>-->
    </div>
    <div class="form-group col-md-6">
      <label for="clave">clave</label>
        <input name="cla" type="password" class="form-control" id="cla" value="<?php echo $re['clave'];?>" readonly>
    </div>

          <!--<p>actividad
            <select name="act" id="act">
        
      <?php
    while($actividad=mysqli_fetch_array($actividades))
    {
      if($actividad['id_actividad']<>$re['actividad'])
      {
    ?>
          <option value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
      <?php
          }
          else
          {
    ?>
          <option selected value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
            
          
      <?php
      }
    }
    ?>
      </select>
          </p>
          <p>profesor 
            <select name="pro" id="pro">
        
      <?php
    while($profesor=mysqli_fetch_array($profesores))
    {
      if($profesor['id_profesor']<>$re['profesor'])
      {
      
    ?>
          <option value="<?php echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
      <?php
      }
      else
      {
    ?>
          <option selected value="<?php echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
      <?php
      }
    }
    ?>
      </select>
          </p>
          <p>
            autorizacion 
            <?php
        if($re['autorizacion']=='N')
        {
        ?>
              <input name="aut" type="checkbox" id="aut" value="S">
          <?php
        }
        else
        {
      ?>
            <input checked name="aut" type="checkbox" id="aut" value="S">
          <?php
        }
      ?>
          </p>
          <p>
            certificado
              <?php
        if($re['certificado']=='N')
        {
        ?>
              <input name="cer" type="checkbox" id="cer" value="S">
          <?php
        }
        else
        {
      ?>
            <input checked name="cer" type="checkbox" id="cer" value="S">
          <?php
        }
      ?>
          </p>-->
          <p> 
            <input class="btn btn-primary" type="submit" name="Submit" value="Enviar">
          </p>
        </form>
    </div>
  </div>
</div>

<div class="div-menu">
	<div class="form-group col-md-6">	
		<p><a href="app_profe.php?v=1#boton_siguiente" class="badge badge-info">Volver</a></p>
	</div>
</div>
</body>
</html>

<?php
// ERA registrados_mod.php
?>