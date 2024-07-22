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
<form action="procesa_alta_proveedor.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <div align="left">
    <p><strong>Alta Proveedor</strong></p>
  </div>
  </label>
  Nombre<br />
<label>
    <input type="text" name="nombre" id="nombre" />
    <br />
    <br />
    CUIT<br />
<input type="text" name="cuit" id="cuit" />
<br />
<br /> 
Mail
<br />
<input type="text" name="mail" id="mail" />
<br />
    <br />
<br />
</label>
  <p>
    <label>
    <input type="submit" name="button" id="button" value="Guardar" />
    </label>
  </p>
</form>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
</body>
</html>
