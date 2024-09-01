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

<link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">

 <script src="js/jquery-3.2.1.min.js"></script>
 <link href="js/lobibox.css" rel="stylesheet" type="text/css" />
<script src="js/lobibox.min.js"></script>


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
    <form action="login_inscripcion_procesa.php" method="post" id="register_form" class="m_query" enctype="multipart/form-data" name="form1" style="width:90%;margin:0 auto;box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);padding:20px;">
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
              <label for="mai">Email <span class="badge badge-warning">*</span></label>
            
              <input type="email" class="form-control" id="mai" name="mai" placeholder="Email" required>
            </div>
            <div class="form-group col-sm-4">
              <label for="clave">Clave <span class="badge badge-warning">*</span></label>
              <input type="password" maxlength="15" class="form-control" id="cla" name="cla" placeholder="Clave" required>
            </div>
            <div class="form-group col-sm-4">
              <label for="cl2">Repetir Clave <span class="badge badge-warning">*</span></label>
              <input type="password" maxlength="15" class="form-control" id="cl2" name="cla2" placeholder="Repetir clave" required>
            </div>
            <div class="form-group col-sm-12">
             <button type="button" onclick="registration_form()" class="btn btn-primary">Registrame</button>
             </div>
        </div>
       
       <br><br>
       <h6><a href="login.php">Ya estoy inscripto <span class="badge badge-success">Entrar</span></a></h6>
      
       
      </form>
      
      
    </div>
    
        <script>
            
            function registration_form() {
                
                
                const email = $('#mai').val();
                const emailPattern = 
                /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                const isValid = emailPattern.test(email);
                
                
                var dni = $('#dni').val();
                var nom = $('#nom').val();
                var tel = $('#tel').val();
                
                var cla = $('#cla').val();
                var cl2 = $('#cl2').val();
                
                
                
                if(dni == ''){
                    Lobibox.notify('error', {
        					size: 'mini',
        					rounded: true,
        					delayIndicator: false,
        					position: "bottom right",
        					sound: false, 
        					msg: 'Please Enter the DNI',
    					
    
    				});
                }else if(nom == ''){
                     Lobibox.notify('error', {
        					size: 'mini',
        					rounded: true,
        					delayIndicator: false,
        					position: "bottom right",
        					sound: false, 
        					msg: 'Please Enter the Nombre',
    					
    
    				});
                }else if(tel == ''){
                     Lobibox.notify('error', {
        					size: 'mini',
        					rounded: true,
        					delayIndicator: false,
        					position: "bottom right",
        					sound: false, 
        					msg: 'Please Enter the Teléfono',
    					
    
    				});
                }
                else if(isValid == false){
                
                    Lobibox.notify('error', {
        					size: 'mini',
        					rounded: true,
        					delayIndicator: false,
        					position: "bottom right",
        					sound: false, 
        					msg: 'Please Enter Valid email',
    					
    
    				});
				
                }else if(cla == ''){
                     Lobibox.notify('error', {
        					size: 'mini',
        					rounded: true,
        					delayIndicator: false,
        					position: "bottom right",
        					sound: false, 
        					msg: 'Please Enter the Clave',
    					
    
    				});
                }else if(cl2 == ''){
                     Lobibox.notify('error', {
        					size: 'mini',
        					rounded: true,
        					delayIndicator: false,
        					position: "bottom right",
        					sound: false, 
        					msg: 'Please Enter the Repetir Clave',
    					
    
    				});
                }else if(cl2 != cla){
                     Lobibox.notify('error', {
        					size: 'mini',
        					rounded: true,
        					delayIndicator: false,
        					position: "bottom right",
        					sound: false, 
        					msg: 'Clave and  Repetir Clave not match',
    					
    
    				});
                }
                
                
                else{
				    document.getElementById("register_form").submit();
                }
                
                
                
            }
            
        </script>

</body>
</html>
