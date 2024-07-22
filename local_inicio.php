<?
  session_name('app_admin');
  session_start();
	$_SESSION['pago']=1;
	$_SESSION['efectivo']="";
	$_SESSION['recargo']="";
	$_SESSION['descuento']="";
    date_default_timezone_set('America/Argentina/Cordoba');
	$hora_actual=gettimeofday();//Obtiene la hora actual.
	$_SESSION["hora"]=$hora_actual["sec"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title>LOKALES TRAINING SPOT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
 <LINK href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
<link rel="stylesheet" href="js/bootstrap.min.css" >

<script src="js/jquery.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

<style>
    
    @media only screen and (max-width: 767px) {
  .m_query{width:100%!important;}
}
</style>
</head>

<body>
<header>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
      <a class="navbar-brand" href="#"><img src="logo.gif" class="img-fluid" alt="Lokales Training spot logo image" width="200"></a>
</header>

<div class="container" style="margin-top:30px;">
<br><br><br>
<form action="local_procesa_inicio.php" method="post" style="width:40%;margin:0 auto;box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);padding:20px;" class="m_query">
<div class="div-menu">
    <div class="form-group col-md-12">
        <label for="usuario">Usuario</label>
        <input type="text" class="form-control" name="usuario" id="usuario">
    </div>

    <div class="form-group col-md-12">
        <label for="clave">Clave</label>
        <input name="clave" class="form-control" type="password" id="clave">
    </div>
    <div class="form-group" style="width:100%;margin:0 auto;text-align:center">
        <input type="submit" name="Submit" value="Entrar" class="btn btn-primary">
    </div>
</div>
</form>
</div>

</body>
</html>
