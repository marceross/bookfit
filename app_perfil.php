<?php
include("conex.php");
include("local_controla_app.php"); // session_name(''); session_start(); se agrega si no está el controla
include("biblioteca.php");
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();
$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
if(isset($_SESSION['usuario_act']))
{ 
  //Buscamos el credito del usuario
	$reg=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='".$_SESSION['usuario_act']."'");
  $re=mysqli_fetch_array($reg);
  $credito_actual=$re['credito'];
  $logueado="S";
  $dni=$re['dni'];
}
else
{
  $credito_actual=0;
  $logueado="N";
}

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
<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
<script src="js/jquery-3.6.0.js"></script>
<script src="js/bootstrap.min.js"  crossorigin="anonymous"></script>
<link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">

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
            <a class="nav-link" href="login_inscripcion.html">Registrarte</a>
          </li>
        <?php
        }
        else
        {
        ?>
          <!--<li class="nav-item">
           <p><a class="nav-link" href="app_perfil.php"><img src="<?php echo $re['foto'];?>" alt="Foto perfil" width="32"></a></p>
          </li>-->
          <li class="nav-item">
            <h6><a class="nav-link"><span class="badge badge-pill badge-light"><?php echo $re['nombre'];?></span></a></h6>
          </li>
          <li class="nav-item">
            <h6><a class="nav-link"><span class="badge badge-pill badge-dark"><?php echo $credito_actual;?></span> puntos</a></h6>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
          </li>
        <?php
        }
        ?>
        </ul>
        <!--
        <form class="form-inline mt-2 mt-md-0" method="post" action="app_reserva_procesa.php" enctype="multipart/form-data">
          <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Buscar">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
        -->
      </div>
    </nav>
</header>

<div class="container" style="margin-top:30px;">
  <div class="row">
      <h5 class="grande"><span class="badge badge-pill badge-info"><?php echo $credito_actual;?></span> puntos</h5>
      <h5 class="grande"><span class="badge badge-pill <?php echo $clase_vencido; ?>"><?php echo formato_latino ($re['vencimiento']);?></span> vencimiento</h5>
      <!--<h5><a class="nav-link" href="app_comprapuntos.php"><span class="badge badge-pill badge-info">Compra puntos</span></a></h5>-->

  </div>


<br>
<div class="div-menu">
	<div class="form-group">
    <form name="form1" method="post" action="app_perfil_mod_procesa.php?dni=<?php echo $dni;?>">
        <div class="row">
        <div class="col-sm-6">
        <p>nombre 
          <input name="nom" type="text" class="form-control" id="nom" value="<?php echo $re['nombre'];?>"/ required>
        </p>
        
        </div>
        <div class="col-sm-6">
        <p>apellido
          <input name="ape" type="text" class="form-control" id="ape" value="<?php echo $re['apellido'];?>"/>
        </p>
        </div>
        <div class="col-sm-6">
        <p>dni
          <input name="dni" type="text" class="form-control" id="dni" value="<?php echo $re['dni'];?>" readonly/>
        </p>
         </div>
         <div class="col-sm-6">
        <p>fecha de nacimento 
          <input name="nac" type="date" class="form-control" id="nac" value="<?php echo $re['nacimiento'];?>"/>
        MM-DD-AAAA</p>
        </div>
        <div class="col-sm-6">
        <p>celular 
          <input name="cel" type="text" class="form-control" id="cel" value="<?php echo $re['celular'];?>"/ required>
        </p>
          </div>
        <!--<p>comentario
          <input name="com" type="text" class="form-control" id="com" value="<?php echo $re['comentario'];?>" size="80" maxlength="500"/>
        </p>-->
        <div class="col-sm-6">
        <p>mail
          <input name="mai" type="text" class="form-control" id="mai" value="<?php echo $re['mail'];?>"/ pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
        </p>
        </div>
        <div class="col-sm-6">
        <p>clave
          <input name="cla" type="password" maxlength="15" class="form-control" id="cla" value="<?php echo $re['clave'];?>"/ required>
        </p>
        </div>

        <?php /*?><p>actividad 
          <select name="act" id="act">
    	
    <?php
	while($actividad=mysqli_fetch_array($actividades))
	{
		if($actividad['id_actividad']<>$re['actividad'])
		{
	?>
    		<option value="<?php echo $actividad['id_actividad'];?>"><?php echo $actividad['nombre'];?></option>
    <?php
         }
         else
         {
   ?>
   			<option selected value="<?php echo $actividad['id_actividad'];?>"><?php echo $actividad['nombre'];?></option>
   			   
         
    <?php
		}
	}
	?>
    </select>
        </p>
        <p>profesor 
          <select name="pro" id="pro">
    	
    <?php
	while($profesor=mysqli_fetch_array($profesores))
	{
		if($profesor['id_profesor']<>$re['profesor'])
		{
		
	?>
    		<option value="<?php echo $profesor['id_profesor'];?>"><?php echo $profesor['nombre'];?></option>
    <?php
		}
		else
		{
	?>
    		<option selected value="<?php echo $profesor['id_profesor'];?>"><?php echo $profesor['nombre'];?></option>
    <?php
		}
	}
	?>
    </select>
        </p>
        <p>
          autorizacion 
          <?php
		  if($re['autorizacion']=='N')
		  {
		  ?>
          	<input name="aut" type="checkbox" id="aut" value="S">
         <?php
		  }
		  else
		  {
		 ?>
         	<input checked name="aut" type="checkbox" id="aut" value="S">
         <?php
		  }
		 ?>
        </p>
        <p>
          certificado
            <?php
		  if($re['certificado']=='N')
		  {
		  ?>
          	<input name="cer" type="checkbox" id="cer" value="S">
         <?php
		  }
		  else
		  {
		 ?>
         	<input checked name="cer" type="checkbox" id="cer" value="S">
         <?php
		  }
		 ?>
        </p><?php */?>
         <div class="col-sm-12">
        <p> 
          <input class="btn btn-primary" type="submit" name="Submit" value="Actualizar">
        </p>
        </div>
        </div>
      </form><br><br>
  </div>
</div>
</div>

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
