<?php
session_name('app_reservas');// AGREGAR session_name('app_sistema'); a los otros archivos, los que son del sistema anterior
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
}else{
    	header("Location:login.php");
}



include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos

if(isset($_POST['usuario'])){
    
}else{
    header("Location:login.php");
    exit();
}

$usuario=str_replace("-","",$_POST['usuario']);
$clave=$_POST['clave'];
$r=mysqli_query($mysqli,"SELECT * FROM registrados WHERE dni='$usuario' AND clave='$clave'");






$datos_usuario=mysqli_fetch_array($r);

if($datos_usuario){

$array_fecha=getdate();




$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);

//Verifico si la fecha de vencimiento actual esta vencida
if(strtotime($datos_usuario['vencimiento'])<strtotime($fecha)){

mysqli_query($mysqli,"UPDATE registrados SET credito=0 WHERE dni='$usuario' AND clave='$clave'");
}

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
  <script src="js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="js/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 
</head>

<?php
if(mysqli_num_rows($r)==0)
{
?>
<div class="container">
	<br><br><h4><?php echo "NOMBRE DE USUARIO O PASS INCORRECTO";?></h4><br><br>
	<h6><a href="login_recuperar.html">Olvidaste la clave? <span class="badge badge-warning">Recuparar</span></a></h6>
	<input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
	<?php exit();?>
</div>
<?php
}
else
{	
	$_SESSION['autentificado']="SI";
	$_SESSION['usuario_act']=$datos_usuario['dni'];	
	
	header("Location:app_actividades.php");
	/*
	if($_SESSION['procedencia']=='app_reserva.php')
	{
		$_SESSION['procedencia']='';		
		header("Location:app_reserva_procesa.php");
	}
	else
	{
		header("Location:app_actividades.php");
	}
	*/
}
?>

</html>