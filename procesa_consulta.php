<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/shop-plantilla.dwt" codeOutsideHTMLIsLocked="false" -->
<?
include("conex.php");
$categorias=mysql_query("SELECT * FROM categorias WHERE activa='S' AND cod_padre=0",$link);
if(isset($_GET['cod_cat']))
{
	$_SESSION['pcategoria']="SI";
	//Determinamos si la categoria es padre o no
	$categorias_padre=mysql_query("SELECT * FROM categorias WHERE cod=".$_GET['cod_cat'],$link);
    $categoria_padre=mysql_fetch_array($categorias_padre);
    if($categoria_padre['cod_padre']==0)
    {
		$_SESSION['cat_padre_activa']=$_GET['cod_cat'];
    }
}
?>
<head>
<link rel="icon" href="http://www.boardhouse.com.ar/4.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://www.boardhouse.com.ar/4.ico" type="image/x-icon" />
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<META NAME="Description" CONTENT="Casa de Deportes Extremos">
<META NAME="Keywords" CONTENT="skate,wakeboard,kitesurf,snowboard,longboard,surf,kite,wake,wakeskate,wakesurf,tablas,decks,boards">
<META NAME="Robots" CONTENT="All">
<script>

function awmShowMenu(x,y,z,v){} 

document.oncontextmenu = rightclickmenu; 

function rightclickmenu(e) {if (document.all) {awmShowMenu('rightCLICK',event.clientX+document.body.scrollLeft,event.clientY+document.body.scrollTop); return false;} 

if (navigator.userAgent.indexOf('Gecko')>-1) {awmShowMenu('rightCLICK',e.pageX,e.pageY); return false;}} 
// End -->

<!-- Begin
/*function right(e) {
if (navigator.appName == 'Netscape' && 
(e.which == 3 || e.which == 2))
return false;
else if (navigator.appName == 'Microsoft Internet Explorer' && 
(event.button == 2 || event.button == 3)) {
//alert("SOLO BOTON IZQUIERDO");
return false;
}
return true;
}

document.onmousedown=right;
document.onmouseup=right;
if (document.layers) window.captureEvents(Event.MOUSEDOWN);
if (document.layers) window.captureEvents(Event.MOUSEUP);
window.onmousedown=right;
window.onmouseup=right;
//  End -->*/
</script>
<!-- InstanceBeginEditable name="doctitle" -->
<?
//para armar la lista de categorias
$cod=$_GET['cod'];
$consulta=$_POST['consulta'];
$mail=$_POST['mail'];
$array_fecha=getdate();
$fecha=strval($array_fecha['year'])."-".strval($array_fecha['mon'])."-".strval($array_fecha['mday']);
?>
<title>GRAVITAL BOARD HOUSE</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>
<body link="#0000FF" vlink="#0000FF" alink="#0000FF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
  <table width="930" height="600" border="0" align="center">
    <!--DWLayoutTable-->
    <tr> 
      <td height="104" colspan="3"><img src="banner_shop4.gif" width="920" height="150" border="0" usemap="#Map2"></td>
    </tr>
    <tr> 
      <td width="143" height="416" valign="top"><table width="143" border="0" cellpadding="0" cellspacing="0">
          <!--DWLayoutTable-->
          <tr> 
            <td width="143" height="24" valign="top"><form name="form1" method="post" action="shop_productos.php">
                <label> 
                <input name="criterio" type="text" id="textfield" size="8" maxlength="20">
                </label>
                <input type="submit" name="Submit2" value="buscar">
              </form></td>
          </tr>
          <tr> 
            <td height="20" valign="top"><strong><font color="#000000" size="3" face="Verdana, Arial, Helvetica, sans-serif">Productos</font></strong></td>
          </tr>
          <?
	while($categoria=mysql_fetch_array($categorias))
	{
?>
          <tr> 
            <td height="18" valign="top"><p><a style="text-decoration:none" href="shop_productos.php?cod_cat=<? echo $categoria['cod'];?>"><strong><font color="#666666" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                <?
                echo $categoria['nombre'];
                ?>
                </font></strong></a></p></td>
            <?
                if($_SESSION['pcategoria']=="SI")
                {
                	if($categoria['cod']==$_SESSION['cat_padre_activa'])
                    {
                		$subcategorias=mysql_query("SELECT * FROM categorias WHERE cod_padre=".$_SESSION['cat_padre_activa']." AND activa='S'",$link);
                		while($subcategoria=mysql_fetch_array($subcategorias))
                		{
?>
          <tr> 
            <td height="18" valign="top"><p><a style="text-decoration:none" href="shop_productos.php?cod_cat=<? echo $subcategoria['cod'];?>"><strong><font color="#666666" size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                <?
                            echo $subcategoria['nombre'];
    ?>
                </font></strong></a></p></td>
          </tr>
          <?
         				}
                 	  }
                   }
	}
	mysql_data_seek($categorias,0);
	
?>
        </table></td>
      <td width="601" valign="top"><!-- InstanceBeginEditable name="EditRegion3" --> 
        <div align="center"><strong> 
          <?
	if($mail=='' or $consulta=='')
	{
    	echo "ERROR: CAMPO VACIO, VOLVER ATRAS";
	}
	else
{	
	mysql_query("INSERT INTO consultas (consulta,mail,fecha,cod_producto) VALUES ('$consulta','$mail','$fecha','$cod')",$link);
	mail($mail,"Consulta de Producto","Te responderemos a la brevedad tu consulta:"." ".$consulta,"From:gravital@boardhouse.com.ar");
	mail("mibanez23@hotmail.com","GRAVITAL CONSULTA",$consulta,"From:".$mail);
	echo "Gracias por tu consulta, responderemos a tu mail a la brevedad";
	//boton o mandar a los productos
}
?>
          </strong></div>
        <!-- InstanceEndEditable --></td>
      <td width="172" valign="top"><img src="marcas.gif" width="169" height="377" border="0" usemap="#Map" /></td>
    </tr>
    <tr> 
      <td height="32" colspan="3" background="fondopie2.png"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>GRAVITAL 
          BOARD HOUSE ® | Rafael Nu&ntilde;ez 3491 | Cerro de las Rosas | Cordoba 
          | Argentina<br>
          0351-5988630 | gravital@boardhouse.com.ar</strong></font></div></td>
    </tr>
  </table>
</div>

<map name="Map"><area shape="rect" coords="5,7,110,26" href="shop_productos.php?marca=10">
  <area shape="circle" coords="146,23,21" href="shop_productos.php?marca=11">
  <area shape="rect" coords="11,32,105,56" href="shop_productos.php?marca=4">
  <area shape="rect" coords="8,61,51,110" href="shop_productos.php?marca=5">
  <area shape="rect" coords="62,58,115,112" href="shop_productos.php?marca=17">
  <area shape="rect" coords="123,63,164,102" href="shop_productos.php?marca=8">
  <area shape="rect" coords="8,123,101,158" href="shop_productos.php?marca=6">
  <area shape="rect" coords="7,168,79,193" href="shop_productos.php?marca=15">
  <area shape="rect" coords="4,201,88,217" href="shop_productos.php?marca=7">
  <area shape="rect" coords="98,203,164,246" href="shop_productos.php?marca=3">
  <area shape="rect" coords="3,223,56,296" href="shop_productos.php?marca=1">
  <area shape="rect" coords="130,317,164,372" href="shop_productos.php?marca=9">
  <area shape="rect" coords="79,320,122,369" href="shop_productos.php?marca=16">
  <area shape="rect" coords="2,306,67,333" href="shop_productos.php?marca=18">
  <area shape="rect" coords="63,253,165,278" href="shop_productos.php?marca=20">
  <area shape="rect" coords="1,341,70,370" href="shop_productos.php?marca=2">
  <area shape="rect" coords="75,281,162,309" href="shop_productos.php?marca=19">
</map>
<map name="Map2">
  <area shape="rect" coords="10,129,57,147" href="shop.php">
  <area shape="rect" coords="855,0,911,18" href="shop_contacto.php">
  <area shape="rect" coords="763,0,840,19" href="shop_comollegar.php">
  <area shape="rect" coords="653,0,745,18" href="shop_comocomprar.php">
  <area shape="rect" coords="119,1,173,19" href="shop_horarios.php">
  <area shape="rect" coords="14,0,105,17" href="shop_quienessomos.php">
</map>
</body>
<!-- InstanceEnd --></html>
