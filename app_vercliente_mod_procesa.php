<?
include("conex.php");
include("local_controla.php"); // para el admin
//include("local_controla_app.php"); para el cliente
$nombre=$_POST['nom'];
$apellido=$_POST['ape'];
$dni=$_POST['dni'];
$nacimiento=$_POST['nac'];
$celular=$_POST['cel'];
//$comentario=$_POST['com'];
$mail=$_POST['mai'];
$clave=$_POST['cla'];
//$actividad=$_POST['act'];
//$profesor=$_POST['pro'];
//$autorizacion=$_POST['aut'];
//$certificado=$_POST['cer'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

<div class="div-menu">
	<div class="form-group col-md-6">	
		<h4>
		<?

		/*
		if (mysqli_query($mysqli,"UPDATE registrados SET nombre='$nombre',apellido='$apellido',dni='$dni',nacimiento='$nacimiento',celular='$celular',comentario='$comentario',mail='$mail', clave='$clave', actividad='$actividad',profesor='$profesor',autorizacion='$autorizacion',certificado='$certificado' WHERE dni='$dni'"))
		*/
		if (mysqli_query($mysqli,"UPDATE registrados SET nombre='$nombre',apellido='$apellido',dni='$dni',nacimiento='$nacimiento',celular='$celular',mail='$mail', clave='$clave' WHERE dni='$dni'"))
		{
		mail("mibanez23@hotmail.com","LOKALES ALTA REGISTRADOS",$nombre." ".$apellido,"From:".$mail);
		mail($mail,"CONFIRMA REGISTRO LOKALES","Usuario: ".$dni."\nClave: ".$clave."\nHaz clic en el link de abajo para confirmar el mail:\nhttp://www.lokales.com.ar/login_mail_confirmar.php?usuario=".$dni."\nTu mail para recuparar la cuenta es: ".$mail,"From:hola@lokales.com.ar");

		echo "SE HA MODIFICADO CON EXITO";
		}
		else
		{
		echo "ERROR";
		echo mysqli_error($mysqli);
		}

		?>
		</h4>
	</div>

	<div class="form-group col-md-6">
		<h5><a href="app_profe.php" class="badge badge-info">Volver</a></h5>
	</div>
	<div class="form-group col-md-6">
		<h6><a href="local_kill_session.php" class="badge badge-danger">cerrar sesión</a></h6>
	</div>
</div>
</body>
</html>

<?
// ERA registrados_modprocesa.php
?>