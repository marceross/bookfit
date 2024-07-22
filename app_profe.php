<?php
session_name("app_admin");
session_start();
	include("conex.php");	
	include("local_controla.php");
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	$id_usuario=$_SESSION['usuario_act'];
	//$mensajes=mysqli_query($mysqli,"SELECT * FROM mensajes_internos, mensajes_internos_destinatarios WHERE confirmado<>'S' AND mensajes_internos.id_mensaje=mensajes_internos_destinatarios.id_mensaje AND mensajes_internos_destinatarios.id_usuario='$id_usuario'");
	//$cant_mensajes=mysqli_num_rows($mensajes);
	$categorias=mysqli_query($mysqli,"SELECT * FROM categorias ORDER BY nombre");
	//$formas=mysqli_query($mysqli,"SELECT * FROM ventas_forma_pago ORDER BY nombre");
	//$consultas=mysqli_query($mysqli,"SELECT * FROM consultas WHERE respuesta=''");
	//$cant_consultas=mysqli_num_rows($consultas);
	//$contactos=mysqli_query($mysqli,"SELECT * FROM contacto WHERE respuesta=''");
	//$cant_contactos=mysqli_num_rows($contactos);

	function calcular_precio_s_dto ($precio_base, $puntos_paquete)
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


	function calcular_dto ($precio_paquete, $puntos_paquete)
	{
		$descuento=$precio_paquete/$puntos_paquete;
		return round($descuento);
	}

	//echo calcular_dto(10800,36);
	//exit();

	//echo calcular_precio_s_dto(500,12);
	//exit();

	//if(!isset($_GET['id_cat']))
	//{
		$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND activo='S' ORDER BY productos.nombre, marcas.nombre");
	//}
	/*else
	{
		$productos=mysqli_query("SELECT productos.cod as cp, productos.nombre as np, costo, margen FROM productos, categorias WHERE cod_cat=categorias.cod AND cod_cat=".$_GET['id_cat']." ORDER BY productos.nombre",$mysqli);	
	}*/
	//Vemos cuando reg hay en la tabla temporal
	$temporales=mysqli_query($mysqli,"SELECT * FROM ventas_temporal WHERE id_usuario='$id_usuario'");
	$cant_registros=mysqli_num_rows($temporales);
	$_SESSION['cant_items']=$cant_registros;

	//Cantidad de reservas
	$reservas=mysqli_query($mysqli,"SELECT COUNT(*) as cant_reservas
	FROM actividad_reservas
	WHERE fecha='$fecha'");
	$reserva=mysqli_fetch_array($reservas);

	//Cantidad de asistencias
	$asistencias=mysqli_query($mysqli,"SELECT COUNT(*) as cant_asistencias FROM actividad_reservas
	WHERE fecha='$fecha' AND asiste=1");
	$asistencia=mysqli_fetch_array($asistencias);
	
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

<script>


	function buscar_cliente(dni)
	{	
		//alert("DNI: "+dni);
		$.ajax({			
			url: 'buscar_cliente.php',
			data: 'dni_cli=' + dni,
			success: function(resp) {
				$('#cliente_encontrado').html(resp)
			}
		});
	}

	function crear_tabla(tipo)
	{	
		//alert("DNI: "+dni);
		$.ajax({			
			url: 'crear_tabla.php',
			data: 'tipo=' + tipo,
			success: function(resp) {					
                $('#tabla_datos_clientes').html(resp);
			}
		});
	}
</script>

</head>

<body>
<!--
<table width="100%" border="0" align="center" bordercolor="#999999">
    <tr> 
      <td width="62%"><div></div></td>
      <td width="38%"><div>mensajes <a href="ver_mensajes.php">ver</a> 
          <?// echo "(".$cant_mensajes.")";?></div></td>
    </tr>
  </table>-->


<!--<p>mensajes <a href="app_mensajes_profe.php">ver</a><?// echo "(".$cant_mensajes.")";?></p>-->
<div class="container">
<h2><a href="asiste.php" class="btn btn-info btn-sm">TOMAR ASISTENCIA</a></h2><br>
<span class="badge badge-pill badge-light">ver cliente</span>
<form action="app_vercliente.php" method="post" enctype="multipart/form-data">
<div class="form-row">
	<div class="form-group col-4">
		<input type="text" name="dni_buscado" id="dni_buscado" onkeyup="buscar_cliente(this.value)" class="form-control" placeholder="dni" required>
	</div>
	<div class="form-group col-6">
		<div id="cliente_encontrado"><input type="text" name="nombre_apellido" id="nombre_apellido" value="" class="form-control" readonly></div>
	</div>
</div>
<input value="Buscar" class="btn btn-primary btn-sm" type="submit"/>
</form><br>

<h2><a href="app_actividades2.php" class="btn btn-danger btn-sm">SUSPENDER HORARIO</a></h2><br>


<div class="container">

<?php
  $paquetes=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm, cantp, duracion FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND cod_cat=11 AND activo='S' ORDER BY productos.cantp DESC");
  //ORDER BY productos.cantp, marcas.nombre" CAMBÍE--------------------------------
  
  //precio base, valor de 1 punto
  $precios_puntos=mysqli_query($mysqli, "SELECT * FROM productos WHERE cod=96");
  $precio_punto=mysqli_fetch_array($precios_puntos);
  //$precio_punto
  //define($precio_punto,100)----------------------------------------------------------------
?>

<div class="row">


<?php
  while($paquete=mysqli_fetch_array($paquetes))

  {
	$porcentaje_descuento=calcular_porcentaje_dto($paquete['costo'], $paquete['cantp'], $precio_punto['costo']);
	if($porcentaje_descuento<=15)
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
        <h5 class="card-header bg-info text-light">Puntos <span class="badge badge-pill badge-light"><?php echo $paquete['cantp'];?></span></h5>
        <div class="card-body">

			<!-- % off 1-((precio paquete/puntos paquete)/precio base))*100---------------------------------------------------------->
			<h5 class="card-title"><?php echo "$ ".$paquete['costo']." ";?><span class="badge <?php echo $clase_descuento;?>"><? echo $porcentaje_descuento."% off";?></span></h5>

			<!-- precio antes precio base * puntos paquete---------------------------------------------------------->
			<h6 class="card-title">Sin descuento <span class="outer"><span class="inner"><?php echo "$ ".calcular_precio_s_dto ($precio_punto['costo'], $paquete['cantp'])."  .";?></span></span></h6>

			<!-- el punto te queda en... $... precio paquete/ puntos paquete ---------------------------------------------------------->
			<span class="badge badge-info"><?php echo "El punto te queda en $ ".calcular_dto($paquete['costo'], $paquete['cantp']);?></span>

			<p class="card-text"><?php echo "Duración ".$paquete['duracion']." días";?></p>
			<br><br>
			<a href="app_profeventas.php?cp=<?php echo $paquete['cp'];?>" class="btn btn-success">Cargar</a>
        </div>
    </div>

<?php
  }
?>


</div>
</div>

<!--<h6><a href="app_profeventames.php" class="badge badge-info">Vender otros</a></h6><br>-->


<div class="container">
<h5>Reservas y asistencias de hoy</h5>
<!--<button type="button" class="btn btn-info btn-sm">Tomar asistencia</button><br><br>-->
    <table class="table">
	<!--<caption>Reservas y asistencias de hoy</caption>-->
    <thead>
        <tr>
        <th scope="col">Reservas</th>
        <th scope="col">Asistencias</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td><?php echo $reserva['cant_reservas'];?></td>
        <td><?php echo $asistencia['cant_asistencias'];?></td>
        </tr>
    </tbody>
    </table>
</div>

<div class="container">
    <h5>Reservas y asistencias del mes por cliente</h5>
    <button type="button" class="btn btn-info btn-sm" onClick="crear_tabla('reservas')">Reservas</button>
    <button type="button" class="btn btn-info btn-sm" onClick="crear_tabla('asistencias')">Asistencias</button>
    <!--<h5><a href="vencimientos.php" class="badge badge-danger">Vencimientos</a></h5>
    <h5><a href="fechas.php" class="badge badge-primary">Cumples</a></h5>-->
    <!--<button type="button" class="btn btn-info btn-sm">Invitaciones</button>-->
    <div id="tabla_datos_clientes">
    </div>
</div>
<br><br>
<h6><a href="local_kill_session.php" class="badge badge-danger">cerrar sesión</a></h6><br>

</body>
</html>

<?php
// funciones LLAMADA AJAX se comporta distinto en compu que en celu
// onchange
// onkeyup, habría que agregar una condición si está vació para que no aparezca la ventana
// oninput, creo que es el mejor para este caso

// ERA local_ventas.php
?>
