<?
include ("conex.php");
include("local_controla2.php");//controla2 usuario admin.
include("biblioteca.php");
$reg=mysql_query("SELECT * FROM registrados",$link);
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><strong>Registrados</strong></p>
<table width="90%" border="1" align="center" bordercolor="#999999">
  <tr> 
    <td width="9%" align="center"><div align="center">Nombre</div></td>
    <td width="9%" align="center"><div align="center">Apellido</div></td>
    <td width="5%" align="center"><div align="center">Dni</div></td>
    <td width="9%" align="center"><div align="center">Fecha de nacimiento</div>
      <div align="center"></div></td>
    <td width="8%" align="center"><div align="center">Celular</div></td>
    <td width="16%" align="center"><div align="center">Comentario</div></td>
    <td width="9%" align="center"><div align="center">Mail</div></td>
    <td width="8%" align="center"><div align="center">Actividad</div></td>
    <td width="9%" align="center">Profesor</td>
    <td width="9%" align="center"><div align="center">Mes Inscripto</div></td>
    <td width="9%" align="center">Mes pagado</td>
  </tr>
  <?
while($re=mysql_fetch_array($reg))
{
?>
  <tr> 
    <td><div align="center"><? echo $re['nombre'];?></div></td>
    <td><div align="center"><? echo $re['apellido'];?></div></td>
    <td><div align="center"><? echo $re['dni'];?></div></td>
    <td><div align="center"><? echo $re['nacimiento'];?></div></td>
    <td><div align="center"><? echo $re['celular'];?></div></td>
    <td><div align="center"><? echo $re['comentario'];?></div></td>
    <td><div align="center"><? echo $re['mail'];?></div></td>
    <td><div align="center"><? echo $re['actividad'];?></div></td>
    <td><div align="center"><? echo $re['profesor'];?></div></td>
    <td><div align="center"><? echo $re['mes_inscripto'];?></div></td>
    <td><div align="center"><? echo $re['mes_pagado'];?></div></td>
  </tr>
  <?
}
?>
</table>
</body>
</html>
