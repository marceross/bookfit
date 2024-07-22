<?
include("conex.php");
$mai=$_GET['mai'];
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"></head>

<body bgcolor="#caca90">
<form name="form1" method="post" action="alta_sorteo.php">
  <table width="75%" border="0" align="center">
    <tr> 
      <td width="24%" rowspan="4" align="center" valign="middle"><strong></strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td width="12%"><div align="left">Mail</div></td>
      <td width="37%"><strong><? echo $mai;?></strong></td>
      <td width="27%">&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="left">Numero de entrada</div></td>
      <td><input name="num" type="text" id="usu2" size="20" maxlength="20"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="right"></div></td>
      <td><p><strong>Carga tu numero de entrada y guardala como comprobante.</strong></p>
        <p> NO SIRVEN las entradas de meses anteriores.</p></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td rowspan="8" align="center" valign="middle"><strong></strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td align="center" valign="middle"><input type="submit" name="Submit" value="Cargar"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p></form>
<p align="center"><em><font size="2">&copy; 2009 LoKales&reg; de HIROSS S. de 
  H.</font></em> </p>
</body>
</html>
