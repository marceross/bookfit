<?
	include("conex.php");
	include("local_controla2.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form action="procesa_alta_marca.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <div align="left">
    <p><strong>Alta Marca</strong></p>
    <p>logo(*)<br />
      <input type="file" name="archivo1" id="archivo1" />
      </p>
  </div>
  </label>
  Nombre de la marca<br />
<label>
    <input type="text" name="nombre" id="nombre" />
    </label>
  <p>
    <label>
    <input type="submit" name="button" id="button" value="Guardar" />
    </label>
  </p>
</form>
<p><a href="alta_producto.php">VOLVER A CARGA DE PRODUCTO</a></p>
</body>
</html>
