<?
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
          <!--<li class="nav-item">
           <p><a class="nav-link" href="app_perfil.php"><img src="<?// echo $re['foto'];?>" alt="Foto perfil" width="32"></a></p>
          </li>-->
          <li class="nav-item">
            <h6><a class="nav-link"><span class="badge badge-pill badge-light"><? echo $re['nombre'];?></span></a></h6>
          </li>
          <li class="nav-item">
            <h6><a class="nav-link"><span class="badge badge-pill badge-dark"><? echo $credito_actual;?></span> puntos</a></h6>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
          </li>
        <?
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

<div class="container">
  <div class="row">
      <h5 class="grande"><span class="badge badge-pill badge-info"><? echo $credito_actual;?></span> puntos</h5>
      <h5 class="grande"><span class="badge badge-pill <? echo $clase_vencido; ?>"><? echo formato_latino ($re['vencimiento']);?></span> vencimiento</h5>
      <!--<h5><a class="nav-link" href="app_comprapuntos.php"><span class="badge badge-pill badge-info">Compra puntos</span></a></h5>-->

  </div>
</div>

<br>
<div class="div-menu">
	<div class="form-group col-md-6">
    <form name="form1" method="post" action="app_perfil_mod_procesa.php?dni=<? echo $dni;?>">
        <p>nombre 
          <input name="nom" type="text" class="form-control" id="nom" value="<? echo $re['nombre'];?>"/ required>
        </p>
        <p>apellido
          <input name="ape" type="text" class="form-control" id="ape" value="<? echo $re['apellido'];?>"/>
        </p>
        <p>dni
          <input name="dni" type="text" class="form-control" id="dni" value="<? echo $re['dni'];?>" readonly/>
        </p>
        <p>fecha de nacimento 
          <input name="nac" type="date" class="form-control" id="nac" value="<? echo $re['nacimiento'];?>"/>
        MM-DD-AAAA</p>
        <p>celular 
          <input name="cel" type="text" class="form-control" id="cel" value="<? echo $re['celular'];?>"/ required>
        </p>
        <!--<p>comentario
          <input name="com" type="text" class="form-control" id="com" value="<? echo $re['comentario'];?>" size="80" maxlength="500"/>
        </p>-->
        <p>mail
          <input name="mai" type="text" class="form-control" id="mai" value="<? echo $re['mail'];?>"/ pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
        </p>
        <p>clave
          <input name="cla" type="password" maxlength="15" class="form-control" id="cla" value="<? echo $re['clave'];?>"/ required>
        </p>

        <!--<p>actividad 
          <select name="act" id="act">
    	
    <?
	while($actividad=mysqli_fetch_array($actividades))
	{
		if($actividad['id_actividad']<>$re['actividad'])
		{
	?>
    		<option value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
    <?
         }
         else
         {
   ?>
   			<option selected value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
   			   
         
    <?
		}
	}
	?>
    </select>
        </p>
        <p>profesor 
          <select name="pro" id="pro">
    	
    <?
	while($profesor=mysqli_fetch_array($profesores))
	{
		if($profesor['id_profesor']<>$re['profesor'])
		{
		
	?>
    		<option value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
    <?
		}
		else
		{
	?>
    		<option selected value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
    <?
		}
	}
	?>
    </select>
        </p>
        <p>
          autorizacion 
          <?
		  if($re['autorizacion']=='N')
		  {
		  ?>
          	<input name="aut" type="checkbox" id="aut" value="S">
         <?
		  }
		  else
		  {
		 ?>
         	<input checked name="aut" type="checkbox" id="aut" value="S">
         <?
		  }
		 ?>
        </p>
        <p>
          certificado
            <?
		  if($re['certificado']=='N')
		  {
		  ?>
          	<input name="cer" type="checkbox" id="cer" value="S">
         <?
		  }
		  else
		  {
		 ?>
         	<input checked name="cer" type="checkbox" id="cer" value="S">
         <?
		  }
		 ?>
        </p>-->
        <p> 
          <input class="btn btn-primary" type="submit" name="Submit" value="Enviar">
        </p>
      </form><br><br>
  </div>
</div>

<div>
    <button id="toggleButton" onclick="toggleReservaAuto()">Toggle Reserva Auto</button>
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

<script src="script.js"></script>

</body>
</html>
