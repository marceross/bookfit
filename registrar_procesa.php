<?php
include("conex.php");
$nom=$_POST['nom'];
$ape=$_POST['ape'];
$dni=$_POST['dni'];
$dia=$_POST['dia'];$mes=$_POST['mes'];$anio=$_POST['anio'];
$nac=$anio."-".$mes."-".$dia;
$caracteristica=$_POST['caracteristica'];
$celular=$_POST['celular'];
$com=$_POST['com'];
$archivo1=$_FILES["archivo1"]["tmp_name"];	
$archivo1_name=$_FILES["archivo1"]["name"];
$error=0;
$mai=$_POST['mai'];
$act=$_POST['act'];
$pro=$_POST['pro'];
if(isset($_POST['per']))//isset significa si existe
{
	$per=$_POST['per'];
}
else
{
	$per='N';// else lo pone en N
}
if(isset($_POST['cer']))
{
	$cer=$_POST['cer'];
}
else
{
	$cer='N';
}

if($caracteristica<>"" and $celular<>"")
{
	$numero_completo="0".$caracteristica."15".$celular;
}
else
{
	$numero_completo="";
}
//if($cla<>$rep) cuando habia clave y se comprobaba si coincidian
//{
//	$error=1;
//}
if($archivo1_name<>'')
{
	$tam=filesize($archivo1)/1024;
	if($tam>500)
	{
		$error=3;
	}
	else
	{
	//Extraigo la extension del archivo
		$largo_nom=strlen($archivo1_name);
		$comienzo_ext=$largo_nom - 4;
		$extension=substr($archivo1_name,$comienzo_ext,4);
		
		//Busco un nombre adecuado para el archivo
		$nro=0;			
		$nombre_adecuado="adjunto".$nro;
		clearstatcache();	
		while(file_exists("imagenes_registrados/".$nombre_adecuado.$extension))
		{				
			$nro++;
			$nombre_adecuado="adjunto".$nro;
			clearstatcache();
		}	
		$archivo1_name=$nombre_adecuado.$extension;		
		$dpath="imagenes_registrados/".$archivo1_name;
		if(move_uploaded_file($archivo1, $dpath))
		{//Se realiza la transmision del archivo al servidor.
			$error=0;
			chmod($dpath,0777);				
		}
		else
		{
			$error=2;
		}	
	}	
}
$array_fecha=getdate();
$fecha=strval($array_fecha['year'])."-".strval($array_fecha['mon'])."-".strval($array_fecha['mday']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<body>
<p align="center">&nbsp;</p>
</br>
<table width="75%" border="0" align="center">
  <tr>
    <td colspan="3"><div align="center">
    <?php

if($error==3)
{
	echo "El archivo de imagen no puede tener mas de 100K<br><br>";
	echo "<input type=button value=Atrás onclick=history.back() style=font-family: Verdana; font-size: 8 pt>";
	exit();
}
if($nom=='' or $ape=='' or $dni=='' or $nac=='' or $caracteristica=='' or $celular=='' or $mai=='' or $act=='-seleccionar-' or $pro=='-seleccionar-')// or $mespag=='-seleccionar-')
{
    echo"ERROR: CAMPO VACIO<br><br>";
	echo "<input type=button value=Atrás onclick=history.back() style=font-family: Verdana; font-size: 8 pt>";
	$error=4;
}	
else
{
	if($archivo1_name<>"")
	{
		$ruta="imagenes_registrados/".$archivo1_name;
	}
	else
	{	
		$ruta="";
	}
	if(mysqli_query($mysqli,"INSERT INTO registrados(nombre,apellido,dni,nacimiento,comentario,foto,mail,actividad,profesor,fecha,celular,autorizacion,certificado)VALUES ('$nom','$ape','$dni','$nac','$com','$ruta','$mai','$act','$pro','$fecha','$numero_completo','$per','$cer')"))
	{

		echo "SE HA REGISTRADO CON EXITO<br><br>";
		echo "<input type=button value=Atrás onclick=history.back() style=font-family: Verdana; font-size: 8 pt>";

	}
	else
	{
    	echo "EL NOMBRE DE USUARIO YA EXISTE<br><br>";
		echo mysqli_error($mysqli);
		echo "<input type=button value=Atrás onclick=history.back() style=font-family: Verdana; font-size: 8 pt>";
	}
}
?>
   </div></td>
  </tr>
  <tr>
    <td width="41%">&nbsp;</td>
    <td width="21%"><p>&nbsp;</p>
    <p><a href="local_ventas.php">Volver a ventas</a></p></td>
    <td width="38%">&nbsp;</td>
  </tr>
</table>
</body>
</html>