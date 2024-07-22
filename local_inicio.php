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
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
<header>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
      <a class="navbar-brand" href="#"><img src="logo.gif" class="img-fluid" alt="Lokales Training spot logo image" width="200"></a>
</header>

<div class="container">
<br><br><br>
<form action="local_procesa_inicio.php" method="post" name="" id="">
<div class="div-menu">
    <div class="form-group col-md-6">
        <label for="usuario">Usuario</label>
        <input type="text" class="form-control" name="usuario" id="usuario">
    </div>

    <div class="form-group col-md-6">
        <label for="clave">Clave</label>
        <input name="clave" class="form-control" type="password" id="clave">
    </div>
    <div> 
        <input type="submit" name="Submit" value="Entrar" class="btn btn-primary">
    </div>
</div>
</form>
</div>

</body>
</html>
