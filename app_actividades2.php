<?
include("conex.php");
include("local_controla.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
$actividades=mysqli_query($mysqli,"SELECT * FROM actividad WHERE activa='S' ORDER BY nombre");

if(isset($_SESSION['usuario_act']))
{ 
  //Buscamos el credito del usuario
	$datos_usuarios=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='".$_SESSION['usuario_act']."'");
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

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
                    <img src="<? echo $actividad['imagen'];?>" class="img-fluid caja_imagen" alt="<? echo $actividad['nombre'];?>">
                    <div class="card-body">
                      <h5 class="card-title"><? echo $actividad['nombre'];?></h5>
                      <a href="app_cancela_horarios.php?act_seleccionada=<? echo $actividad['id_actividad'];?>" class="btn btn-primary">MODIFICAR</a>
                    </div>
            </div>
      </div>

<?
        $cuenta_act++;

        
        if($cuenta_act==3)
        {
    ?>    
            </div>
    <?
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
