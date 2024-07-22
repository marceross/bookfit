<?
include("local_controla_app.php");
?>


<!DOCTYPE html>
<html lang="es">

<head>  
<title>Lokales compra puntos</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<div class="containter">
	<div class="row">
		<div class="col-6">
		<?
			//Analiza la respuesta obtenida de mercadopago y muestra un mensaje
			if($_POST['payment_status_detail']=='pending_waiting_payment')
			{
				echo "<p>!Esperamos tu pago! Una vez acreditado procesaremos tu pedido.</p>";
			}
	
			if($_POST['payment_status_detail']=='accredited')
			{
				echo "<p>Se acreditó tu pago. En tu resumen verás el cargo de $".number_format( $_SESSION['total_a_pagar'],2,",","."). " como Compra en Lokales.</p><p>Tus puntos están listos para usar!.</p>";
			}
	
			if($_POST['payment_status_detail']=='pending_contingency')
			{
				echo "<p>Estamos procesando tu pago. No te preocupes, en menos de 2 días hábiles te avisaremos por e-mail si se acreditó.</p>";
			}

			if($_POST['payment_status_detail']=='pending_review_manual')
			{
				echo "<p>Estamos procesando tu pago. No te preocupes, en menos de 2 días hábiles te avisaremos por e-mail si se acreditó o si necesitamos más información..</p>";
			}
	
			if($_POST['payment_status_detail']=='pending_review_manual')//no veo la diferencia con el anterior
			{
				echo "<p>Estamos procesando tu pago. No te preocupes, en menos de 2 días hábiles te avisaremos por e-mail si se acreditó o si necesitamos más información.</p>";
			}				
			if($_POST['payment_status_detail']=='cc_rejected_bad_filled_card_number')
			{
				echo "<p>Pago rechazado: Revisa el número de tarjeta.</p>";
			}						
			if($_POST['payment_status_detail']=='cc_rejected_bad_filled_date')
			{
				echo "<p>Pago rechazado: Revisa la fecha de vencimiento.</p>";
			}
	
			if($_POST['payment_status_detail']=='cc_rejected_bad_filled_other')
			{
				echo "<p>Pago rechazado: Revisa los datos.</p>";
			}
	
			if($_POST['payment_status_detail']=='cc_rejected_bad_filled_security_code')
			{
				echo "<p>Pago rechazado: Revisa el código de seguridad de la tarjeta.</p>";
			}
	
			if($_POST['payment_status_detail']=='cc_rejected_blacklist')
			{
				echo "<p>Pago rechazado: No pudimos procesar tu pago.</p>";
			}						
			if($_POST['payment_status_detail']=='cc_rejected_call_for_authorize')
			{
				echo "<p>Debes autorizar ante tu entidad bancaria el pago de ".number_format( $_SESSION['total_a_pagar'],2,",",".").".</p>";
			}				
			if($_POST['payment_status_detail']=='cc_rejected_card_disabled')
			{
				echo "<p>Llama a tu entidad bancaria para activar tu tarjeta o usa otro medio de pago.</p>";
			}				
			if($_POST['payment_status_detail']=='cc_rejected_card_disabled')
			{
				echo "<p>Llama a tu entidad bancaria para activar tu tarjeta o usa otro medio de pago. El teléfono está al dorso de tu tarjeta.</p>";
			}	
	
			if($_POST['payment_status_detail']=='cc_rejected_card_error')
			{
				echo "<p>Error: No pudimos procesar tu pago.</p>";
			}				
			if($_POST['payment_status_detail']=='cc_rejected_duplicated_payment')// eso es así en el mismo día????
			{
				echo "<p>Error: Ya hiciste un pago por ese valor. Si necesitas volver a pagar usa otra tarjeta u otro medio de pago.</p>";
			}	
	
			if($_POST['payment_status_detail']=='cc_rejected_high_risk')
			{
				echo "<p>Error: Tu pago fue rechazado. Elige otro de los medios de pago, te recomendamos con medios en efectivo.</p>";
			}				
			if($_POST['payment_status_detail']=='cc_rejected_insufficient_amount')
			{
				echo "<p>Pago rechazado: Tu medio de pago no tiene fondos suficientes.</p>";
			}				
			if($_POST['payment_status_detail']=='cc_rejected_invalid_installments')
			{
				echo "<p>Pago rechazado: tu medio de pago no procesa pagos en la cantidad de cuotas que elegiste.</p>";
			}	
	
			if($_POST['payment_status_detail']=='cc_rejected_max_attempts')
			{
				echo "<p>Pago rechazado: Llegaste al límite de intentos permitidos. Elige otra tarjeta u otro medio de pago.</p>";
			}
	
			if($_POST['payment_status_detail']=='cc_rejected_other_reason')
			{
				echo "<p>Pago rechazado: Tu medio de pago no procesó el pago.</p>";
			}
			
			//Cierro la venta
			$_SESSION['venta_iniciada']="NO";
			unset($_SESSION['nro_venta']);	
		?>
		</div>
	</div>
	<div class="row">
		<div class="col-6">
		<p><a href="app_perfil.php" class="badge badge-info">Volver</a></p>
		</div>
	</div>
</div>
		