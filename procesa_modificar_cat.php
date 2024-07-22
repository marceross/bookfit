<?
include("conex.php");
include("local_controla.php");

$cod=$_POST['cod'];
$foto_generica=$_POST['foto_generica'];
$nombre=$_POST['nombre'];
$cod_padre=$_POST['cod_padre'];
$margen=$_POST['margen'];
$activa=$_POST['activa'];

//UPDATE clientes donde los datos son como dice la variable
if (mysql_query("UPDATE categorias SET cod='$cod',foto_generica='$foto_generica',nombre='$nombre',cod_padre='$cod_padre',margen='$margen',activa='$activa' WHERE cod='$cod'",$link))
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
<p><a href="alta_modificar_cat.php">VOLVER A MODIFICAR</a> </p>
<p><a href="local_datos.php">HOME (local_datos)</a> </p>
<p> <a href="local_kill_session.php">DESCONECTAR</a></p>
</body>
</html>
