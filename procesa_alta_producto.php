<?
	include("conex.php");
	include("local_controla2.php");	
	$archivo1=$_FILES["archivo1"]["tmp_name"];	
	$archivo1_name=$_FILES["archivo1"]["name"];
	$nombre=$_POST['nombre'];
	$cod_cat=$_POST['cod_cat'];
	$marca=$_POST['marca'];
	$proveedor=$_POST['proveedor'];
	$costo=$_POST['costo'];
	$descripcion=$_POST['descripcion'];
	$error=0;
	if($nombre=='' or $cod_cat=='' or $costo=='')
	{
		$error=1;
	}
	else
	{
		//Transmision de archivo
		if($archivo1_name<>'')
		{
		//Extraigo la extension del archivo
			$largo_nom=strlen($archivo1_name);
			$comienzo_ext=$largo_nom - 4;
			$extension=substr($archivo1_name,$comienzo_ext,4);			
			//Busco un nombre adecuado para el archivo
			$nro=0;			
			$nombre_adecuado="adjunto".$nro;
			clearstatcache();	
			while(file_exists("archivos_recibidos/".$nombre_adecuado.$extension))
			{				
				$nro++;
				$nombre_adecuado="adjunto".$nro;
				clearstatcache();
			}	
			$archivo1_name=$nombre_adecuado.$extension;		
			$dpath="archivos_recibidos/".$archivo1_name;
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
		$ruta="archivos_recibidos/".$archivo1_name;
		if($ruta=="archivos_recibidos/")
		{
			$ruta='';
		}
		if(mysqli_query($mysqli,"INSERT INTO productos (foto, nombre, cod_cat, costo, id_marca, id_proveedor, descripcion) VALUES ('$ruta','$nombre','$cod_cat','$costo','$marca','$proveedor','$descripcion')"))
		{
			$error=0;
		}
		else
		{
			$error=3;
		}
		
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?
	if($error==0)
	{
		echo "El producto se cargo correctamente.";
	}
	if($error==1)
	{
		echo "Dejo un campo obligatorio en blanco";
	}
	if($error==2)
	{
		echo "El archivo de foto no se pudo subir";
	}
	if($error==3)
	{
		echo "Error de base de datos: ".mysqli_error($mysqli);
	}
?>
<p></p><a href="alta_producto.php">VOLVER A CARGA DE PRODUCTO</a></p>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
</body>
</html>
