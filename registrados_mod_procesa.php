<?
include("conex.php");
//include("local_controla.php");
$nombre=$_POST['nom'];
$apellido=$_POST['ape'];
$dni=$_POST['dni'];
$nacimiento=$_POST['nac'];
$celular=$_POST['cel'];
$comentario=$_POST['com'];
$mail=$_POST['mai'];
$actividad=$_POST['act'];
$profesor=$_POST['pro'];
$autorizacion=$_POST['aut'];
$certificado=$_POST['cer'];

//UPDATE registrados donde los datos son como dice la variable
if (mysqli_query($mysqli,"UPDATE registrados SET nombre='$nombre',apellido='$apellido',dni='$dni',nacimiento='$nacimiento',celular='$celular',comentario='$comentario',mail='$mail',actividad='$actividad',profesor='$profesor',autorizacion='$autorizacion',certificado='$certificado' WHERE dni='$dni'"))
{
	echo "SE HA MODIFICADO CON EXITO";
}
else
{
	echo "ERROR";
	echo mysqli_error($mysqli);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<p>&nbsp;</p>
<p><a href="registrados.php">VOLVER A REGISTRADOS</a></p>
<p><a href="local_ventas.php">VENTAS</a></p>
<p> <a href="local_kill_session.php">DESCONECTAR</a></p>
</body>
</html>
