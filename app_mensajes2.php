<?
include("conex.php");
include("local_controla_app.php");
include("biblioteca.php");
$id_usuario=$_SESSION['usuario_act'];

// para mostrar profesores en opcion de destinatario
$usuario_profe=mysqli_query($mysqli,"SELECT * FROM usuarios WHERE activo='s' AND id_tipo_usuario='3'");

// trae todos los mensajes de un usuario, enviados y recibidos
 /*$mensajes_fechas=mysqli_query($mysqli,"SELECT fecha FROM mensajes, mensajes_destinatarios WHERE mensajes.id_mensaje=mensajes_destinatarios.id_mensaje AND (id_usuario_rem='$id_usuario' or id_usuario='$id_usuario') GROUP BY fecha ORDER BY fecha DESC");*/

?>
<html>
<head>
<title>Lokales Mensajes</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">
<script src="js/jquery-3.6.0.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<script>
function buscar_mensaje(buscar_men)
{	
  //alert("mensaje: "+buscar_men);
  $.ajax({			
    url: 'buscar_mensaje.php',
    data: 'buscar_men=' + buscar_men,
    success: function(resp) {
      $('#mensaje_encontrado').html(resp)
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
          <h6><a class="nav-link" href="perfil.php"><span class="badge badge-pill badge-light"><? echo $dato_usuario['nombre'];?></span><span class="badge badge-pill badge-dark"><? echo $credito_actual;?></span> puntos</a></h6>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
          </li>
        <?
        }
        ?>
        </ul>
      </div>
    </nav>
</header>

<br>
<div class="container">
  <form action="" method="post" enctype="multipart/form-data" name="">
        <div class="form-row row justify-content-center">
          <div class="form-group col-md-4">
            <input type="text" class="form-control" id="buscar_men" name="buscar_men" onkeyup="buscar_mensaje(this.value)" placeholder="Buscar chat o @alias">
            <!--<p>@alias para buscar usuario</p>-->
          </div>
        </div>
  </form>
</div>

<div class="container">

<?php
  //while($mensaje_fecha=mysqli_fetch_array($mensajes_fechas))
 // {
   // $fecha_movimiento=$mensaje_fecha['fecha'];
    /*$msjes=mysqli_query($mysqli, "SELECT * FROM mensajes, mensajes_destinatarios WHERE mensajes.id_mensaje=mensajes_destinatarios.id_mensaje AND (id_usuario_rem='$id_usuario' or id_usuario='$id_usuario') AND fecha='$fecha_movimiento' ORDER BY hora ASC");*/

    $msjes=mysqli_query($mysqli, "SELECT * FROM mensajes WHERE (id_usuario_rem='$id_usuario' or id_dest='$id_usuario') ORDER BY id_conversacion DESC, fecha DESC, hora");
    
    
    $i=0;
    $ultima_fecha='';
    while($msj=mysqli_fetch_array($msjes))
    {   

      if(!isset($id_conversacion))  
      {
        $id_conversacion=$msj['id_conversacion'];
        
      }


      $remitente=$msj['id_usuario_rem'];
      //Buscamos el nombre del remitente en registrados, si no lo encuentra busca en usuarios
      $registrados=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$remitente'");
      if(mysqli_num_rows($registrados)==0)
      {
        $usuarios=mysqli_query($mysqli, "SELECT * FROM usuarios WHERE id_usuario='$remitente'");
        $usuario=mysqli_fetch_array($usuarios);
        $nombre_remitente=$usuario['usuario'];
      }
      else
      {
        $registrado=mysqli_fetch_array($registrados);
        $nombre_remitente=$registrado['nombre'];
      }

// mostrar campo de mensaje si es el final de la conversación
// si el id_mensaje es el último de la conversación mostrar campo
     ?>

        <div class="row justify-content-start">
        <?
          if($id_conversacion<>$msj['id_conversacion'])
          {      
         ?>
            <form action="app_mensaje_procesa.php?id_conversacion=<? echo $id_conversacion;?>" method="post" enctype="multipart/form-data" name="">
            <div class="form-row row justify-content-center">
              <div class="form-group col-md-4">
                <input type="text" class="form-control" id="texto_mensaje" name="texto_mensaje" placeholder="Continuar chat...">
              </div>
            </div>
            </form>
      <?php
          $id_conversacion=$msj['id_conversacion'];
          }
      ?>
          <div class="col-2">
          <img src="marce.jpg" alt="M" width="40" height="40">
          </div>
          <div class="col-5">
            <h6><span class="badge badge-light"><? echo $nombre_remitente;?></span><span class="badge badge-info">
              <? 
              if($ultima_fecha<>$msj['fecha'])
              {
                echo " ". formato_latino($msj['fecha']);?><? echo " ". $msj['hora'];
                $ultima_fecha=$msj['fecha'];              
              }
              else
              {
                echo " ". $msj['hora'];                
              }
              ?>
            </span></h6>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-11">
            <p><? echo $msj['mensaje'];?></p>
          </div>
        </div>
<?php      
      $i++;
    }
  
?>
    <form action="app_mensaje_procesa.php?id_conversacion=<? echo $id_conversacion;?>" method="post" enctype="multipart/form-data" name="">
         <div class="form-row row justify-content-center">
           <div class="form-group col-md-4">
             <input type="text" class="form-control" id="texto_mensaje" name="texto_mensaje" placeholder="Continuar chat2...">
           </div>
         </div>
         </form>
</div>

<div class="container">
  <form action="app_mensaje_procesa.php" method="post" enctype="multipart/form-data" name="">
        <div class="form-row row justify-content-center">
          <div class="form-group col-sm-10 col-md-6 col-lg-4">
            <textarea class="form-control" id="texto_mensaje" name="texto_mensaje" placeholder="Nueva conversación"></textarea>
            <input type="text" class="form-control" id="destino" name="destino" placeholder="Destino">
            <p>@alias nuevo destinatario</p>
  
 <select name="pro" id="pro">
  <?
	while($usu_profe=mysqli_fetch_array($usuario_profe))
	{
	?>
    	<option value="<? echo $usu_profe['id_usuario'];?>"><? echo $usu_profe['usuario'];?></option>
    <?
	}
	?>
  </select>
            <button type="submit" class="btn btn-primary btn-sm">enviar</button>
          </div>
        </div>
  </form>
</div>


<br><br>


<!--ejemplo ---------------------------------------------------------------------
<div class="container">

  <div class="row justify-content-center">
    <div class="col-6">
      <h5>Nov 18, 2021</h5>
    </div>
  </div>

  <form action="" method="post" enctype="multipart/form-data" name="">
        <div class="form-row row justify-content-center">
          <div class="form-group col-md-4">
            <input type="text" class="form-control" id="" name="" placeholder="Continuar chat...">
          </div>
        </div>
  </form>

  <div class="row justify-content-start">
    <div class="col-2">
    <img src="marce.jpg" alt="M" width="40" height="40">
    </div>
    <div class="col-5">
      <h6><span>Marcelo</span> 21:10</h6>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-11">
      <p>3 Hola Martina, como estas? las clases de escalada podes ver en la app, www.lokales.com.ar/inscripcion.html ahí vas a encontrar todo. saludos</p>
    </div>
  </div>

  <div class="row justify-content-start">
    <div class="col-2">
    <img src="marce.jpg" alt="J" width="40" height="40">
    </div>
    <div class="col-5">
      <h6><span>Martina</span> 21:00</h6>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-11">
      <p>2 Hola, quisiera saber de las clases de escalada, horario y precios. gracias</p>
    </div>
  </div>


  <form action="" method="post" enctype="multipart/form-data" name="">
        <div class="form-row row justify-content-center">
          <div class="form-group col-md-4">
            <input type="text" class="form-control" id="" name="" placeholder="Continuar chat...">
          </div>
        </div>
  </form>


<div class="row justify-content-start">
    <div class="col-2">
    <img src="marce.jpg" alt="J" width="40" height="40">
    </div>
    <div class="col-5">
      <h6><span>Marcelo</span> 11:00</h6>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-11">
      <p>1 Hola Tato... sisi, empezaron, te esperamos</p>
    </div>
  </div>


  <div class="row justify-content-center">
    <div class="col-6">
      <h5>Nov 17, 2021</h5>
    </div>
  </div>

  <div class="row justify-content-start">
    <div class="col-2">
    <img src="marce.jpg" alt="J" width="40" height="40">
    </div>
    <div class="col-5">
      <h6><span>Tato</span> 14:00</h6>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-11">
      <p>0 Hola Marce, como estas? empezaron las clases?</p>
    </div>
  </div>

</div>-->


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
        <div class="col">
        <a href="app_mensajes.php"><img src="svg/svgmessage.svg" class="barra" alt="Mensajes"/></a>
        <br><h6 class="chica">Mensajes</h6>
        </div>
        <div class="col">
        <a href="app_perfil.php"><img src="svg/svgsettings-brain.svg" class="barra" alt="Ajustes"/>
        <br></a><h6 class="chica">Ajustes</h6>
        </div>
      </div>
</footer>

</body>
</html>
