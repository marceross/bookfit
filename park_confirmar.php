<?
	include("conex.php");
	$mail=$_GET['usuario'];
    mysql_query("UPDATE registrados SET confirmado='S' WHERE mail='$mail'",$link);
?>
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/skateclub-plantilla.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>LOKALES</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
  <table width="930" height="208" border="0" align="center">
    <!--DWLayoutTable-->
    <tr align="center"> 
      <td height="70" colspan="3"><img src="banner_skateclub.jpg" width="922" height="179" border="0" usemap="#Map"> 
      </td>
    </tr>
    <tr> 
      <td height="34" colspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <!--DWLayoutTable-->
          <tr> 
            <td width="924" height="32" valign="top"><!-- InstanceBeginEditable name="EditRegion3" -->
              <p>&nbsp;</p><center>
                <p><strong>GRACIAS... tu registro es confirmado!</strong></p>
                <p>Ahora podes participar del foro y comprar o vender productos 
                  usados en la seccion de compraventa</p>
                <p>&nbsp; </p>
              </center>
            <!-- InstanceEndEditable --></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td width="144" height="32">&nbsp;</td>
      <td width="595"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">LOKALES&copy; 
          | Calle Caraffa 181 | Villa Allende (5105) | Cordoba | Argentina<br />
          03543-401743 | info@lokales.com.ar</font></div></td>
      <td width="177"><div align="right"></div></td>
    </tr>
  </table>
</div>
<map name="Map">
  <area shape="rect" coords="22,138,65,156" href="#">
  <area shape="rect" coords="232,137,350,158" href="skaters.php">
  <area shape="rect" coords="360,137,395,158" href="foro.php">
  <area shape="rect" coords="403,138,486,160" href="compraventa.php">
  <area shape="rect" coords="841,138,902,158" href="contacto.htm">
</map>
</body>
<!-- InstanceEnd --></html>
