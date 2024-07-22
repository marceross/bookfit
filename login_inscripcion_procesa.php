<?php
session_name('app_reservas'); // AGREGAR session_name('app_sistema'); a los otros archivos, los que son del sistema anterior
session_start();
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
if(!isset($_GET['invitado']) and !isset($_GET['venta']))
// dato que viene de modal de app_reserva al invitar 
// dato que viene de modal de app_profeventas
{// este es el camino si el cliente se registra por login_inscripcion.php
	$nom=$_POST['nom'];
	$ape=$_POST['ape'];
	$dni=$_POST['dni'];
	$nac=$_POST['nac'];
	$tel=$_POST['tel'];
	$com=$_POST['com'];
	
	
	//$archivo1= $_FILES["archivo1"]["tmp_name"];	
	//$archivo1_name=$_FILES["archivo1"]["name"];
	
	$archivo1= '';
	$archivo1_name= '';
	
	$error=0;
	$mai=$_POST['mai'];
	$cla=$_POST['cla'];
	$cla2=$_POST['cla2'];

	if($cla<>$cla2) //clave, coincide?
	{
		$error=1;
	}
	//Validamos mail
	if(!strpos($mai,"@") or !strpos($mai,"."))
	{
		$error=5;	
	}
	// Validamos dni
	if ($dni<=0 or strlen($dni)<6)
	{
		$error=6;
	}
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
}
else
{//este es el camino si carga el profesor o es invitado
	$nom=$_POST['nom'];
	$ape='';
	$_SESSION['dni_invitado']=$dni=$_POST['dni'];
	$nac='';
	$tel='';
	$com='';
	$archivo1='';
	$archivo1_name='';
	$error=0;
	$mai=$_POST['mai'];
	$cla='lokales';
	$cla2='';
	$_SESSION['id_horario_guardados']=$_POST['id_horario'];
	$_SESSION['fecha_clase']=$_POST['fecha_clase'];
	$_SESSION['total_costo']=$_POST['total_costo_modal'];

}
$array_fecha=getdate();
$fecha=strval($array_fecha['year'])."-".strval($array_fecha['mon'])."-".strval($array_fecha['mday']);
?>
<!doctype html>
<html>
<head>
<title>Lokales inscripcion</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
<script src="js/jquery-3.6.0.js"></script>
<script src="js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="js/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<style>
    
    @media only screen and (max-width: 767px) {
  .m_query{width:100%!important;}
}
</style>

</head>
<body>

<div class="container " style="margin: 0 auto;text-align: center;margin-top: 50px;">
    
    <div  style="width:50%;margin:0 auto;box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);padding:20px;margin-top:40px" class="m_query">
<?php
if($error==1) //clave coincide?
{
?>
	<h2><?echo "Las claves ingresadas no coinciden"?></h2><br><br>
	<input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
	<?exit();?>
<?php
	//<input type=button value=Atrás onclick=history.back() style=font-family: Verdana; font-size: 8 pt>
}


if($error==5) //formato de mail incorrecto
{
?>
	<h2><?echo "El e-mail ingresado no es válido"?></h2><br><br>
	<input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
	<?php exit();?>
<?php
	//<input type=button value=Atrás onclick=history.back() style=font-family: Verdana; font-size: 8 pt>
}

if($error==6) //formato de dni incorrecto
{
?>
	<h2><?php echo "El DNI ingresado no es válido"?></h2><br><br>
	<input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
	<?php exit();?>
<?php
	//<input type=button value=Atrás onclick=history.back() style=font-family: Verdana; font-size: 8 pt>
}

if($error==3)
{
	echo "El archivo de imagen no puede tener mas de 100K<br><br>";
	echo "<input type=button value=Atrás onclick=history.back()>";
	exit();
}
if(!isset($_GET['invitado']) and !isset($_GET['venta']))
{
	if($nom=='' or $dni=='' or $tel=='' or $mai=='' or $cla=='')
	{
		echo"ERROR: CAMPO VACIO<br><br>";
		echo "<input type=button value=Atrás onclick=history.back()>";
		//
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
		
	}
}
else
{
	if($nom=='' or $dni=='' or $mai=='')
	{
		echo"ERROR: CAMPO VACIO<br><br>";
		echo "<input type=button value=Atrás onclick=history.back()>";
		$error=4;
	}	
	else
	{
		//Buscamos si el invitado ya existe
		$invitados=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$dni'");			
		if(mysqli_num_rows($invitados)>0)
		{
			$_SESSION['invitacion']=1;
			if(isset($_GET['invitado']))// si viene de invitado lleva a reservar
			{
				header("Location:app_reserva_procesa.php");
			}
			if(isset($_GET['venta']))// si viene de app_profeventas lleva de nuevo con los parametros de la modal
			{
				header("Location:app_profeventas.php?dni_nuevo=".$dni."&nom_nuevo=".$nom);
			}

			exit();
		}
	}
}




if(mysqli_query($mysqli,"INSERT INTO registrados(nombre,apellido,dni,mail,nacimiento,comentario,foto,fecha,celular,clave)VALUES ('$nom','$ape','$dni','$mai','$nac','$com','$ruta','$fecha','$tel','$cla')"))
	{
	    
	   
	    
		//mail("mibanez23@hotmail.com","LOKALES ALTA REGISTRADOS",$nom." ".$ape,"From:".$mai);
		mail($mai,"CONFIRMA REGISTRO LOKALES","Usuario: ".$dni."\nClave: ".$cla."\nGracias por registrarte, haz clic en el link de abajo para confirmar el registro:\nhttps://lokales.com.ar/login_mail_confirmar.php?usuario=".$dni."\nTu mail para recuparar la cuenta es: ".$mai,"From:hola@lokales.com.ar");
		?>

		<h4><?php echo "SE HA REGISTRADO CON EXITO"?></h4><br><br>
		<h6><?php echo "Te mandamos un mail, por favor confirmalo para completar tu registro"?></h6><br><br>
		<a href="login.php" class="btn btn-primary">Comenzar</a>
		<?php
		if(isset($_GET['invitado']))
		{
			$_SESSION['invitacion']=1;
			header("Location:app_reserva_procesa.php");
			exit();
		}
		if(isset($_GET['venta']))
			{
				header("Location:app_profeventas.php?dni_nuevo=".$dni."&nom_nuevo=".$nom);
			}
	}
	else
	{?>
    	<h2><?php echo "EL USUARIO YA EXISTE" //echo mysqli_error($mysqli);?></h2><br>
		<h5><?php echo "Comunicate con Lokales para informar un mail y recuperar tu usuario"//echo mysqli_error($mysqli);?></h5><br>
		<input type=button class="btn btn-primary" value=Atrás onclick=history.back()>
	<?php
	}
?>
</div>
</div>

<!--<p><a class="badge badge-primary" href="login_inscripcion.html">Volver</a></p>-->
</body>
</html>