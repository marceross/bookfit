<?
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	include("conex.php");	
	//mail para darme cuenta que se ejecuto este archivo
	//require("class.phpmailer.php");
	//require("class.smtp.php");
	// SDK de Mercado Pago
	require __DIR__ .  '/vendor/autoload.php';	
	MercadoPago\SDK::setAccessToken
	("APP_USR-7459218136972856-071713-58fe64fed0c17a85a07c6e3e442d0fc7-79024106");

	if (isset($_GET["id"], $_GET["topic"])) 
	{
    	http_response_code(200);    	
	}	

	$marcar_pagado=false;

    $merchant_order = null;

    switch($_GET["topic"]) {
        case "payment":
            $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
            // Get the payment and the corresponding merchant_order reported by the IPN.
            $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
            break;
        case "merchant_order":
            $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
            break;
    }	

    $paid_amount = 0;
	$estados_pagos="";
    foreach ($merchant_order->payments as $payment) {
        if ($payment->status == 'approved'){
            $paid_amount += $payment->transaction_amount;
			$estados_pagos=$estados_pagos.$payment->status." ";
        }
    }

    // If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items
    if($paid_amount >= $merchant_order->total_amount)
	{        
		$marcar_pagado=true;       
    } 
	else 
	{
        //print_r("Not paid yet. Do not release your item.");
		$marcar_pagado=false;
    }

	if($marcar_pagado==true)
	{
		$nro_venta=$merchant_order->external_reference;
		
		//Verifico si la venta figura como NO pagada
		if(!$ventas=mysqli_query($link, "SELECT * FROM ventas WHERE id_venta='$nro_venta'"))
		{
			echo mysqli_error($link);
			exit();
		} 
		
		$venta=mysqli_fetch_array($ventas);		
		if($venta['pagado']=='N')
		{
			//Marco la venta como pagada			
			mysqli_query($link, "UPDATE  ventas SET pagado='S' WHERE id_venta='$nro_venta'");	
			//ENVIO MAIL DE AVISO DE PAGO
			// Datos de la cuenta de correo utilizada para enviar vía SMTP
			/*$smtpHost = "aerecor.ferozo.com";  // Dominio alternativo brindado en el email de alta 
			$smtpUsuario = "enviosweb@recordparts.com.ar";  // Mi cuenta de correo
			$smtpClave = "Pepsi306";  // Mi contraseña

			// Email donde se enviaran los datos cargados en el formulario de contacto
			$emailDestino = "gerencia@recordparts.com.ar";	
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			$mail->Port = 587; 
			$mail->IsHTML(true); 
			$mail->CharSet = "utf-8";

			// VALORES A MODIFICAR //
			$mail->Host = $smtpHost; 
			$mail->Username = $smtpUsuario; 
			$mail->Password = $smtpClave;

			$mail->From = $smtpUsuario; // Email desde donde envío el correo.
			$mail->FromName = "Sitio Web - Recordparts";
			$mail->AddAddress($emailDestino); // Esta es la dirección a donde enviamos los datos del formulario

			$mail->Subject = "Pago online pedido Nro. ".$nro_venta; // Este es el titulo del email.
			$mensaje="El pedido Nro.: ".$nro_venta." ha sido PAGADO.<br><br>Ver pedido: <a href=https://www.recordparts.com.ar/admin/ver_pedido.php?nro=".$nro_venta.">https://www.recordparts.com.ar/admin/ver_pedido.php?nro=".$nro_venta."</a><br><br>Mercado pago ID: ".$_GET["id"]."<br><br> Topic: ".$_GET["topic"];
			$mensajeHtml = $mensaje;
			$mail->Body = "{$mensajeHtml}"; // Texto del email en formato HTML
			$mail->AltBody = "{$mensaje}"; // Texto sin formato HTML
			// FIN - VALORES A MODIFICAR //
				//Southware
				$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			$estadoEnvio = $mail->Send();	*/
		}
	}
	
	//ENVIO MAIL PRUEBA IPN
	// Datos de la cuenta de correo utilizada para enviar vía SMTP
	/*$smtpHost = "aerecor.ferozo.com";   // Dominio alternativo brindado en el email de alta 
	$smtpUsuario = "enviosweb@recordparts.com.ar";  // Mi cuenta de correo
	$smtpClave = "Pepsi306";  // Mi contraseña
	
	// Email donde se enviaran los datos cargados en el formulario de contacto
	$emailDestino = "tomas@southware.com.ar";	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Port = 587; 
	$mail->IsHTML(true); 
	$mail->CharSet = "utf-8";
	
	// VALORES A MODIFICAR //
	$mail->Host = $smtpHost; 
	$mail->Username = $smtpUsuario; 
	$mail->Password = $smtpClave;
	
	$mail->From = $smtpUsuario; // Email desde donde envío el correo.
	$mail->FromName = "Desde la Web Recordparts";
	$mail->AddAddress($emailDestino); // Esta es la dirección a donde enviamos los datos del formulario
	
	$mail->Subject = "IPN ejecutada"; // Este es el titulo del email.
	//$mensaje="Se ejecuto la IPN. Nro. de Venta: ".$merchant_order->external_reference." Topic: ".$_GET["topic"]." Consulta: "."UPDATE pedidos SET pagado='S' WHERE id_pedido=".$nro_venta." Paid amount: ".$paid_amount." Total amount: ".$merchant_order->total_amount." Shipment: ".$merchant_order->shipments."Shipment status: ".$merchant_order->shipments[0]->status." Payments status: ".$estados_pagos;
	$mensaje="Se ejecuto la IPN. Nro. de Venta: ".$merchant_order->external_reference." Topic: ".$_GET["topic"]." Consulta: "."UPDATE pedidos SET pagado='S' WHERE id_pedido=".$nro_venta." Paid amount: ".$paid_amount." ID: ".$_GET["id"];
	$mensajeHtml = $mensaje;
	$mail->Body = "{$mensajeHtml}"; // Texto del email en formato HTML
	$mail->AltBody = "{$mensaje}"; // Texto sin formato HTML
	// FIN - VALORES A MODIFICAR //
	//Southware
	$mail->SMTPOptions = array(
	'ssl' => array(
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
	)
);
	$estadoEnvio = $mail->Send();*/
		
?>