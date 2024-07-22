<?
	include("conex.php");	
	include("local_controla2.php");
?>
<!doctype html>
<html>

<head>
  <title>LOKALES</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <LINK href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">  

<style>
html, body {
    height: 100%;
}

html {
    display: table;
    margin: auto;
}

body {
    vertical-align: middle;
    padding: 4vw;
}

div {
    border: 1px solid lightgrey;
}

</style>
</head>

<body>
<div>
    <div>
        <h1>Cargo: Encargado General</h1>
    </div>
    <div>
        <h1>Puesto: Oficina</h1>
    </div>
    <div>
        <h1>Tareas del cargo</h1>
        <p><h5>DIARIAS</h5><br>
      
        Control de dinero de dia en curso o dia anterior<br>
        Control de anotaciones<br>
        Reposición de factureros<br>
      </p>
      <p><h5>SEMANALES</h5><br>
      Compras<br>
      Control de stock<br>
        Control de facturas de compras<br>
        Control de facturación
        <br>
      </p>
      <p><h5>MENSUALES</h5><br>
        Pagos profesores<br>
        Pagos impuestos y servicios<br>
        Balance
        <br>
      </p><h1>Procedimientos</h1>
    <p>Control de dinero<br>
      1- entrar en el sistema y buscar el monto de extracción del día<br>
      2- contar el dinero, debe coincidir con el monto anotado en el sistema<br>
      3- si no coincide, tomar nota de esto<br>
      4- en el caso que se haya pasado de hacer esta tarea y haya varios sobres, se debera sumar el total de extracciones de los dias que no se realizo la tarea y debera coincidir con el mismo monto en el sistema de esos dias</p>
      <p>Control de anotaciones<br>
        Una anotacion mal hecha hace que la caja no de correctamente<br>
        Tanto las ventas, como las extracciones y aportes deber coincidir con los movimientos diarios de la caja para que esta de correctamente
    </p>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col">
      1 of 2
    </div>
    <div class="col">
      2 of 2
    </div>
  </div>
  <div class="row">
    <div class="col">
      1 of 3
    </div>
    <div class="col">
      2 of 3
    </div>
    <div class="col">
      3 of 3
    </div>
  </div>
</div>
</body>
</html>