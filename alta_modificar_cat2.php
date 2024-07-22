<?
include("conex.php");
//session_start();	
include("local_controla.php");
$cod=$_POST['cod'];
$categorias=mysql_query("SELECT * FROM categorias WHERE cod='$cod'",$link);
$categoria=mysql_fetch_array($categorias);
//$productos=mysql_query("SELECT productos.cod as cp, productos.nombre as np, productos.costo, productos.foto, productos.descripcion, productos.cod_cat, productos.id_marca, productos.stock, marcas.nombre as nm FROM productos, marcas WHERE cod='$cod' AND productos.id_marca=marcas.id_marca",$link);
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
    <td height="442"><form name="form1" method="post" action="procesa_modificar_cat.php?cod=<? echo $cod;?>">
        <p><strong>Modificar</strong></p>
        <p>cod
<input name="cod" type="text" id="cod" value="<? echo $categoria['cod'];?>"/>
        </p>
        <p>foto_generica 
          <input name="foto_generica" type="text" id="foto_generica" value="<? echo $categoria['foto_generica'];?>"/>
        </p>
        <p>nombre 
          <input name="nombre" type="text" id="nombre" value="<? echo $categoria['nombre'];?>"/>
        </p>
        <p>cod_padre 
          <input name="cod_padre" type="text" id="cod_padre" value="<? echo $categoria['cod_padre'];?>"/>
        </p>
        <p>margen 
          <input name="margen" type="text" id="margen" value="<? echo $categoria['margen'];?>"/>
        </p>
        <p>activa 
          <input name="activa" type="text" id="activa" value="<? echo $categoria['activa'];?>"/>
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
