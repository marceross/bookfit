<?php
session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
$id_usuario=$_SESSION['usuario_act'];

	/*if(isset($_SESSION['invitacion']))
	{
		echo "llego<br><br>";
		echo  "Dni invitado: ".$_SESSION['dni_invitado'];
		exit();
	}*/

	if(isset($_POST['id_horario']))
	{
		$_SESSION['id_horario_guardados']=$_POST['id_horario'];
		$_SESSION['fecha_clase']=$_POST['fecha_clase'];
	}
	
	$error=0;	

	if($error==0)	
	{	
		//echo "hasta aca estamos";
		//exit();
		$i=0;
		foreach ($_SESSION['id_horario_guardados'] as $estado) {
			
			//echo $estado."<br>";
			//exit();			
			$fecha=$_SESSION['fecha_clase'][$i];
			$datos_horarios=explode("_",$estado);
			$id_horario=$datos_horarios[0];

			//echo "Reseva: ".$datos_horarios[1];
			//exit();

			if($datos_horarios[1]=='1')
			{			

				if(!mysqli_query($mysqli,"INSERT INTO actividad_horarios_susp (fecha, actividad_horarios_id_horario2, id_usuario) VALUES ('$fecha','$id_horario', '$id_usuario')"))
				{
					$error=2;
					echo  "Errorxxxx: ".mysqli_error($mysqli);
					exit();
				}
			}
		$i++;
		}	
		if($error==0)
		{
			//Acualiza el credito				
			header("Location:app_actividades2.php");
			exit();// es necesario?
		}
	}	
		
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
<script src="js/bootstrap.min.js"  crossorigin="anonymous"></script>
<link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">

</head>

<body>
<?php	
	
	if($error==2)
	{
?>
		<div class="alert alert-danger">
			<p>Ese horario ya fue cancelado.</p>
			<p><?php echo mysqli_error($mysqli);?></p>
			<br>
			<p><a href="app_actividades2.php" class="btn btn-primary">Volver</a></p>
		</div>
<?php
	}
	
?>
</body>
</html>
