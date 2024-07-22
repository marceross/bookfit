<?php
	include("conex.php");	

	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	

	function calcular_precio_s_dto($precio_base, $puntos_paquete)
	{
		$precio_s_dto=$precio_base*$puntos_paquete;
		return $precio_s_dto;
	}

	function calcular_porcentaje_dto($precio_paquete, $puntos_paquete, $precio_base)
	{
		
		$porcentaje_descuento=(1-(($precio_paquete/$puntos_paquete)/$precio_base))*100;
		return round ($porcentaje_descuento);
	}




	function calcular_dto($precio_paquete, $puntos_paquete)
	{
		$descuento=$precio_paquete/$puntos_paquete;
		return round ($descuento);
	}

	
		$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod ORDER BY productos.nombre, marcas.nombre");
	

?>
<!DOCTYPE html>
<html lang="es">

<head>
<title>Lokales</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<LINK href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
<link href="estilo.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="js/bootstrap.min.css"  >
<script src="js/jquery-3.6.0.js"></script>

 <script src="js/jquery.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    
    
    <header>
      <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
      <a class="navbar-brand" href="app_actividades.php"><img src="logo.gif" class="img-fluid" alt="Lokales Training spot logo image" width="200"></a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
        <?php
        if(!isset($_SESSION['usuario_act']))
        {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login_inscripcion.php">Registrarte</a>
          </li>
        <?php
        }
        else
        {
        ?>
          <li class="nav-item">
          <h6><a class="nav-link"><span class="badge badge-pill badge-light"><?php echo $dato_usuario['nombre'];?></span><span class="badge badge-pill badge-dark"><?php echo $credito_actual;?></span> puntos</a></h6>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
          </li>
        <?php
        }
        ?>
        </ul>
       
      </div>
    </nav>
</header>

<div class="container" style="margin-top:60px;">
<table role="presentation" class="main">
    <tr>
    <td class="wrapper">
        <table role="presentation">
        <tr>
            <td>
            <h4>Como funcionan los puntos?</h4>
            <p>El <span class="badge-secondary">valor de cada actividad</span> lo vés al lado del horario para cuando quieras reservar una clase o sesión libre<br>
			Después de registrarte <span class="badge-secondary">comprá los puntos en Lokales</span><br>
			Tenés precios y vencimientos distintos de acuerdo a lo que necesites</p>
            <h5>Tip compra <span class="span__check">&#10003;</span></h5>
            <p>Con la compra de cualquier pack se suman los puntos viejos a la compra nueva, y no perdés los puntos que tenías.</p>
            <h5>Tip cancelar reserva <span class="span__check">&#10003;</span></h5>
            <p>Hasta una hora antes de empezar la clase podés cancelar tu reserva y recuperar los puntos.</p>
</td>
</tr>
</table>
</div>


<div class="container">
	<?php
	$paquetes=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm, cantp, duracion FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND cod_cat=11 AND activo='S' ORDER BY productos.cantp DESC");
	
	//precio base, valor de 1 punto---------------------------------------------------------->
	$precios_puntos=mysqli_query($mysqli, "SELECT * FROM productos WHERE cod=96");
	$precio_punto=mysqli_fetch_array($precios_puntos);
	
	?>

	<div class="row justify-content-center">
	
			<?php
			
			while($paquete=mysqli_fetch_array($paquetes)){
			    
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
				<div class="col-sm-6 text-center">
				<div class="card text-dark bg-light mb-3 caja_precio">
					<h5 class="card-header bg-info text-light">Puntos <span class="badge badge-pill badge-light"><?php echo $paquete['cantp'];?></span></h5>
					<div class="card-body">

						
						<h5 class="card-title"><?php echo "$ ".$paquete['costo']." ";?><span class="badge <?php echo $clase_descuento;?>"><?php echo $porcentaje_descuento."% off";?></span></h5>

						
						<span class="badge badge-info"><?php echo "El punto te queda en $ ".calcular_dto($paquete['costo'], $paquete['cantp']);?></span>

						<p class="card-text"><?php echo "Duración ".$paquete['duracion']." días";?></p>
						
					</div>
				</div>
				</div>
			<?php
			}
			
			?>
            <h6><a href="app_actividades.php" class="badge badge-success">volver</a></h6><br>
		
	</div>
</div>

<?php

$_SESSION['pago']=2;
?>   
  
</body>
</html>