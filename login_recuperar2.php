<?
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba');
$dni_cli=$_POST['dni'];
$mai=$_POST['email'];
$clientes=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$dni_cli' AND mail='$mai'");
//cuando lleva comillas'' o "", funciona igual mejor con comilla por seguridad
$cliente=mysqli_fetch_array($clientes);
$cla=$cliente['clave'];


if(!strpos($mai,"@") or !strpos($mai,"."))
{
  $error=1;	
}
if ($dni_cli<=0 or strlen($dni_cli)<6)
{
  $error=2;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>LOKALES TRAINING SPOT</title>
<meta charset="UTF-8">
<meta name="description" content="COMPLEJO DEPORTIVO EXTREMO --- ACTION SPORTS COMPLEX --- Centro deportivo de entrenamiento para deportes de accion">
<meta name="keywords" content="deportes, extremos, acrobacia, parkour, calistenia, escalada, palestra, skate, bmx, roller, surf, mountainboard, snow, ski, snowboard, mountainboarding, trial, kite, kitesurf, tablas, boards">
<meta name="author" content="Lokales Training Spot">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">

<script src="js/jquery-3.6.0.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

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
                <a class="nav-link" href="login.html">Entrar</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="login_inscripcion.html">Registrarte</a>
              </li>
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

<?
if($error==1)
{
?>
	<h2><?echo "El e-mail ingresado no es válido"?></h2><br><br>
	<input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
	<?exit();?>
<?
}
if($error==2)
{
?>
	<br><br>
  <h4><?echo "El DNI ingresado no es válido"?></h4><br><br>
	<input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
	<?exit();?>
<?
}

if($mai==$cliente['mail'] and $dni_cli==$cliente['dni'])
  {
    mail($mai,"CLAVE LOKALES","Usuario: ".$dni_cli."\nClave: ".$cla."\nSi no te acordabas la clave es buen momento para CAMBIARLA!!!\nTu mail de recuperacion es: ".$mai,"From:hola@lokales.com.ar");
    ?>
		<br><br>
    <h4><?echo "Te mandamos un mail con tu datos de inicio"?></h4><br><br>
		<a href="login.html" class="btn btn-primary">Comenzar</a>
		<?
  }
else
  {?>
    <br><br>
    <h4><?echo "LOS DATOS INGRESADOS NO COINCIDEN" //echo mysqli_error($mysqli);?></h4><br><br>
    <input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
  <?
  }
?>

</body>
</html>
