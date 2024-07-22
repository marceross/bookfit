
<?php

session_name('app_reservas');
session_start();
include("conex.php");
include("biblioteca.php");
date_default_timezone_set('America/Argentina/Cordoba');

 
if(isset($_SESSION['usuario_act']))
{ 
    header("Location:app_actividades.php");
	exit();
}else{
    
}
?>

<style>
    
    @media only screen and (max-width: 767px) {
  .m_query{width:100%!important;}
}
</style>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>LOKALES TRAINING SPOT</title>
  <meta charset="UTF-8">
  <meta name="description" content="COMPLEJO DEPORTIVO EXTREMO --- ACTION SPORTS COMPLEX --- Centro deportivo de entrenamiento para deportes de accion">
  <meta name="keywords" content="deportes, extremos, acrobacia, parkour, calistenia, escalada, palestra, skate, bmx, roller, surf, mountainboard, snow, ski, snowboard, mountainboarding, trial, kite, kitesurf, tablas, boards">
  <meta name="author" content="Lokales Training Spot">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <LINK href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
<link href="estilo.css" rel="stylesheet" type="text/css">
  <script src="js/jquery-3.6.0.js"></script>
  <script src="js/bootstrap.min.js"  crossorigin="anonymous"></script>
  
   <script src="js/jquery.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
  
<link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">

<style>
  
/*html, body {
    height: 100%;
}

html {
    display: table;
    margin: auto;
}

body {
    font-weight: 600;
    display: table-cell;
    vertical-align: middle;
    padding: 4vw;
}
a:link {
  text-decoration: none;
}

a:visited {
  text-decoration: none;
}

a:hover {
  text-decoration: none;
}

a:active {
  text-decoration: none;
}

.div-menu {
  border: 1px solid lightgrey;
  border-radius: 20px;
  text-align: center;
  padding: 1vw;
}

.div-footer {
  border-top: 1px solid lightgray;
  padding: 2vw;
}
*/
</style>
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
            <a class="nav-link" href="login.php">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login_inscripcion.php">Registrarte</a>
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

<div class="container">
  <br><br><br>
  <form action="login_procesa.php" method="post" class="m_query" style="width:40%;margin:0 auto;box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);padding:20px;">
    <div class="div-menu">
      <div class="form-group col-md-12">
              <label for="usuario">Usuario</label>
              <input type="text" class="form-control" id="usuario" placeholder="dni" name="usuario" pattern="[0-9]+">
      </div>
      <div class="form-group col-md-12">
          <label for="clave">Clave</label>
          <input type="password" class="form-control" id="clave" placeholder="tu clave personal" name="clave">
      </div>
      <br>
      <div class="form-group" style="width:100%;margin:0 auto;text-align:center">
       <input type="submit" name="Submit" value="Entrar" class="btn btn-primary">   
       
       <h6 style="margin-top:20px"><a href="login_inscripcion.php">Primera vez acá? <span class="badge badge-success">Registrate</span></a></h6>
  <h6><a href="login_recuperar.html">Olvidaste la clave? <span class="badge badge-warning">Recuparar</span></a></h6>
      <div>
           
      </div>
    </div>
   
    <br><br>
  </div>
 </form>
 
  
  
  
</div>
</body>
</html>