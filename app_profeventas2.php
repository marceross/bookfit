<?php
session_name("app_admin");
session_start();
	include("conex.php");
	include("local_controla.php"); 	// session_name('app_admin'); session_start(); se agrega si no está el controla	
	$id_usuario=$_SESSION['tipo_usuario_act'];
	
	

	
	$cod_producto = $_POST['cp'];
	
	
	$error=0;
	if(isset($_POST['dni_buscado']))
	{
		if($_POST['dni_buscado']<>'')
		{
			$dni_registrado=$_POST['dni_buscado'];
		}
		else
		{
			$error=1;
		}
	}
	
	

	if($error==0)
	{

		//Obtiene la fecha y hora
		$array_fecha=getdate();
		$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
		//$hora4=date("H:i:s",(time()-3*3600));
		$hora4=date("H:i:s",time());
		
		if(isset($_SESSION['pago'])){
		    
		    $id_forma=$_SESSION['pago'];
		
		    
		}else{
		    
		    $id_forma = 1;
		}
		
		//--------------------------------------------------------------------
		//Creamos el registro en la tabla ventas
		//if(mysqli_query($mysqli,"INSERT INTO ventas (id_usuario, fecha, hora, id_forma, descuento, recargo, efectivo, id_registrados) VALUES ('$id_usuario', '$fecha', '$hora4', '$id_forma', '$descuento', '$recargo', '$efectivo', '$dni_registrado')"))
		
		if(mysqli_query($mysqli,"INSERT INTO ventas (id_usuario, fecha, hora, id_forma, id_registrados) VALUES ('$id_usuario', '$fecha', '$hora4', '$id_forma', '$dni_registrado')"))
		{
			$id_venta=mysqli_insert_id($mysqli);
			
			//--------------------------------------------------------------------
			//Actualizamos venta mensual
			if(isset($_GET['bar']))
			{
				if(!mysqli_query($mysqli,"UPDATE ventas_facturacion SET ventamesbar=ventamesbar + ".$_SESSION['tot_venta']." WHERE mes=".$array_fecha['mon']))
				{
					echo mysqli_error($mysqli);
					exit();
				}
			}
			else
			{
				if(!mysqli_query($mysqli,"UPDATE ventas_facturacion SET ventamesgim=ventamesgim + ".$_SESSION['tot_venta']." WHERE mes=".$array_fecha['mon']))
				{
					echo mysqli_error($mysqli);
					exit();
				}		
			}
			
			
			
			
				

					//--------------------------------------------------------------------
					//Buscamos y calculamos el precio del producto
					$productos=mysqli_query($mysqli,"SELECT * FROM productos, categorias WHERE categorias.cod=cod_cat AND productos.cod='$cod_producto'");
					$producto=mysqli_fetch_array($productos);
					$precio=$producto['costo']*$producto['margen'];
					if(!mysqli_query($mysqli,"INSERT INTO ventas_detalle (id_venta, cod_producto, cantidad, precio) VALUES ('$id_venta', '$cod_producto', '$id_usuario', '$precio')"))
					{
						echo mysqli_error($mysqli);
					}


					


					//--------------------------------------------------------------------
					//Actualizo stock
					mysqli_query($mysqli,"UPDATE productos SET stock=stock-'$id_usuario' WHERE cod='$cod_producto'");


					//--------------------------------------------------------------------
					//Actualizo fecha de vencimiento y credito
					$duracion=$producto['duracion'];// paquete		
					
					//Busco la fecha de vencimiento actual
					$registrados=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$dni_registrado'");
					$registrado=mysqli_fetch_array($registrados);
					$credito_viejo=$registrado['credito'];
					$credito_compra=$producto['cantp'];

					//Verifico si la fecha de vencimiento actual esta vencida
					//echo "Vto: ".strtotime($registrado['vencimiento'])." Hoy: ".strtotime($fecha);
					//exit();
					if(strtotime($registrado['vencimiento'])<strtotime($fecha))
					{					
						//si esta vencida se suman solo los dias que compra
						$credito_nuevo=($credito_viejo-$credito_viejo+$credito_compra);
						$vencimiento=date("Y-m-d",strtotime($fecha."+ ".$duracion." days"));
					}
					else//si no esta vencida se suman a la fecha vieja los dias que se agregan
					{
						$credito_nuevo=($credito_viejo+$credito_compra);
						$vencimiento=date("Y-m-d",strtotime($registrado['vencimiento']."+ ".$duracion." days")); 							
					}
					
					if(!mysqli_query($mysqli,"UPDATE registrados SET vencimiento='$vencimiento', credito='$credito_nuevo' WHERE dni='$dni_registrado'"))
					{
					    
						echo mysqli_error($mysqli);
						exit();
					}
                    
                    
                        
					    //if($registrado['reserva_auto'] == 1){
					    
					    if($producto['cod_cat'] == 8){
					        
					        mysqli_query($mysqli,"UPDATE registrados SET reserva_auto=1 WHERE dni='$dni_registrado'");
					        
					        //mysqli_query($mysqli,"UPDATE registrados SET reserva_auto=1 WHERE dni='$dni_registrado'");
					        
					        $eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='$dni_registrado'");
					        
					        
					        while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    // Check if the user made reservations last week
    $lastWeek = strtotime('-1 week');
    $reservationsLastWeekQuery = mysqli_query($mysqli, "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");

    if (mysqli_num_rows($reservationsLastWeekQuery) > 0) {
        // User made reservations last week, automate reservations for the same time slots

        // Get the time slots reserved by the user last week (assuming it's the same time slots for demonstration)
        $timeSlotsLastWeekQuery = mysqli_query($mysqli, "SELECT DISTINCT ahr.actividad_horarios_id_horario, ahr.fecha, ah.costo
            FROM actividad_reservas ahr
            JOIN actividad_horarios ah ON ahr.actividad_horarios_id_horario = ah.id_horario
            WHERE ahr.registrados_dni='" . $user['dni'] . "' AND ahr.fecha >= '" . date('Y-m-d', $lastWeek) . "'");
        
        while ($row = mysqli_fetch_assoc($timeSlotsLastWeekQuery)) {
            $id_horario = $row['actividad_horarios_id_horario'];
            $fechaLastWeek = new DateTime($row['fecha']);
            $fechaLastWeek->modify('+1 week');
            $fecha = $fechaLastWeek->format('Y-m-d');
            $costo = $row['costo'];

            // Check if the id_horario and fecha combination exists in actividad_horarios_susp
            $isSuspendedQuery = mysqli_query($mysqli, "SELECT * FROM actividad_horarios_susp WHERE actividad_horarios_id_horario2='" . $id_horario . "' AND fecha='" . $fecha . "'");

            if (mysqli_num_rows($isSuspendedQuery) == 0) {
                // Check if the user has enough credits for the reservation
                if ($user['credito'] >= $costo) {
                    
                    $if_check = "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha='" . $fecha . "' AND actividad_horarios_id_horario='".$id_horario."'";
                    //echo $if_check;
                    $if_check = mysqli_query($mysqli,$if_check);
                    
                    if (mysqli_num_rows($if_check) == 0) {
                    
                   
                    
                    // Insert reservation record
                    mysqli_query($mysqli, "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('" . $user['dni'] . "', '" . $fecha . "', '" . $id_horario . "')");

                    // Deduct the cost from the user's credits
                    mysqli_query($mysqli, "UPDATE registrados SET credito = credito - " . $costo . " WHERE dni = '" . $user['dni'] . "'");
                    
                    

                    //echo "Reservation made for User: " . $user['nombre'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
                    }else{
                        //echo 'Already Reservation maded ';
                    }
                    
                } else {
                    //echo "Insufficient credits for User: " . $user['nombre'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
                }
            } else {
                //echo "Reservation not made for User: " . $user['nombre'] . ", DNI: " . $user['dni'] . " for the same time slot as last week due to suspension<br>";
            }
        }
    }
}
					        
					        
					    }
					    
		//}

					//Borro la tabla temporal
					//mysqli_query($mysqli,"DELETE FROM ventas_temporal");
					//Volvemos a inicio y destruimos la variable autentificado
					
					$_SESSION['pago']=1;				
					header("Location:app_profe.php");
					
				}
			
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
</head>
<body>
	<?php
		if($error==1)
		{
			echo  "El campo DNI es obligatorio";
		}
	?>
</body>
</html>

<?php
// ERA local_procesa_ventas2.php
?>
