<?
include("conex.php");
//session_start();	
//include("local_controla.php");
$dni=$_GET['dni'];
$reg=mysqli_query($mysqli,"SELECT * FROM registrados WHERE dni='$dni'");
$re=mysqli_fetch_array($reg);
$actividades=mysqli_query($mysqli,"SELECT * FROM actividad ORDER BY nombre");
$profesores=mysqli_query($mysqli,"SELECT * FROM profesor ORDER BY nombre");
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
    <td height="512"><form name="form1" method="post" action="registrados_mod_procesa.php?dni=<? echo $dni;?>">
        <p>&nbsp;</p>
        <p>nombre 
          <input name="nom" type="text" id="nom" value="<? echo $re['nombre'];?>"/>
        </p>
        <p>apellido
          <input name="ape" type="text" id="ape" value="<? echo $re['apellido'];?>"/>
        </p>
        <p>dni
          <input name="dni" type="text" id="dni" value="<? echo $re['dni'];?>"/>
        </p>
        <p>fecha de nacimento 
          <input name="nac" type="text" id="nac" value="<? echo $re['nacimiento'];?>"/>
        AAAA-MM-DD</p>
        <p>celular 
          <input name="cel" type="text" id="cel" value="<? echo $re['celular'];?>"/>
        </p>
        <p>comentario
          <input name="com" type="text" id="com" value="<? echo $re['comentario'];?>" size="80" maxlength="500"/>
        </p>
        <p>mail
          <input name="mai" type="text" id="mai" value="<? echo $re['mail'];?>"/>
        </p>
        <p>actividad 
          <select name="act" id="act">
    	
    <?
	while($actividad=mysqli_fetch_array($actividades))
	{
		if($actividad['id_actividad']<>$re['actividad'])
		{
	?>
    		<option value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
    <?
         }
         else
         {
   ?>
   			<option selected value="<? echo $actividad['id_actividad'];?>"><? echo $actividad['nombre'];?></option>
   			   
         
    <?
		}
	}
	?>
    </select>
        </p>
        <p>profesor 
          <select name="pro" id="pro">
    	
    <?
	while($profesor=mysqli_fetch_array($profesores))
	{
		if($profesor['id_profesor']<>$re['profesor'])
		{
		
	?>
    		<option value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
    <?
		}
		else
		{
	?>
    		<option selected value="<? echo $profesor['id_profesor'];?>"><? echo $profesor['nombre'];?></option>
    <?
		}
	}
	?>
    </select>
        </p>
        <p>
          autorizacion 
          <?
		  if($re['autorizacion']=='N')
		  {
		  ?>
          	<input name="aut" type="checkbox" id="aut" value="S">
         <?
		  }
		  else
		  {
		 ?>
         	<input checked name="aut" type="checkbox" id="aut" value="S">
         <?
		  }
		 ?>
        </p>
        <p>
          certificado
            <?
		  if($re['certificado']=='N')
		  {
		  ?>
          	<input name="cer" type="checkbox" id="cer" value="S">
         <?
		  }
		  else
		  {
		 ?>
         	<input checked name="cer" type="checkbox" id="cer" value="S">
         <?
		  }
		 ?>
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
