<?php
include("conex.php");
include("local_controla_app.php"); // session_name(''); session_start(); se agrega si no está el controla
include("confirmacion.js");
$id_usuario=$_SESSION['usuario_act'];

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

$array_fecha=getdate();
$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
//$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
$hora4=date("H:i:s");

$reservas=mysqli_query($mysqli,"SELECT actividad.nombre AS nombre_actividad, actividad_reservas.fecha AS fecha_reserva, actividad_dias_id_dia, id_actividad, id_reserva FROM actividad, actividad_reservas, actividad_horarios WHERE actividad_horarios.id_horario=actividad_reservas.actividad_horarios_id_horario AND actividad_horarios.actividad_id_actividad=actividad.id_actividad  AND registrados_dni='$id_usuario' AND actividad_reservas.fecha>='$fecha' GROUP BY actividad.nombre, actividad_reservas.fecha, actividad_dias_id_dia  ORDER BY fecha ASC");

//Aca consulta de invitaciones HABRIA QUE SACAR DEL WHILE SI YO QUIERO QUE MUESTRE LAS INVITACIONES HABIENDO CANCELADO MÍ RESERVA



?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales Socio</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="https://lokales.com.ar/favico.ico" rel="shortcut icon">

<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
-->

<script src="js/jquery-3.6.0.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="js/bootstrap.min.css" >

 <script src="js/jquery.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

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
        <?php
        if(!isset($_SESSION['usuario_act']))
        {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login_inscripcion.php">Registrarte</a>
          </li>
        <?php
        }
        else
        {
        ?>
          <li class="nav-item">
          <h6><a class="nav-link"><span class="badge badge-pill badge-light"><?php echo $dato_usuario['nombre'];?></span><span class="badge badge-pill badge-dark"><?php echo $credito_actual;?></span> puntos</a></h6>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
          </li>
        <?php
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

<div class="container" style="margin-top:30px">

<h3 class="card-title">Tus reservas</h3>
<?php
while($reserva=mysqli_fetch_array($reservas))
{
  $numero_dia_mes=date('d', strtotime($reserva['fecha_reserva'])); 
  $nombres_dias=mysqli_query($mysqli,"SELECT * FROM actividad_dias WHERE id_dia=".$reserva['actividad_dias_id_dia']);          
  $nombre_dia=mysqli_fetch_array($nombres_dias);

  $horarios=mysqli_query($mysqli, "SELECT * FROM actividad_reservas, actividad_horarios WHERE actividad_horarios_id_horario=id_horario AND registrados_dni='$id_usuario' AND actividad_reservas.fecha>='$fecha' AND actividad_id_actividad=".$reserva['id_actividad']." AND actividad_dias_id_dia=".$reserva['actividad_dias_id_dia']." ORDER BY hora");

  //Invitaciones
  $invitaciones=mysqli_query($mysqli, "SELECT * FROM actividad_reservas, actividad_horarios, registrados WHERE registrados_dni=dni AND actividad_horarios_id_horario=id_horario AND dni_invitador='$id_usuario' AND actividad_reservas.fecha>='$fecha' AND actividad_id_actividad=".$reserva['id_actividad']." AND actividad_dias_id_dia=".$reserva['actividad_dias_id_dia']." ORDER BY hora");

  
?>
  <div class="row justify-content-center">
    <h4><span class="badge badge-light"><?php echo $nombre_dia['nombre_dia'];?></span></h4>
    <h4><span class="badge badge-dark"><?php echo $numero_dia_mes;?></span></h4>
   
  </div>
    
  <div class="row">
    <h3 class="card-title"><span class="badge badge-pill badge-light"><?php echo $reserva['nombre_actividad'];?></span></h3>
  </div>


<?php
   //Mostrar Reservas
   while($horario=mysqli_fetch_array($horarios))
   {
?>
  <div class="row hori_line">
    <div class="col">
    <button type="button" class="btn btn-success btn-sm" value="disabled"><?php echo $horario['hora'];?></button>
    </div>
    <div class="col">
    <h6 class="card-title"><span class="badge badge-pill badge-info"><?php echo $horario['desc_especifica'];?></span></h6>
    <!--<span class="badge badge-info"><?php echo $horario['desc_especifica'];?></span>-->
    <!--<button type="button" class="btn btn-primary">Invitar</button>-->
    </div>
    <div class="col">
  <?php
// VER SI PUEDE CANCELAR O NO
      //$fecha_c=strtotime($reserva['fecha_reserva']);
      //$fecha_a=strtotime($fecha);
          
      //echo  "Fecha c: ".$fecha_c;
      //echo "<br>Fecha a: ".$fecha_a;

      $diferencia=(strtotime($reserva['fecha_reserva']." ".$horario['hora'])-strtotime($fecha." ".$hora4))/3600;
      // DIFERENCIA ENTRE hora de la reserva y la hora actual

      //echo "Diferencia: ".$diferencia;

       if($diferencia>=1)
       //($diferencia>=1 and $fecha_c==$fecha_a or $fecha_c>=$fecha_a)
       {
  ?>
          <a class="btn btn-outline-danger btn-sm" href="javascript:confirmar('¿Vas a cancelar la reserva?', 'app_cancela_reserva.php?idr=<?php echo $horario['id_reserva'];?>')">cancelar</a>
    <?php
       }
       else
       {
    ?>
        <a class="btn btn-outline-danger btn-sm" href="javascript:alert('Se puede cancelar hasta una hora antes de que empiece la clase')">cancelar</a>
    <?php
       }
    ?>

    </div>
  </div>


<?php
   }
    //Mostrar Invitaciones VER MENSAJE DE CANCELACIÓN, COMPARAR CON EL DE RESERVA...
    while($invitacion=mysqli_fetch_array($invitaciones))
   {
    ?>
      <div class="row hori_line">
        <div class="col">
          <button type="button" class="btn btn-info btn-sm" value="disabled"><?php echo $invitacion['hora'];?></button>
        </div>
        <div class="col">
          <h3 class="card-title"><span class="badge badge-pill badge-light"><?php echo $invitacion['nombre'];?></span></h3>          
        </div>
        <div class="col">
<?php
// VER SI PUEDE CANCELAR O NO
      //$fecha_c=strtotime($reserva['fecha_reserva']);
      //$fecha_a=strtotime($fecha);
          
      //echo  "Fecha c: ".$fecha_c;
      //echo "<br>Fecha a: ".$fecha_a;

      $diferencia=(strtotime($reserva['fecha_reserva']." ".$invitacion['hora'])-strtotime($fecha." ".$hora4))/3600;

      //echo "Diferencia: ".$diferencia;

       if($diferencia>=1)
       //($diferencia>=1 and $fecha_c==$fecha_a or $fecha_c>=$fecha_a)
       {
?>


        <a class="btn btn-outline-danger btn-sm" href="javascript:confirmar('¿Vas a cancelar la reserva?', 'app_cancela_reserva.php?idr=<?php echo $invitacion['id_reserva'];?>')">cancelar</a>

    <?php
       }
       else
       {
    ?>
        <a class="btn btn-outline-danger btn-sm" href="javascript:alert('Se puede cancelar hasta una hora antes de que empiece la clase')">cancelar</a>
    <?php
       }
    ?>

        </div>
      </div>

    <?php
   }
}
?>



<h3 class="card-title">Clases pasadas</h3> 
<?php
// FROM registrados WHERE  registrados.dni=actividad_reservas.registrados_dni
$reservas_viejas=mysqli_query($mysqli,"SELECT actividad.nombre AS nombre_actividad, actividad_reservas.fecha AS fecha_reserva, actividad_dias_id_dia, id_actividad, id_reserva, usuario FROM actividad, actividad_reservas, actividad_horarios, usuarios WHERE usuarios.id_usuario=actividad_reservas.id_usuario AND actividad_horarios.id_horario=actividad_reservas.actividad_horarios_id_horario AND actividad_horarios.actividad_id_actividad=actividad.id_actividad  AND registrados_dni='$id_usuario' AND actividad_reservas.fecha<'$fecha' AND asiste=1 GROUP BY actividad.nombre, actividad_reservas.fecha, actividad_dias_id_dia  ORDER BY fecha ASC LIMIT 5");

 //Mostrar Reservas PASADAS
   while($reserva_vieja=mysqli_fetch_array($reservas_viejas))
   {
?>
  <div class="row hori_line">
    <div class="col">
    <button type="button" class="btn btn-info" onClick="window.location='app_reserva.php?act_seleccionada=<?php echo $reserva_vieja['id_actividad'];?>'" ><?php echo $reserva_vieja['nombre_actividad'];?></button>
    </div>
    <div class="col">
    <h3 class="card-title">Profe <span class="badge badge-pill badge-light"><?php echo $reserva_vieja['usuario'];?></span></h3>          
    </div>
    <div class="col">
    <a class="btn btn-outline-primary" href="app_reserva.php?act_seleccionada=<?php echo $reserva_vieja['id_actividad'];?>">repetir</a>

    </div>
  </div>
<?php
}
?>

 <!--
<div class="container">

  <div class="row">

  <div class="col-xs-12 col-md-6">

    <div class="col-3">
    <button type="button" class="btn btn-info" value="disabled">09:00</button>
    </div>
    <div class="col-6">
    <h3 class="card-title"><span class="badge badge-pill badge-light">Nombre invitado</span></h3>
    </div>
    <div class="col-3">
    <button type="button" class="btn btn-outline-danger">cancelar</button>
    </div>
  </div>
-->


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
