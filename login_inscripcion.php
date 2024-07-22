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
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
<script src="js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="js/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="js/jquery-3.6.0.js"></script>
 <script src="js/jquery.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

<script>
function buscar_cliente(dni)
{	
  //alert("DNI: "+dni);
  $.ajax({			
    url: 'buscar_cliente_inscripcion.php',
    data: 'dni_cli=' + dni,
    success: function(resp) {
      $('#cliente_encontrado').html(resp)
    }
  });
}
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

  <div class="container" style="margin-top:40px;">
    <form action="login_inscripcion_procesa.php" method="post" class="m_query" enctype="multipart/form-data" name="form1" style="width:90%;margin:0 auto;box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);padding:20px;">
        <div class="row">
          <div class="form-group col-sm-6">
            <label for="dni" class="form-label">Dni  <span class="badge badge-warning">*</span> (obligatorio)</label>
            <input type="text" class="form-control" id="dni" name="dni" onkeyup="buscar_cliente(this.value)" placeholder="Dni" pattern="[0-9]+"  required>
          </div>
          <div class="form-group col-sm-6">
            <label for="nombre">Nombre <span class="badge badge-warning">*</span></label>
            <div id="cliente_encontrado"><input type="text" class="form-control" id="nom" name="nom" placeholder="Nombre" required></div>
          </div>
          <div class="form-group col-sm-6">
            <label for="apellido">Apellido</label>
            <input type="text" class="form-control" id="ape" name="ape" placeholder="Apellido">
          </div>
        
          
          <div class="form-group col-sm-3">
            <label for="nacimiento">Nacimiento</label>
            <input type="date" class="form-control" id="nac" name="nac">
            MM-DD-AAAA
          </div>
          <div class="form-group col-sm-3">
            <label for="teléfono">Teléfono <span class="badge badge-warning">*</span></label>
            <input type="tel" class="form-control" id="tel" name="tel" placeholder="3515123456" maxlength="12" required>
          </div>
       
          <div class="form-group col-sm-12">
              <label for="comentario">Comentrario</label>
              <input type="text" class="form-control" id="com" name="com" placeholder="Teléfono de emergencia, contacto de emergencia, obra social">
            </div>
         
          
          <!--<div class="form-row">
            <div class="form-group col-md-6">
              <label for="foto">Foto</label>
              <input type="file" id="archivo1" name="archivo1"/>
            </div>-->

         
            <div class="form-group col-sm-4">
              <label for="text">Email <span class="badge badge-warning">*</span></label>
              <!--<input name="mai" type="text" class="form-control" id="mai" value="<? echo $re['mail'];?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>-->
              <input type="email" class="form-control" id="mai" name="mai" placeholder="Email" required>
            </div>
            <div class="form-group col-sm-4">
              <label for="clave">Clave <span class="badge badge-warning">*</span></label>
              <input type="password" maxlength="15" class="form-control" id="cla" name="cla" placeholder="Clave" required>
            </div>
            <div class="form-group col-sm-4">
              <label for="clave2">Clave <span class="badge badge-warning">*</span></label>
              <input type="password" maxlength="15" class="form-control" id="cl2" name="cla2" placeholder="Repetir clave" required>
            </div>
            <div class="form-group col-sm-12">
             <button type="submit" class="btn btn-primary">Registrame</button>
             </div>
        </div>
       
       <br><br>
       <h6><a href="login.php">Ya estoy inscripto <span class="badge badge-success">Entrar</span></a></h6>
      
       
      </form>
      
      
    </div>

</body>
</html>
