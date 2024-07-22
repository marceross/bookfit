<?
include ("conex.php");
//include("local_controla.php");//controla2 usuario admin.
include("biblioteca.php");
$actividades=mysqli_query($mysqli,"SELECT * FROM actividad ORDER BY nombre");
$profesores=mysqli_query($mysqli,"SELECT * FROM profesor ORDER BY nombre");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
<p align="center">&nbsp;</p>
<form action="registrar_procesa.php" method="post" enctype="multipart/form-data" name="form1">
                <table width="45%" border="0" align="center" cellpadding="6">
                  <tr> 
                    <td width="31%" align="right"><strong><font color="#666666">Nombre</font></strong> 
                      (<font color="#FF0000">*</font>)</td>
                    <td><input name="nom" type="text" id="nom" size="30" maxlength="30"></td>
                  </tr>
                  <tr> 
                    <td align="right"><font color="#666666"><strong>Apellido</strong></font> 
                      (<font color="#FF0000">*</font>)</td>
                    <td><input name="ape" type="text" id="ape" size="30" maxlength="30"></td>
                  </tr>
				  <tr> 
                    <td align="right"><font color="#666666"><strong>DNI</strong></font> 
                      (<font color="#FF0000">*</font>)</td>
                    <td><input name="dni" type="text" id="dni" size="10" maxlength="8"></td>
                  </tr>
                  <tr> 
                    <td align="right"><font color="#999999"><strong><font color="#666666">Fecha 
                      de nacimiento</font></strong></font> (<font color="#FF0000">*</font>)</td>
                    <td><label> 
                      <select name="dia" id="dia">
                        <?
					for($i=1;$i<=31;$i++)
					{
						if($i<10)
						{
							$d="0".$i;
						}
						else
						{
							$d=$i;
						}
?>
                        <option value="<? echo $d;?>"> 
                        <? echo $d;?>
                        </option>
                        <?
					}
?>
                      </select>
                      / 
                      <select name="mes" id="mes">
                        <?
					for($i=1;$i<=12;$i++)
					{
						if($i<10)
						{
							$d="0".$i;
						}
						else
						{
							$d=$i;
						}
?>
                        <option value="<? echo $d;?>"> 
                        <? echo $d;?>
                        </option>
                        <?
					}
?>
                      </select>
                      / 
                      <select name="anio" id="anio">
                        <?
					for($i=1950;$i<=2014;$i++)
					{						
?>
                        <option value="<? echo $i;?>"> 
                        <? echo $i;?>
                        </option>
                        <?
					}
?>
                      </select>
                      DD/MM/AAAA</label></td>
                  </tr>
                  <tr> 
                    <td align="right"><font color="#666666"><strong>numero de telefono</strong></font> 
                      (<font color="#FF0000">*</font>)</td>
                    <td>0 
                      <input name="caracteristica" type="text" id="caracteristica" size="5" maxlength="5">
                      15 
                      <input name="celular" type="text" id="celular" size="10" maxlength="10"></td>
                  </tr>
                  <tr> 
                    <td align="right"><strong><font color="#666666" face="Times New Roman, Times, serif">Comentario</font></strong></td>
                    <td><textarea name="com" cols="50" rows="4" id="com" onFocus="this.select()">tel. alternativo, nombre padres, deudas</textarea></td>
                  </tr>
                  <tr> 
                    <td align="right"><strong><font color="#666666" face="Times New Roman, Times, serif">Foto</font></strong></td>
                    <td><label> 
                      <input type="file" name="archivo1" id="archivo1">
                      <font color="#FF0000" face="Arial, Helvetica, sans-serif">Peso maximo 500k</font></label></td>
                  </tr>
                  <tr> 
                    <td align="right"><font color="#666666"><strong><font face="Times New Roman, Times, serif">Mail</font></strong></font><font face="Times New Roman, Times, serif"> 
                      (<font color="#FF0000">*</font>)</font></td>
                    <td><input name="mai" type="text" id="mai" size="30" maxlength="50"></td>
                  </tr>
                  <tr> 
                    <td align="right"><strong><font color="#666666" face="Times New Roman, Times, serif">Actividad</font></strong><font face="Times New Roman, Times, serif"> 
                      (<font color="#FF0000">*</font>)</font></td>
                    <td><select name="act" id="act">
    <?
	while($actividad=mysqli_fetch_array($actividades))
	{
	?>
    	<option value="<? echo $actividad['id_actividad'];?>" selected="selected"><? echo $actividad['nombre'];?></option>
    <?
	}
	?>
    </select></td>
                  </tr>
                  <tr> 
                    <td align="right"><font color="#666666"><strong><font face="Times New Roman, Times, serif">Profesor</font></strong></font><font face="Times New Roman, Times, serif"> 
                      (<font color="#FF0000">*</font>)</font></td>
                    <td><select name="pro" id="pro">
    <?
	while($profesor=mysqli_fetch_array($profesores))
	{
	?>
    	<option value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
    <?
	}
	?>
    </select></td>
                  <tr> 
                    <td align="right"><font color="#666666"><strong><font face="Times New Roman, Times, serif">Autorizacion firmada</font></strong></font></td>
                    <td><input name="per" type="checkbox" id="per" value="S"></td>
                  </tr>
                  <tr> 
                    <td align="right"><font color="#666666"><strong><font face="Times New Roman, Times, serif">Certificado medico</font></strong></font></td>
                    <td><input name="cer" type="checkbox" id="cer" value="S"></td>
                  </tr>
				  <!--
                  <tr>
                    <td colspan="2"><div align="left"><font size="1" face="Arial, Helvetica, sans-serif">Alta</font></div></td>
                  </tr>
				  -->
                  <tr align="right"> 
                    <td colspan="2"><div align="center"> 
                        <p><font face="Times New Roman, Times, serif">(<font color="#FF0000">*</font>)</font> campo obligatorio</p>
                        <p> 
                          <input type="submit" name="Submit" value="Enviar">
                        </p>
                    </div></td>
                  </tr>
  </table>
</form>
</body>
</html>