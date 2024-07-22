<?
include("conex.php");
$dni=$_GET['usuario'];
mysqli_query($mysqli,"UPDATE registrados SET mail_confirma='S' WHERE dni='$dni'");
?>
<!DOCTYPE html>
<html lang="es">

<head>
<title>LOKALES TRAINING SPOT</title>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <LINK href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">  


</head>

<body>
<header>
<div class="container">
  <h2>Tu mail fue confirmado!</h2>
  <!--<p>Ahora podes participar del foro y comprar o vender productos usados en la seccion de compraventa</p>-->      
</div>

<a href="login.html" class="btn btn-primary">Comenzar</a>

<!--<footer>
LOKALES TRAINING SPOT | Caraffa 181 - Villa Allende (5105) - Cordoba - Argentina | 03543-430905 | hola@lokales.com.ar
</footer>-->
</body>
</html>
