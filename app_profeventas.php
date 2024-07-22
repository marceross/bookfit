<?
	include("conex.php");
	//Variable que nos dice si hay que cargar la venta o no
	//$cargar_venta="no";	
	include("local_controla.php"); // session_name('app_admin'); se agrega si no está el controla
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$ventas_fact=mysqli_query($mysqli,"SELECT * FROM ventas_facturacion WHERE mes=".$array_fecha['mon']);
	$venta_fact=mysqli_fetch_array($ventas_fact);
	if(!$reg=mysqli_query($mysqli,"SELECT dni, registrados.nombre as nomreg, apellido, actividad.nombre as nomact, profesor.nombre as nomprofe FROM registrados, actividad, profesor WHERE actividad=id_actividad AND profesor=id_profesor"))
	{
		echo mysqli_error($mysqli);
		exit();
	}
	$id_usuario=$_SESSION['usuario_act'];
	
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>

function buscar_cliente(dni)
	{	
		//alert("DNI: "+dni);
		$.ajax({			
			url: 'buscar_cliente.php',
			data: 'dni_cli=' + dni,
			success: function(resp) {				
				if(resp!='no encontrado')
				{					
					$('#cliente_encontrado').html(resp);
				
				}
				else
				{					
					//paso dato de dni_buscado a dni de la ventana modal
					$("#dni").val($("#dni_buscado").val());
					//pongo en cero dni_buscado cuando cierro ventana modal
					$("#dni_buscado").val('');
					$("#ventana_invitacion").modal("show");					
					$('#nombre_apellido').val('');
					
				}
			}
		});
	}

</script>
</head>

<body>

 <!---------------VENTANA MODAL--------------------------------->
 <div class="modal fade" id="ventana_invitacion">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  
                </div>
              <div class="modal-body">                  
                <form action="login_inscripcion_procesa.php?venta=1" method="post" enctype="multipart/form-data" class="form-group">
                <h4 class="modal-title">Nuevo cliente</h4>
                  <label>DNI</label>
                
                  <input type="text" name="dni" id="dni" class="form-control" value="0" required>

                  <label>Nombre</label>                

                  <input type="nombre" name="nom" id="nom" value="" class="form-control" required>
                  
                  <label>Mail</label>
                  <input type="email" name="mai" id="mai" class="form-control" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>  
                  
                  <div class="modal-footer">
                  

                  <input type="submit" value="Enviar" class="btn btn-primary" id="boton_enviar">
                  </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!------------------------------------------------>

<?
//echo "Cant reg2: ".$_SESSION['cant_items'];
//Boton agregar
	//if(isset($_POST['agregar']) or isset($_POST['terminar']))
	//{
		//$mostrar_registrados=0;
		
		//Limpio la tabla temporal		
		mysqli_query($mysqli,"DELETE FROM ventas_temporal WHERE id_usuario='$id_usuario'");
		//for($i=0;$i<$_SESSION['cant_items'];$i++)
		//{

			if(isset($_GET['cp']))
			{
				$_SESSION['cod_producto']=$cod_producto=$_GET['cp'];
				$_SESSION['cantidad']=$cantidad=1;		
			}
			else
			{
				$cod_producto=$_SESSION['cod_producto'];
				$cantidad=$_SESSION['cantidad'];		
			}

			$productos_categorias=mysqli_query($mysqli,"SELECT * FROM productos WHERE cod=".$_SESSION['cod_producto']);
			$producto_categoria=mysqli_fetch_array($productos_categorias);						
			
			//if($cantidad<>"")
			//{
				//$cargar_venta="si";
				mysqli_query($mysqli,"INSERT INTO ventas_temporal (cod_producto, cant, id_usuario) VALUES ('$cod_producto','$cantidad','$id_usuario')");
			//}
			// categorias para editar... son las que van a mostrar el nombre del socio antes de facturar y van a mostrar el mensaje de facturacion
			//if(($producto_categoria['cod_cat']==5 or $producto_categoria['cod_cat']==8 or $producto_categoria['cod_cat']==11) and $cantidad<>"")
			//{
			//	$mostrar_registrados=1;
			//}
		//}
		//if(isset($_POST['agregar']))
		//{
		//	header("Location:app_profe.php");
		//}
	//}	
	//if(isset($_GET['dni_nuevo']))
	//{	
		
		
		//Mostrar resumen
		if(!$productos_temporales=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, descripcion, margen, marcas.nombre as nm, cant, productos.cod_cat as cate FROM productos, categorias, marcas, ventas_temporal WHERE productos.cod=ventas_temporal.cod_producto AND productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND id_usuario='$id_usuario' ORDER BY productos.nombre"))
		{
			echo mysqli_error($mysqli);
			exit();
		}	
		

?>
<table class="table">
<tr><td>Producto</td>
<td>Cantidad</td>
<td>Precio</td>
<td>Total</td>
</tr>
<?
		$total=0;
		while($producto_temporal=mysqli_fetch_array($productos_temporales))
		{
			$total=$total+$producto_temporal['costo']*$producto_temporal['margen']*$producto_temporal['cant'];		
?>		
	<tr><td>    
<?    
			echo $producto_temporal['np']." ".$producto_temporal['nm']." ".$producto_temporal['descripcion']." ($".round ($producto_temporal['costo']*$producto_temporal['margen']).")<br>";
?>
		</td>			
        <td><? echo $producto_temporal['cant'];?></td>
        <td><? echo "$".round ($producto_temporal['costo']*$producto_temporal['margen']);?></td>
        <td><? echo "$".round ($producto_temporal['costo']*$producto_temporal['margen']*$producto_temporal['cant']);?></td>
        </tr>
<?        
		}
		$total_definitivo=$total;
		$_SESSION['tot_venta']=$total_definitivo;
?>
	<tr><td>TOTAL</td><td></td><td></td><td><? echo "$".round ($total_definitivo);?></td></tr>
</table>		

<?
//if($mostrar_registrados==1 or isset($_GET['dni_nuevo']))
//{
	//ventas del mes menor a ventas proyectadas
	if($venta_fact['ventamesgim']<$venta_fact['ventapgim'])
	{
		echo "FACTURAR";	
	}

?>
	<div class="col-xs-6 col-md-6">
		<form action="app_profeventas2.php" method="post" enctype="multipart/form-data" class="form-group">
		
		<label>Ingresa el DNI del cliente</label>
		<div class="form-row">
			<div class="form-group col-xs-4">
				<?
				if(isset($_GET['dni_nuevo']))
				{
					$dni_nuevo=$_GET['dni_nuevo'];
					$nom_nuevo=$_GET['nom_nuevo'];					
					
				}
				else
				{
					$dni_nuevo='';
					$nom_nuevo='';					
				}
			
				?>
				<input type="text" name="dni_buscado" id="dni_buscado" class="form-control" required value="<? echo $dni_nuevo;?>">
			</div>
			<div class="form-group col-xs-3">
				<button type="button" onclick="buscar_cliente(dni_buscado.value)" class="badge badge-info form-control">Buscar</button>
			</div>
		</div><br>
		
		<div id="cliente_encontrado">
			<input type="text" name="nombre_apellido" id="nombre_apellido" value="<? echo $nom_nuevo;?>" class="form-control" readonly>
		</div>
		<br>

		<input value="Confirmar" class="btn btn-primary" type="submit"/>
		</form>
	</div>
<?
//}
//else
//{
	
	//ventas del mes menor a ventas proyectadas
//	if($venta_fact['ventamesbar']<$venta_fact['ventapbar'])
//	{
//		echo "FACTURAR";	
//	}
//}
?>

<?
	//}	
?>

	<a href="app_profe.php?v=1#boton_siguiente" class="badge badge-info">Volver</a>
	
    <?/*
	if($mostrar_registrados==0 and !isset($_GET['dni_nuevo']))
	{
	?>
    <a href="app_profeventas2.php?bar=1" class="btn btn-primary">Confirmar</a>
    <?
	}*/
	?>

</body>
</html>

<?
// ERA local_procesa_ventas.php
?>
