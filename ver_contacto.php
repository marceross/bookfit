<?
include ("conex.php");
include("local_controla.php");
include("biblioteca.php");
$contacto=mysql_query("SELECT * FROM contacto ORDER BY contacto.fecha DESC",$link);
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><a href="local_ventas.php">HOME</a></p>
<p><strong>CONTACTO</strong></p>
<table width="89%" border="1" align="center">
  <tr> 
    <td width="7%"><div align="center">Empresa</div></td>
    <td width="7%"><div align="center">Actividad</div></td>
    <td width="7%"><div align="center">Telefono</div></td>
    <td width="4%"><div align="center">Mail</div></td>
    <td width="6%"><div align="center">Nombre</div></td>
    <td width="30%"><div align="center">Comentario</div></td>
    <td width="30%"><div align="center">Respuesta</div></td>
    <td width="9%"><div align="center">Fecha</div></td>
  </tr>
  <?
while($contact=mysql_fetch_array($contacto))
{
?>
  <tr> 
    <td><div align="center"><? echo $contact['empresa'];?></div></td>
    <td><div align="center"><? echo $contact['actividad'];?></div></td>
    <td><div align="center"><? echo $contact['telefono'];?></div></td>
    <td><div align="center"><? echo $contact['mail'];?></div></td>
    <td><div align="center"><? echo $contact['nombre'];?></div></td>
    <td><div align="center"><? echo $contact['comentario'];?></div></td>
    <td><div align="center"><? echo $contact['respuesta'];?></div></td>
    <td><div align="center"><? echo formato_latino ($contact['fecha']);?></div></td>
  </tr>
  <?
	if($contact['respuesta']=='')
	{
?>
  <tr> 
    <td colspan="8"><form name="form1" method="post" action="procesa_respuesta_contacto.php?cont=<? echo $contact['codigo'];?>">
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
