<?
include("conex.php");
include("local_controla.php");

$nombre=$_POST['nombre'];
$cod=$_POST['cod'];
$descripcion=$_POST['descripcion'];
$costo=$_POST['costo'];
$foto=$_POST['foto'];
$cod_cat=$_POST['cod_cat'];
$id_marca=$_POST['id_marca'];
$stock=$_POST['stock'];

//UPDATE clientes donde los datos son como dice la variable
if (mysqli_query($mysqli,"UPDATE productos SET nombre='$nombre',cod='$cod',descripcion='$descripcion',costo='$costo',foto='$foto',cod_cat='$cod_cat',id_marca='$id_marca',stock='$stock' WHERE cod='$cod'"))
{
	echo "SE HA MODIFICADO CON EXITO";
}
else
{
	echo "ERROR";
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
<p><a href="alta_modificar.php">VOLVER A MODIFICAR</a> </p>
<p><a href="local_datos.php">HOME (local_datos)</a> </p>
<p> <a href="local_kill_session.php">DESCONECTAR</a></p>
</body>
</html>
