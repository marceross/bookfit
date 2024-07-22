<?
	include("conex.php");	
	include("local_controla_app.php"); // session_name('app_admin'); session_start(); se agrega si no está el controla
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	$id_usuario=$_SESSION['usuario_act'];
	//$mensajes=mysqli_query($mysqli,"SELECT * FROM mensajes_internos, mensajes_internos_destinatarios WHERE confirmado<>'S' AND mensajes_internos.id_mensaje=mensajes_internos_destinatarios.id_mensaje AND mensajes_internos_destinatarios.id_usuario='$id_usuario'");
	//$cant_mensajes=mysqli_num_rows($mensajes);
	//$formas=mysqli_query($mysqli,"SELECT * FROM ventas_forma_pago ORDER BY nombre");

	function calcular_precio_s_dto($precio_base, $puntos_paquete)
	{
		$precio_s_dto=$precio_base*$puntos_paquete;
		return $precio_s_dto;
	}

	function calcular_porcentaje_dto($precio_paquete, $puntos_paquete, $precio_base)
	{
		//echo "Precio Paquete: ".$precio_paquete."<br>";
		//echo "Puntos Paquete: ".$puntos_paquete."<br>";
		//echo "PBase: ".$precio_base."<br>";
		//exit();

		//es una variable definida para la funcion, a diferencia de la variable global
		$porcentaje_descuento=(1-(($precio_paquete/$puntos_paquete)/$precio_base))*100;
		return round ($porcentaje_descuento);
	}

	//echo calcular_porcentage_dto(4800,12,500);
	//exit();


	function calcular_dto($precio_paquete, $puntos_paquete)
	{
		$descuento=$precio_paquete/$puntos_paquete;
		return round ($descuento);
	}

	//echo calcular_dto(10800,36);
	//exit();

	//echo calcular_precio_s_dto(500,12);
	//exit();

	//if(!isset($_GET['id_cat']))
	//{
		$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod ORDER BY productos.nombre, marcas.nombre");
	//}
	/*else
	{
		$productos=mysqli_query("SELECT productos.cod as cp, productos.nombre as np, costo, margen FROM productos, categorias WHERE cod_cat=categorias.cod AND cod_cat=".$_GET['id_cat']." ORDER BY productos.nombre",$mysqli);	
	}*/

?>
<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="js/jquery-3.6.0.js"></script>

</head>

<body>

<div class="container">
	<?
	$paquetes=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm, cantp, duracion FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND cod_cat=11 AND activo='S' ORDER BY productos.nombre, marcas.nombre");
	
	//precio base, valor de 1 punto---------------------------------------------------------->
	$precios_puntos=mysqli_query($mysqli, "SELECT * FROM productos WHERE cod=96");
	$precio_punto=mysqli_fetch_array($precios_puntos);
	?>

	<div class="row justify-content-center">
		<div class="col-10 col-sm-9 col-md-8 col-lg-7 text-center">
			<?
			while($paquete=mysqli_fetch_array($paquetes))
			{
				$porcentaje_descuento=calcular_porcentaje_dto($paquete['costo'], $paquete['cantp'], $precio_punto['costo']);
				if($porcentaje_descuento<=20)
				{
				$clase_descuento="badge-success";
				}
				else
				{
				if($porcentaje_descuento<=40)
				{
					$clase_descuento="badge-warning";
				}
				else
				{
					$clase_descuento="badge-danger";
				}
				}
			?>
				<div class="card text-dark bg-light mb-3 caja_precio">
					<h5 class="card-header bg-info text-light">Puntos <span class="badge badge-pill badge-light"><? echo $paquete['cantp'];?></span></h5>
					<div class="card-body">

						<!-- precio antes precio base * puntos paquete---------------------------------------------------------->
						<h6 class="card-title">Sin descuento <span class="outer"><span class="inner"><? echo "$ ".calcular_precio_s_dto($precio_punto['costo'], $paquete['cantp'])."  .";?></span></span></h6>

						<!-- % off 1-((precio paquete/puntos paquete)/precio base))*100---------------------------------------------------------->
						<h5 class="card-title"><? echo "$ ".$paquete['costo']." ";?><span class="badge <? echo $clase_descuento;?>"><? echo $porcentaje_descuento."% off";?></span></h5>

						<!-- el punto te queda en... $... precio paquete/ puntos paquete ---------------------------------------------------------->
						<span class="badge badge-info"><? echo "El punto te queda en $ ".calcular_dto($paquete['costo'], $paquete['cantp']);?></span>

						<p class="card-text"><?echo "Duración ".$paquete['duracion']." días";?></p>
						<a href="app_comprapuntos2.php?cp=<? echo $paquete['cp'];?>" class="btn btn-primary">Comprar</a>
					</div>
				</div>
			<?
			}
			?>
		</div>
	</div>
</div>

<?
// forma de pago sólo en EFECTIVO en este archivo de carga de ventas por parte de los profesores, ver archivo local_ventas para versiones anteriores cambiar <> por =???
$_SESSION['pago']=2;
?>   
  
</body>
</html>