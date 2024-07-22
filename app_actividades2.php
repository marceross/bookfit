<?php
session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
$actividades=mysqli_query($mysqli,"SELECT * FROM actividad WHERE activa='S' ORDER BY nombre");

if(isset($_SESSION['usuario_act']))
{ 
  //Buscamos el credito del usuario
	$datos_usuarios=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='".$_SESSION['usuario_act']."'");
	//echo "SELECT * FROM registrados WHERE dni='".$_SESSION['usuario_act']."'";
	//print_r($datos_usuarios);
	
	
  $dato_usuario=mysqli_fetch_array($datos_usuarios);
  
  
  
  $credito_actual=$dato_usuario['credito'];
}
else
{
  $credito_actual=0;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales Socio</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">

<script src="js/jquery-3.6.0.js"></script>
<script src="js/bootstrap.min.js"  crossorigin="anonymous"></script>
<link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">

</head>

<body>


<div class="container">
    <?php
    $cuenta_act=0;
    while($actividad=mysqli_fetch_array($actividades))
    {
        if($cuenta_act==0)
        {
    ?>

            <div class="row">
    <?php
        }
    ?>
      <div class="col-xs-12 col-md-4 caja_actividad">
            <div class="card text-center caja_individual">
                    <img src="<?php echo $actividad['imagen'];?>" class="img-fluid caja_imagen" alt="<?php echo $actividad['nombre'];?>">
                    <div class="card-body">
                      <h5 class="card-title"><?php echo $actividad['nombre'];?></h5>
                      <a href="app_cancela_horarios.php?act_seleccionada=<?php echo $actividad['id_actividad'];?>" class="btn btn-primary">MODIFICAR</a>
                    </div>
            </div>
      </div>

<?php
        $cuenta_act++;

        
        if($cuenta_act==3)
        {
    ?>    
            </div>
    <?php
        }
      

        if($cuenta_act==3)
        {
            $cuenta_act=0;
        }
    }
?>
</div>
<br><br>

</body>
</html>
