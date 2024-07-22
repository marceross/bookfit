<?php
	session_name('app_admin');
	session_start();
	include("conex.php");	
	include("local_controla.php");	
	//Obtiene la fecha y hora
	$array_fecha=getdate();
	$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
	$id_usuario=$_SESSION['tipo_usuario_act'];
	$mensajes=mysqli_query($mysqli,"SELECT * FROM mensajes_internos, mensajes_internos_destinatarios WHERE confirmado<>'S' AND mensajes_internos.id_mensaje=mensajes_internos_destinatarios.id_mensaje AND mensajes_internos_destinatarios.id_usuario='$id_usuario'");
	$cant_mensajes=mysqli_num_rows($mensajes);
	$categorias=mysqli_query($mysqli,"SELECT * FROM categorias ORDER BY nombre");
	$formas=mysqli_query($mysqli,"SELECT * FROM ventas_forma_pago ORDER BY nombre");
	
	
	
	$consultas=mysqli_query($mysqli,"SELECT * FROM consultas WHERE respuesta=''");
	$cant_consultas=mysqli_num_rows($consultas);
	$contactos=mysqli_query($mysqli,"SELECT * FROM contacto WHERE respuesta=''");
	$cant_contactos=mysqli_num_rows($contactos);
	//if(!isset($_GET['id_cat']))
	//{
		$productos=mysqli_query($mysqli,"SELECT productos.cod as cp, productos.nombre as np, costo, stock, descripcion, margen, marcas.nombre as nm FROM productos, categorias, marcas WHERE productos.id_marca=marcas.id_marca AND cod_cat=categorias.cod ORDER BY productos.nombre, marcas.nombre");
	//}
	/*else
	{
		$productos=mysqli_query("SELECT productos.cod as cp, productos.nombre as np, costo, margen FROM productos, categorias WHERE cod_cat=categorias.cod AND cod_cat=".$_GET['id_cat']." ORDER BY productos.nombre",$mysqli);	
	}*/
	//Vemos cuando reg hay en la tabla temporal
	$temporales=mysqli_query($mysqli,"SELECT * FROM ventas_temporal WHERE id_usuario='$id_usuario'");
	
	
	
	$cant_registros=mysqli_num_rows($temporales);
	
	
	
	$_SESSION['cant_items']=$cant_registros+1;
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

  <LINK href="https://lokales.com.ar/favico.ico" rel="shortcut icon">

  <script src="js/jquery-3.6.0.js"></script>
  <script src="js/bootstrap.min.js"  crossorigin="anonymous"></script>
<link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">
<style type="text/css">
body {
	
}
.top_nav li{display:inline-block;list-style:none;padding: 15px;}
</style>
</head>

<body>
     <div class="container-fluid" style="background-color: #A1A1A3;margin-bottom: 20px;">
     <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <ul class="top_nav">
                   
                    <li>
                       <a style="font-weight:bold;" href="app_profe.php">App Profe</a>
                    </li>
                    <li>
                       <a style="font-weight:bold;" href="asistencia.php">ASISTENCIA</a>
                    </li>
                    
                     <li>mensajes
                    <!--<a href="ver_mensajes.php">ver</a>-->
                    <a href="#">ver</a> 
          <?php echo "(".$cant_mensajes.")";?>
                    </li>
                    
                    <li>
                        <a href="local_kill_session.php" style="font-weight:bold">DESCONECTAR(para 
  cambiar de usuario) </a>
                    </li>
                </ul>
                
            </div>
            </div>
    </div>
    <div class="container" style="background:#F9F9FD">
        
       
        
<form action="local_procesa_ventas.php" method="post" enctype="multipart/form-data" name="form1" style="width:100%;margin:0 auto;box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);padding:20px;">
  
  <?php
	for($i=0;$i<$cant_registros+1;$i++)
	{
		mysqli_data_seek($categorias,0);
		mysqli_data_seek($productos,0);
		$temporal=mysqli_fetch_array($temporales);
		
		
?>
<div class="row">
  <div class="col-sm-6">
  Producto 
    <select name="producto[<?php echo $i;?>]" id="producto" class="form-control" >
      <?php
	while($producto=mysqli_fetch_array($productos))
	{
	    
	    echo $producto['cp'];
	    
		if($producto['cp']<>$temporal['cod_producto'])
		{
?>   
      <option value="<?php echo $producto['cp'];?>"><?php echo $producto['np']." ".$producto['nm']." ".$producto['descripcion']." ($".round ($producto['costo']*$producto['margen']).")"." ".$producto['stock'];?></option>
      <?php
		}
		else
		{
?>
      <option selected value="<?php echo $producto['cp'];?>"><?php echo $producto['np']." ".$producto['nm']." ".$producto['descripcion']." ($".round ($producto['costo']*$producto['margen']).")"." ".$producto['stock'];?></option>	
      <?php
		}            	
	}
?>        
    </select>
    </div>
    <div class="col-sm-6">
   
  <label>Cantidad</label>
  <input name="cant[<?php echo $i;?>]" type="text" class="form-control" id="cant" size="9" value="<?php if(isset($id_usuario)){ echo $id_usuario;}?>" required>
  
  </div>
  <?php
	}
?>  
  <div class="col-sm-6">
    <label>Agregar</label>
    <input type="submit" name="agregar" id="agregar" class="form-control"  value="agregar casillas">
    
  </div>
  <div class="col-sm-6">
      
      <label>forma de pago</label>
      
    <select name="pago" id="pago" class="form-control">
<?php
	while($forma=mysqli_fetch_array($formas))
	{
		if($forma['id_forma']<>$_SESSION['pago'])
		{
?>   
			<option value="<?php echo $forma['id_forma'];?>" ><?php echo $forma['nombre'];?></option>
<?php
		}
		else
		{
?>		
			<option selected="selected" value="<?php echo $forma['id_forma'];?>" ><?php echo $forma['nombre'];?></option>
<?php
		}            
	}
?>        
    </select>
   </div>
  <div class="col-sm-6"> <label>Efectivo  0000.00</label>
    <input name="efectivo" type="text" id="efectivo" class="form-control" size="9" value="<?php  if(isset($_SESSION['efectivo'])){ echo $_SESSION['efectivo'];}?>" required>
  </div>
  <div class="col-sm-6"><label>Recargo %</label> 
    <input name="recargo" type="text" id="recargo" class="form-control" size="9" value="<?php  
    if(isset($_SESSION['recargo'])){ echo $_SESSION['recargo'];}?>" required>
 </div>
   <div class="col-sm-6"><label>Descuento %</label>
    <input name="descuento" type="text" id="descuento" class="form-control" size="9" value="<?php 
    if(isset($_SESSION['descuento'])){ echo $_SESSION['descuento'];}?>" required>
    
    
    </div>
  <div class="col-sm-12" style="margin-top:20px">
    <input type="submit" name="terminar" class="btn btn-primary" value="Siguiente &gt;&gt;" id="terminar">
  </div>
  
  </div>
</form>



 <div class="row"><div class="col-sm-12" style="margin-top:15px;margin-bottom:15px">

 </div>
 </div>
<!--<p><a href="registrar.php">cargar cliente</a> 	<a href="registrados.php">ver cliente</a></p>
<p><a href="local_info.php">info local</a></p>-->
<form name="form2" method="post" action="local_procesa_cierrecaja.php" style="width:100%;margin:0 auto;box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);padding:20px;">
    <div class="row">
        <div class="col-sm-12">
  <strong>CIERRE CAJA</strong>
  </div>
  <div class="col-sm-6">
  <label>Billetes</label>
  <input name="billetes" type="text" id="billetes" class="form-control" size="5"required>
  </div>
  <div class="col-sm-6">
  <label>Monedas</label>
  <input name="monedas" type="text" id="monedas" class="form-control" size="5" required>
  </div>
  <div class="col-sm-12" style="margin-top:20px">
  <input name="enviar" type="submit" id="enviar" class="btn btn-primary" value="enviar" >
    </div>
  
  </div>
</form>

  
  </div>
</body>
</html>
