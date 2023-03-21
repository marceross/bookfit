<?
include("local_controla_app.php");// es el controla de app_reservas
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos
include("local_controla_app.php");		
unset($_SESSION['usuario_act']);
$_SESSION["autentificado"]="NO";
$_SESSION['procedencia']='';
header("Location: login.html");
?>
<head>
</head>
<body>
</body>
</html>
