<?
	include("conex.php");
	include("local_controla2.php");
	$categorias=mysqli_query($mysqli,"SELECT * FROM categorias ORDER BY nombre");
	$productos=mysqli_query($mysqli,"SELECT * FROM productos ORDER BY nombre");
	$marcas=mysqli_query($mysqli,"SELECT * FROM marcas ORDER BY nombre");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form action="procesa_alta_producto.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <div align="left">
<p><strong>Carga de producto</strong></p>
    <p>Categoria<br />
      <select name="cod_cat" id="cod_cat">
        <?
	while($categoria=mysqli_fetch_array($categorias))
	{
?>
        <option value="<? echo $categoria['cod'];?>" selected="selected"> <? echo $categoria['nombre'];?> 
        </option>
        <?
	}
?>
        <option value="0"> </option>
      </select>
      <a href="alta_categoria.php">nueva categoria</a></p>
    <p>Marca<br />
      <select name="marca" id="select">
        <?
	while($marca=mysqli_fetch_array($marcas))
	{
?>
        <option value="<? echo $marca['id_marca'];?>" selected="selected"> <? echo $marca['nombre'];?> 
        </option>
        <?
	}
?>
        <option value="0"> </option>
      </select>
      <a href="alta_marca.php">nueva marca</a></p>
    <p>Foto(*)<br />
      <input type="file" name="archivo1" id="archivo1" />
      </p>
  </div>
  </label>
  Nombre del producto(*)<br />
<label>
    <input type="text" name="nombre" id="nombre" />
    </label>
  <p>Costo<br />
    <input name="costo" type="text" id="costo" size="5" maxlength="5" />
  </p>
  <p>Descripcion<br />
    <textarea name="descripcion" cols="50" rows="3" id="descripcion"></textarea>
  </p>
  <p>
    <label>
    <input type="submit" name="button" id="button" value="Guardar" />
    </label>
  </p>
</form>
<p><a href="local_datos.php">HOME (local_datos)</a></p>
<p><a href="local_kill_session.php">DESCONECTAR</a></p>
</body>
</html>
