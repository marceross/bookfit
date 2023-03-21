<?
session_name("app_reservas");
session_start();
include("conex.php");

date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos

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
		$_SESSION['total_costo']=$_POST['total_costo'];
	}


	$existe=isset($_SESSION['autentificado']);
	if($existe)
	{
			if($_SESSION["autentificado"]!="SI")
		 {//Verifica si el usuario esta autenticado                           
				//session_destroy();
			 //echo $_SESSION["autentificado"];
			   header("Location: login.html");
			   exit();
		 }		
	 }
	 else
	 {		  
		 $_SESSION['autentificado']="NO";
		 header("Location: login.html");
		 exit();
	 }


	/*echo  "Id horario: ".$_SESSION['id_horario_guardados'];
	echo "<br>Fecha: ".$_SESSION['fecha_clase'];
	echo "<br>Costo: ".$_SESSION['total_costo'];
	exit();*/

	$error=0;

	//Buscamos el credito del usuario
	$datos_usuarios=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='".$_SESSION['usuario_act']."'");
	$dato_usuario=mysqli_fetch_array($datos_usuarios);	


	if($dato_usuario['credito']<$_SESSION['total_costo'])
	{
		$error=1;
		//echo  "No tienes suficiente credito";
		//exit();
	}

	

	if($error==0)	
	{	
		//echo "hasta aca estamos";
		//exit();
		$i=0;
		foreach ($_SESSION['id_horario_guardados'] as $estado) {
			
			//echo $estado."<br>";
			//exit();
			if(isset($_SESSION['invitacion']))
			{
				$id_usuario=$_SESSION['dni_invitado'];
				$id_usuario_invitador=$_SESSION['usuario_act'];
				unset($_SESSION['invitacion']);
			}
			else
			{
				$id_usuario=$_SESSION['usuario_act'];
				$id_usuario_invitador=0;
			}
			$fecha=$_SESSION['fecha_clase'][$i];
			$datos_horarios=explode("_",$estado);
			$id_horario=$datos_horarios[0];

			//echo "Reseva: ".$datos_horarios[1];
			//exit();

			if($datos_horarios[1]=='1')
			{
				//echo "Estamos aca<br><br>";
				//echo "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario, dni_invitador) VALUES ('$id_usuario','$fecha','$id_horario', '$id_usuario_invitador')";
				//exit();
				if(!mysqli_query($mysqli,"INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario, dni_invitador) VALUES ('$id_usuario','$fecha','$id_horario', '$id_usuario_invitador')"))
				{
					$error=2;
					
					//echo  "Errorxxxx: ".mysqli_error($mysqli);
					//exit();
				}
			}
		$i++;
		}	
		if($error==0)
		{
			//Acualiza el credito	
			mysqli_query($mysqli, "UPDATE registrados SET credito=credito-".$_SESSION['total_costo']." WHERE dni=".$_SESSION['usuario_act']);
			header("Location:app_agenda.php");
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
<LINK href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">

<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
-->

<script src="js/jquery-3.6.0.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
<?	
	if($error==1)
	{
?>
		<div class="alert alert-danger">
			<p>No tenés suficientes puntos</p>
			<br>
			<p><a href="app_reserva.php?act_seleccionada=<? echo $_SESSION['actividad_sel'];?>" class="btn btn-primary">Volver</a></p>
		</div>
<?
	}
	if($error==2)
	{
?>
		<div class="alert alert-danger">
			<p>Esa reserva ya existe.</p>
			<p><? echo mysqli_error($mysqli);?></p>
			<br>
			<p><a href="app_reserva.php?act_seleccionada=<? echo $_SESSION['actividad_sel'];?>" class="btn btn-primary">Volver</a></p>
		</div>
<?
	}
	if($error==3)
	{
		echo "Error de base de datos: ".mysqli_error($mysqli);
	}
?>
</body>
</html>
