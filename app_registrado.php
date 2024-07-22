<?
include ("conex.php");
//include("local_controla.php");//controla2 usuario admin.
include("biblioteca.php");
$actividades=mysqli_query($mysqli,"SELECT * FROM actividad ORDER BY nombre");
$profesores=mysqli_query($mysqli,"SELECT * FROM profesor ORDER BY nombre");
if(isset($_POST['dni']))
{
	{
	if($_POST['dni']<>'')//si es distinto de campo vacio
	{
		$reg=mysqli_query($mysqli,"SELECT * FROM registrados WHERE dni=".$_POST['dni']);
	}
	if($_POST['act']<>'')
	{
		$reg=mysqli_query($mysqli,"SELECT * FROM registrados WHERE actividad=".$_POST['act']);
		echo mysqli_error($mysqli);
	}
	if($_POST['pro']<>'')
	{
		$reg=mysqli_query($mysqli,"SELECT * FROM registrados WHERE profesor=".$_POST['pro']);
	}
}
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
    <td width="20%" align="center"><div align="center">Nombre</div></td>
    <td width="24%" align="center"><div align="center">Apellido</div></td>
    <td width="9%" align="center"><div align="center">Ultimo pago</div></td>
    <td width="15%" align="center"><div align="center">Autorizacion firmada</div></td>
    <td width="14%" align="center"><div align="center">Certificado medico</div></td>
    <td width="7%" align="center">&nbsp;</td>
  </tr>
  <?  
while($re=mysqli_fetch_array($reg))
{
?>
  <tr>
    <td><div align="center"><? echo $re['nombre'];?></div></td>
    <td><div align="center"><? echo $re['apellido'];?></div></td>
    <td><div align="center"><? echo formato_latino ($re['mes_pagado']);?></div></td>
    <td align="center" valign="middle"><div align="center"><? echo $re['autorizacion'];?></div></td>
    <td align="center" valign="middle"><div align="center"><? echo $re['certificado'];?></div></td>
    <td align="center" valign="middle"><div align="left"><a href="registrados_ver.php?dni=<? echo $re['dni'];?>">ver</a></div></td>
  </tr>
  <?
}
}
?>
</table><form action="app_registrado.php" method="post" enctype="multipart/form-data" name="form1">
  <p><strong>Buscar</strong></p>
  <p>Dni  
    <input name="dni" type="text" id="dni" size="10" maxlength="8">
    Actividad
    <select name="act" id="act">
    	<option value="" selected>Seleccionar</option>
    <?
	while($actividad=mysqli_fetch_array($actividades))
	{
	?>
    	<option value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
    <?
	}
	?>
    </select>
    <label> </label>
  Profesor 
    <select name="pro" id="pro">
    	<option value="" selected>Seleccionar</option>
    <?
	while($profesor=mysqli_fetch_array($profesores))
	{
	?>
    	<option value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
    <?
	}
	?>
    </select>
  </p>
<label></label>
  <p> 
    <input type="submit" name="buscar" value="BUSCAR" id="buscar">
  </p>
</form></p>
</body>
</html>
