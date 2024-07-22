<?
include("conex.php");
include("local_controla.php");
include("biblioteca.php");
$array_fecha=getdate();
$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
//$dni=$_GET['dni'];
$id_usuario=$_SESSION['usuario_act'];
//$dni=$_POST['dni_buscado'];
//$reg=mysqli_query($mysqli,"SELECT * FROM registrados WHERE dni='$dni'");
//$re=mysqli_fetch_array($reg);
//$actividades=mysqli_query($mysqli,"SELECT * FROM actividad ORDER BY nombre");
//$profesores=mysqli_query($mysqli,"SELECT * FROM profesor ORDER BY nombre");

/*if(isset($_GET["id"])){
	//$connect = mysqli_connect("localhost", "root", "", "ajax_search");
	
		$query = "
  SELECT * FROM registrados 
  WHERE dni='".$_GET["id"]."'";
  $result = mysqli_query($mysqli, $query);
  $re=mysqli_fetch_array($result)*/

/* verifico si los puntos estan vencidos
if(strtotime($re['vencimiento'])<strtotime($fecha))
{
  $clase_vencido="badge-danger";
}
else
{
  $clase_vencido="badge-info";
}*/
?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

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

<?
if(isset($_GET["id"])){
  //$connect = mysqli_connect("localhost", "root", "", "ajax_search");
  
      $query = "
SELECT * FROM registrados 
WHERE dni='".$_GET["id"]."'";
$result = mysqli_query($mysqli, $query);
if(mysqli_num_rows($result) > 0){
while($re = mysqli_fetch_array($result))
{?>

  <form name="form1" method="post" action="app_vercliente_mod_procesa.php?dni=<? echo $re['dni'];?>">
  <div class="div-menu">
    
    <div class="form-group col-md-6">
      <label for="credito">Puntos</label>
      <span class="badge badge-pill badge-dark"><? echo $re['credito'];?></span>
    </div>
      <?
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
    <div class="form-group col-md-6">
      <label for="vencimiento">Vencimiento</label>
      <span class="badge badge-pill <? echo $clase_vencido; ?> "><? echo formato_latino ($re['vencimiento']);?></span>
    </div>
  
  
    <!--<div class="form-group col-md-4">
      <label for="nombre">Alias</label>
      <input name="ali" type="text" class="form-control" id="ali" value="<?// echo $re['alias'];?>" onkeyup="buscar_cliente(this.value)">
    </div>-->
    <div class="form-group col-md-6">
      <label for="nombre">Nombre</label>
      <input name="nom" type="text" class="form-control" id="nom" value="<? echo $re['nombre'];?>" required>
    </div>
    <div class="form-group col-md-6">
      <label for="apellido">Apellido</label>
      <input name="ape" type="text" class="form-control" id="ape" value="<? echo $re['apellido'];?>"/>
    </div>
    <div class="form-group col-md-6">
      <label for="dni">Dni</label>
      <input name="dni" type="text" class="form-control" id="dni" value="<? echo $re['dni'];?>" readonly/>
    </div>
    <div class="form-group col-md-6">
      <label for="fecha de nacimento ">fecha de nacimento</label>
      <input name="nac" type="date" class="form-control" id="nac" value="<? echo $re['nacimiento'];?>"/>
      MM-DD-AAAA
    </div>
    <div class="form-group col-md-6">
      <label for="celular">celular</label>
      <input name="cel" type="text" class="form-control" id="cel" value="<? echo $re['celular'];?>"/>
    </div>
    <!--<div class="form-group col-md-6">
      <label for="comentario">comentario</label>
      <input name="com" type="text" class="form-control" id="com" value="<? echo $re['comentario'];?>" size="80" maxlength="500"/>
    </div>-->
    <div class="form-group col-md-6">
      <label for="mail">mail</label>
    
      <!--<input name="mai" type="text" class="form-control" id="mai" value="<?// echo $re['mail'];?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>-->
      <input name="mai" type="email" class="form-control" id="mai" value="<? echo $re['mail'];?>" required>
      <!--<a href="login_recuperar2.php" class="badge badge-info">Re-enviar mail</a>-->
    </div>
    <div class="form-group col-md-6">
      <label for="clave">clave</label>
        <input name="cla" type="password" class="form-control" id="cla" value="<? echo $re['clave'];?>" readonly>
    </div>

          <!--<p>actividad
            <select name="act" id="act">
        
      <?
    /*{
      if($actividad['id_actividad']<>$re['actividad'])
      {
    ?>
          <option value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
      <?
          }
          else
          {
    ?>
          <option selected value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
            
          
      <?
      }
    }*/
    ?>
      </select>
          </p>
          <p>profesor 
            <select name="pro" id="pro">
        
      <?
    /*while($profesor=mysqli_fetch_array($profesores))
    {
      if($profesor['id_profesor']<>$re['profesor'])
      {
      
    ?>
          <option value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
      <?
      }
      else
      {
    ?>
          <option selected value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
      <?
      }
    }
    ?>
      </select>
          </p>
          <p>
            autorizacion 
            <?
        if($re['autorizacion']=='N')
        {
        ?>
              <input name="aut" type="checkbox" id="aut" value="S">
          <?
        }
        else
        {
      ?>
            <input checked name="aut" type="checkbox" id="aut" value="S">
          <?
        }
      ?>
          </p>
          <p>
            certificado
              <?
        if($re['certificado']=='N')
        {
        ?>
              <input name="cer" type="checkbox" id="cer" value="S">
          <?
        }
        else
        {
      ?>
            <input checked name="cer" type="checkbox" id="cer" value="S">
          <?
        }
      */?>
          </p>-->
          <p> 
            <input class="btn btn-primary" type="submit" name="Submit" value="Enviar">
          </p>
          <?php }
			}
		}?>
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

<?
// ERA registrados_mod.php
?>