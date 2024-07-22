<?
session_name('app_admin');
include ("conex.php");
include("local_controla.php");
include("biblioteca.php");
$id_usuario=$_SESSION['usuario_act'];
$mensajes=mysqli_query($mysqli, "SELECT * FROM mensajes_internos, mensajes_internos_destinatarios, usuarios WHERE usuarios.id_usuario=mensajes_internos.id_usuario_rem AND mensajes_internos.id_mensaje=mensajes_internos_destinatarios.id_mensaje AND mensajes_internos_destinatarios.id_usuario='$id_usuario'");
$usuarios=mysqli_query($mysqli, "SELECT * FROM usuarios WHERE activo='S' AND id_tipo_usuario='2'");
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><a href="local_ventas.php">HOME</a></p>
<p><strong>MENSAJES</strong></p>
<table width="89%" border="1" align="center">
  <tr> 
    <td width="6%"><div align="center">Remitente</div></td>
    <td width="30%"><div align="center">Mensaje</div></td>
    <td width="30%"><div align="center">Confirmado</div></td>
    <td width="9%"><div align="center">Fecha</div></td>
  </tr>
<?
while($mensaje=mysql_fetch_array($mensajes))
{
?>
  <tr> 
    <td><div align="center"><? echo $mensaje['usuario'];?></div></td>
    <td><div align="center"><? echo $mensaje['mensaje'];?></div></td>
    <td><div align="center"><? 
	if($mensaje['confirmado']=='S')
	{
		echo "S";
	}
	else
	{
	?>
    	<a href="confirmar_mensaje.php?id=<? echo $mensaje['id_mensaje'];?>"><img src="Check-icon.png" width="32" height="32" border="0"></a>
	<?
	}
	?>        
    </div></td>
    <td><div align="center"><? echo formato_latino ($mensaje['fecha']);?></div></td>
  </tr>
<?
}
?>
  <tr> 
    <td colspan="4"><form action="procesa_mensaje_interno.php" method="post" enctype="multipart/form-data" name="form1">
        <p align="center">&nbsp;</p>
        <p align="center">mensaje<br>
          <textarea name="texto_mensaje" cols="100" rows="5" id="texto_mensaje"></textarea>
        </p>
        <table width="141" border="0" align="center">
          <?
	while($usuario=mysql_fetch_array($usuarios))
	{
?>
          <tr>
            <td width="107"><? echo $usuario['usuario'];?></td>
            <td width="24"><label>
              <input type="checkbox" name="dest[<? echo $usuario['id_usuario'];?>]" id="checkbox" value="<? echo $usuario['id_usuario'];?>">
            </label></td>
          </tr>
<?
	}
?>          
        </table>
        <p align="center"> 
          <input type="submit" name="Submit" value="ENVIAR">
        </p>
      </form></td>
  </tr>

</table>
</body>
</html>
