<?
session_name('app_reservas');
session_start();
include("conex.php");
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

<script>/*
document.addEventListener("DOMContentLoaded", function(){
                // llamamos cada 500 segundos ;)
                const milisegundos = 5 *1000;
                setInterval(function(){
                    // No esperamos la respuesta de la petición porque no nos importa
                    fetch("./refrescar.php");
                },milisegundos);
            });*/
</script>
</head>

<body>
<header>
      <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
      <a class="navbar-brand" href="app_actividades.php"><img src="logo.gif" class="img-fluid" alt="Lokales Training spot logo image" width="200"></a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="info_puntos.php"><span class="badge badge-pill badge-info">info puntos</span></a>
          </li>
        <?
        if(!isset($_SESSION['usuario_act']))
        {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="login.html">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login_inscripcion.html">Registrarte</a>
          </li>
        <?
        }
        else
        {
        ?>
          <li class="nav-item">
          <h6><a class="nav-link"><span class="badge badge-pill badge-light"><? echo $dato_usuario['nombre'];?></span><span class="badge badge-pill badge-dark"><? echo $credito_actual;?></span> puntos</a></h6>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
          </li>
        <?
        }
        ?>
        </ul>
        <!--
        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Buscar">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
        -->
      </div>
    </nav>
</header>

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
                      <p class="card-text">Hacé click <!--<span class="badge badge-pill badge-light">reservar</span>-->para ver horarios disponibles y el valor</p>
                      <!--<p class="card-text"><?// echo $actividad['descripcion'];?></p>-->
                      <!--<a href="app_act_info.html" class="btn btn-info">mas info</a>-->
                      <a href="app_reserva.php?act_seleccionada=<? echo $actividad['id_actividad'];?>" class="btn btn-primary">Horarios</a>
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

<footer class="fixed-bottom bg-light">
      <div class="row">
        <div class="col">
        <a href="app_actividades.php"><img src="svg/svgbike.svg" class="barra" alt="Actividades"></a>
        <br><h6 class="chica">Actividades</h6>
        </div>
        <div class="col">
        <a href="app_agenda.php"><img src="svg/svghora.svg" class="barra" alt="Agenda"/></a>
        <br><h6 class="chica">Agenda</h6>
        </div>
        <!--<div class="col">
        <a href="app_mensajes.php"><img src="svg/svgmessage.svg" class="barra" alt="Mensajes"/></a>
        <br><h6 class="chica">Mensajes</h6>
        </div>-->
        <div class="col">
        <a href="app_perfil.php"><img src="svg/svgsettings-brain.svg" class="barra" alt="Ajustes"/>
        <br></a><h6 class="chica">Ajustes</h6>
        </div>
      </div>
</footer>

</body>
</html>
