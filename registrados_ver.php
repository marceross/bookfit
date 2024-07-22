<?
include ("conex.php");
//include("local_controla.php");//controla2 usuario admin.
include("biblioteca.php");
$dni=$_GET['dni'];
$reg=mysqli_query($mysqli,"SELECT registrados.nombre as nomreg, apellido, dni, mail, nacimiento, comentario, foto, celular, mes_pagado, fecha, autorizacion, certificado, actividad.nombre as nomact, profesor.nombre as nompro FROM registrados, actividad, profesor WHERE dni='$dni' AND actividad=id_actividad AND profesor=id_profesor");
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<p>&nbsp;</p>
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
    <td width="9%" align="center"><div align="center">Profesor</div></td>
    <td width="9%" align="center"><div align="center">Mes Inscripto</div></td>
    <td width="9%" align="center">Ultimo pago</td>
    <td width="9%" align="center">&nbsp;</td>
  </tr>
  <?
while($re=mysqli_fetch_array($reg))
{
?>
  <tr> 
    <td><div align="center"><? echo $re['nomreg'];?></div></td>
    <td><div align="center"><? echo $re['apellido'];?></div></td>
    <td><div align="center"><? echo $re['dni'];?></div></td>
    <td><div align="center"><? echo formato_latino ($re['nacimiento']);?></div></td>
    <td><div align="center"><? echo $re['celular'];?></div></td>
    <td><div align="center"><? echo $re['comentario'];?></div></td>
    <td><div align="center"><? echo $re['mail'];?></div></td>
    <td><div align="center"><? echo $re['nomact'];?></div></td>
    <td><div align="center"><? echo $re['nompro'];?></div></td>
    <td><div align="center"><? echo $re['fecha'];?></div></td>
    <td><div align="center"><? echo formato_latino ($re['mes_pagado']);?></div></td>
    <td><a href="registrados_mod.php?dni=<? echo $re['dni'];?>">modificar</a></td>
  </tr>
  <?
}
?>
</table>
<p>
<tr>
<td><div>
<?
$foto_a_mostrar=$re['foto'];
if($foto_a_mostrar=='')
	{
	$foto_a_mostrar="/imagenes_registrados/foto_generica.jpg";
	}
?>                      
<p><img src="<? echo $foto_a_mostrar;?>" width="150"/></p>
</div></td>
</tr>
</p>
</body>
</html>
