<?php
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
$array_fecha=getdate();
$fecha_hoy=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
//seleccionar al que el vencimiento sea mayor a la fecha de hoy y que haya tenido alguna asistencia 
$registrados=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='31943322'");
$registrado=mysqli_fetch_array($registrados);

$nom=$registrado['nombre'];
$dni=$registrado['dni'];
$mai=$registrado['mail'];

//$to=$registrado['mail'];

$to = "$mai";
$subject = "Lokales training";
$message = "
<html>
<head>
<title>Lokales training spot</title>
<style>
a {
    color: #3498db;
    text-decoration: none; 
  }
a:hover {
    opacity: 0.8;
  }
.main {
    background: #ffffff;
    border-radius: 3px;
    width: 100%; 
  }
.wrapper {
    box-sizing: border-box;
    padding: 20px; 
  }
.content {
    box-sizing: border-box;
    display: block;
    margin: 0 auto;
    max-width: 580px;
    padding: 10px; 
  }
.content-block {
    padding-bottom: 10px;
    padding-top: 10px;
  }
.btn {
    box-sizing: border-box;
    width: 100%; }
    .btn > tbody > tr > td {
      padding-bottom: 15px; }
    .btn table {
      width: auto; 
  }
.btn table td {
      background-color: #ffffff;
      border-radius: 5px;
      text-align: center; 
  }
.btn a {
      background-color: #ffffff;
      border: solid 1px #3498db;
      border-radius: 5px;
      box-sizing: border-box;
      color: #3498db;
      cursor: pointer;
      display: inline-block;
      font-size: 14px;
      font-weight: bold;
      margin: 0;
      padding: 12px 25px;
      text-transform: capitalize; 
  }
.btn-primary table td {
    background-color: #3498db; 
  }

.btn-primary a {
    background-color: #3498db;
    border-color: #3498db;
    color: #ffffff; 
  }
.footer {
    clear: both;
    margin-top: 10px;
    text-align: center;
    width: 100%; 
  }
    .footer td,
    .footer p,
    .footer span,
    .footer a {
      color: #999999;
      font-size: 12px;
      text-align: center; 
  }
.apple-link a {
    color: inherit !important;
    font-family: inherit !important;
    font-size: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
    text-decoration: none !important; 
  }
</style>
</head>
<body>
<table role="."presentation"." class="."main".">
<tr>
  <td class="."wrapper".">
    <table role="."presentation".">
      <tr>
        <td>
            <p>Hola $nom,</p>
            <p>¿Cómo estás? soy Marcelo, encargado de Lokales. Junto a Beti, Marcos y Mateo estamos trabajando para mejorar tu experiencia y hacer de Lokales un espacio único.</p>
            <p>Hace poco empezamos con el sistema de puntos y reservas, por favor contame un poco lo bueno, lo malo o que te gustaría.</p>
            <p>Aprovecho para darte unos tips para que saques mejor provecho a tus puntos.</p><br>
          <table role="."presentation"." class="."btn btn-primary".">
            <tbody>
              <tr>
                <td align="."left".">
                  <table role="."presentation".">
                    <tbody>
                      <tr>
                        <td> <a href="."https://www.lokales.com.ar/email_tips.php?usu=$dni".">Ver más!</a> </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
          <!--<p><Saludos!</p>-->
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>
<div class="."footer".">
<table role="."presentation".">
  <tr>
    <td class="."content-block".">
    <br>Has recibido este correo electrónico porque eres parte de nuestra base de clientes. Si quieres dejar de recibir información haz clic <a href="."https://www.lokales.com.ar/email_cancel.php?usu=$dni".">AQUÍ y cancela tu suscripción.</a><!--Si quieres dejar de recibir información, resúmenes de tus visitas y actividades haz clic-->
    <h3><span class="."apple-link".">Lokales training spot</span></h3>
    </td>
  </tr>
</table>
</div>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <hola@lokales.com.ar>' . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

mail($to,$subject,$message,$headers);
?>