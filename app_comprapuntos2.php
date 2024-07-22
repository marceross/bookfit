<?
	include("conex.php");
	//Variable que nos dice si hay que cargar la venta o no
	// SDK de Mercado Pago
	require __DIR__ .  '/vendor/autoload.php';
	include("local_controla_app.php"); // session_name(''); session_start(); se agrega si no está el controla
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);	
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
</head>

<body>
<?		
    //Limpio la tabla temporal	
    mysqli_query($mysqli,"DELETE FROM ventas_temporal WHERE id_usuario='$id_usuario'");   
    
    //$_SESSION['cod_producto']= quité esta primera parte-----------------------------------------------------------
    $_SESSION['cod_producto']=$cod_producto=$_GET['cp'];
    $_SESSION['cantidad']=$cantidad=1;

    mysqli_query($mysqli,"INSERT INTO ventas_temporal (cod_producto, cant, id_usuario) VALUES ('$cod_producto','$cantidad','$id_usuario')");		
		
    //Mostrar resumen
    if(!$productos_temporales=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, descripcion, margen, marcas.nombre as nm, cant, productos.cod_cat as cate, cantp FROM productos, categorias, marcas, ventas_temporal WHERE productos.cod=ventas_temporal.cod_producto AND productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod AND id_usuario='$id_usuario' ORDER BY productos.nombre"))
    {
        echo mysqli_error($mysqli);
        exit();
    }	
?>
<div class="container">
    <table class="table">
    <tr>
        <td>Producto</td>
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
        <tr>
            <td>    
    <?    
                echo $producto_temporal['np']." ".$producto_temporal['nm']." ".$producto_temporal['descripcion']." ($".round ($producto_temporal['costo']*$producto_temporal['margen']).")<br>";
    ?>
            </td>			
            <td><? echo $producto_temporal['cantp'];?></td>
            <td><? echo "$".round ($producto_temporal['costo']*$producto_temporal['margen']);?></td>
            <td><? echo "$".round ($producto_temporal['costo']*$producto_temporal['margen']*$producto_temporal['cant']);?></td>    
        </tr>
    <?        
            }
            $total_definitivo=$total;
            $_SESSION['tot_venta']=$total_definitivo;
    ?>
        <tr>
            <td>TOTAL</td>
            <td></td>
            <td></td>
            <td><? echo "$".round ($total_definitivo);?></td>
        </tr>
    </table>

    <?php			                                                             
    // Agrega credenciales
    MercadoPago\SDK::setAccessToken('APP_USR-7459218136972856-071713-58fe64fed0c17a85a07c6e3e442d0fc7-79024106');

    // Crea un objeto de preferencia
    $preference = new MercadoPago\Preference();

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = 'Compra en Lokales';
    $item->quantity = 1;
    $item->unit_price = $_SESSION['tot_venta'];
    $preference->items = array($item);
    $preference->external_reference=$nro_venta;								
    $preference->save(); 	//ACÁ QUEDAMOS//////////////////////////////////// respuesta.php
    ?>

    <div id="boton_pago">
        <form action="/respuesta.php" method="POST">
            <script
            src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
            data-preference-id="<?php echo $preference->id; ?>">
            </script>
        </form> 
    </div>
</div>

</body>
</html>