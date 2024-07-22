<?php
include("conex.php");
include("local_controla_app.php");// es el controla de app_reservas
$nombre=$_POST['nom'];
$apellido=$_POST['ape'];
$dni=$_POST['dni'];
$nacimiento=$_POST['nac'];
$celular=$_POST['cel'];
//$comentario=$_POST['com'];

$comentario= '';

$mail=$_POST['mai'];
$clave=$_POST['cla'];
//$actividad=$_POST['act'];

$actividad='';
//$profesor=$_POST['pro'];
$profesor='';

//$autorizacion=$_POST['aut'];

$autorizacion='';
//$certificado=$_POST['cer'];

$certificado='';
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

<div class="div-menu" style="margin: 0 auto;text-align: center;margin-top: 50px;">
	<div class="form-group col-md-12">	
		<h4>
		<?php
		if (mysqli_query($mysqli,"UPDATE registrados SET nombre='$nombre',apellido='$apellido',dni='$dni',nacimiento='$nacimiento',celular='$celular',comentario='$comentario',mail='$mail', clave='$clave', actividad='$actividad',profesor='$profesor',autorizacion='$autorizacion',certificado='$certificado' WHERE dni='$dni'"))
		{
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

	<div class="form-group col-md-12">
		<h5><a href="app_perfil.php" class="badge badge-info">Volver</a></h5>
	</div>
</div>
</body>
</html>

<?php
// ERA registrados_modprocesa.php
?>