<?
include ("conex.php");
include("local_controla.php");
include("biblioteca.php");
$consultas=mysql_query("SELECT consultas.numero, consultas.consulta, consultas.mail, consultas.fecha, consultas.cod_producto, consultas.respuesta, productos.nombre as np, marcas.nombre as nm FROM consultas, productos, marcas WHERE cod=cod_producto AND productos.id_marca=marcas.id_marca ORDER BY consultas.fecha DESC",$link);
//$productos=mysql_query("SELECT * FROM productos",$link);
//$producto=mysql_fetch_array($productos);
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><a href="local_ventas.php">HOME</a></p>
<p><strong>CONSULTAS DE PRODUCTOS</strong></p>
<table width="89%" border="1" align="center">
  <tr> 
    <td width="36%"><div align="center">Consulta</div></td>
    <td width="21%"><div align="center">Respuesta</div></td>
    <td width="15%"><div align="center">Producto</div></td>
    <td width="14%"><div align="center">Fecha</div></td>
    <td width="14%"><div align="center">Mail</div></td>
  </tr>
  <?
while($consulta=mysql_fetch_array($consultas))
{
?>
  <tr> 
    <td><div align="center"><? echo $consulta['consulta'];?></div></td>
    <td><div align="center"><? echo $consulta['respuesta'];?></div></td>
    <td><div align="center"><? echo $consulta['np']." ".$consulta['nm'];?></div></td>
    <td><div align="center"><? echo formato_latino ($consulta['fecha']);?></div></td>
    <td><div align="center"><? echo $consulta['mail'];?></div></td>
  </tr>
  <?
	if($consulta['respuesta']=='')
	{
?>
  <tr> 
    <td colspan="5"><form name="form1" method="post" action="procesa_respuesta_consulta.php?cons=<? echo $consulta['numero'];?>">
        <p align="center"> 
          <textarea name="respuesta" cols="100" rows="5" id="respuesta"></textarea>
        </p>
        <p align="center"> 
          <input type="submit" name="Submit" value="RESPONDER">
        </p>
      </form></td>
  </tr>
  <?
	}
}
?>
</table>
</body>
</html>
