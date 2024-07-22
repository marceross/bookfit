<?
include("conex.php");
//session_start();	
include("local_controla.php");
$cod=$_POST['cod'];
$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, productos.costo, productos.foto, productos.descripcion, productos.cod_cat, productos.id_marca, productos.stock, marcas.nombre as nm FROM productos, marcas WHERE cod='$cod' AND productos.id_marca=marcas.id_marca");
$producto=mysqli_fetch_array($productos);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="75%" border="0">
  <tr> 
    <td height="512"><form name="form1" method="post" action="procesa_modificar.php?cod=<? echo $cod;?>">
        <p><strong>Modificar</strong></p>
        <p>nombre 
          <input name="nombre" type="text" id="nombre" value="<? echo $producto['np'];?>"/>
        </p>
        <p>cod 
          <input name="cod" type="text" id="cod" value="<? echo $producto['cp'];?>"/>
        </p>
        <p>descripcion 
          <input name="descripcion" type="text" id="descripcion" value="<? echo $producto['descripcion'];?>"/>
        </p>
        <p>costo 
          <input name="costo" type="text" id="costo" value="<? echo $producto['costo'];?>"/>
        </p>
        <p>foto 
          <input name="foto" type="text" id="foto" value="<? echo $producto['foto'];?>"/>
        </p>
        <p>cod_cat 
          <input name="cod_cat" type="text" id="cod_cat" value="<? echo $producto['cod_cat'];?>"/>
        </p>
        <p>id_marca 
          <input name="id_marca" type="text" id="id_marca" value="<? echo $producto['id_marca'];?>"/>
        </p>
        <p>stock 
          <input name="stock" type="text" id="stock" value="<? echo $producto['stock'];?>"/>
        </p>
        <p> 
          <input type="submit" name="Submit" value="Enviar">
        </p>
      </form></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
