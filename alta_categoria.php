<?
	include("conex.php");
	include("local_controla2.php");
	$categorias=mysql_query("SELECT * FROM categorias ORDER BY nombre",$link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="procesa_alta_categoria.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <strong>Carga de categoría</strong><br />
  <div align="left">
<p>Foto(*)<br />
      <input type="file" name="archivo1" id="archivo1" />
      </p>
  </div>
  </label>
  Nombre de la categoría(*)<br />
<label>
    <input type="text" name="nombre" id="nombre" />
    </label>
  <p>
    <label> Categoría padre<br />  
    <select name="cod_padre" id="cod_padre">
<?
	while($categoria=mysql_fetch_array($categorias))
	{
?>   
		<option value="<? echo $categoria['cod'];?>"><? echo $categoria['nombre'];?></option> 
<?
	}
?>      
	    <option selected="selected" value="0"> </option>
    </select>
    ver </label>
  </p>
  <p>Activa?<br />
    <select name="activa" id="activa">
      <option value="S">S</option>
      <option value="N" selected="selected">N</option>
    </select>
  </p>
  <p>Margen<br />
    <input name="margen" type="text" id="margen" value="2" size="4" maxlength="4" />
  <p>
    <label>
    <input type="submit" name="button" id="button" value="Guardar" />
    </label>
  </p>
</form>
<p><a href="alta_producto.php">VOLVER A CARGA DE PRODUCTO</a> </p>
</body>
</html>
