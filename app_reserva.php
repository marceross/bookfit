<?
session_name('app_reservas');
session_start();
include("conex.php");
include("biblioteca.php");
date_default_timezone_set('America/Argentina/Cordoba'); //-3
//date_default_timezone_set('Pacific/Honolulu'); //-10
//date_default_timezone_set('Pacific/Auckland'); // +12
//$actividades=mysqli_query($mysqli,"SELECT * FROM actividad ORDER BY nombre");
$_SESSION['actividad_sel']=$id_actividad=$_GET['act_seleccionada'];
$_SESSION['procedencia']='app_reserva.php';
$actividad=mysqli_query($mysqli,"SELECT * FROM actividad WHERE id_actividad='$id_actividad'");
$act=mysqli_fetch_array($actividad);

if(isset($_SESSION['usuario_act']))
{ 
  //Buscamos el credito del usuario
	$datos_usuarios=mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='".$_SESSION['usuario_act']."'");
  $dato_usuario=mysqli_fetch_array($datos_usuarios);
  $credito_actual=$dato_usuario['credito'];
  $logueado="S";
}
else
{
  $credito_actual=0;
  $logueado="N";
}

// verifico si los puntos estan vencidos
if(strtotime($dato_usuario['vencimiento'])<strtotime($fecha))
{
  $clase_vencido="badge-danger";
}
else
{
  $clase_vencido="badge-info";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>  
<title>Lokales Socio</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="https://www.lokales.com.ar/favico.ico" rel="shortcut icon">

<script src="js/jquery-3.6.0.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<script>
//funcion para contar el costo total de las actividades seleccionadas y cambiar la condicion de los botones de hora

function armar_descripcion()
{
  //Armamos la descripcion
  desc_especifica='';

  $(".boton_horario").each(function(idx, el) {
    //alert('Desa: '+$(el).attr('disabled'));
    
    if($(el).css('opacity')!='1' && $(el).attr('disabled')!='disabled')
    {
      resultado=$(el).attr('id').split('_');
      desc_especifica=desc_especifica+"<li>"+$('#desc_especifica_'+resultado[1]).val()+"</li>";
    }
  });
  if(desc_especifica!='')
  {    
    $('#contenedor_descrip').addClass("badge-info");
    $('#contenedor_descrip').addClass("detalle");
  }
  else
  {   
    $('#contenedor_descrip').removeClass("badge-info");
    $('#contenedor_descrip').removeClass("detalle");
  }

  $('#descrip_especifica').html(desc_especifica);
}

function calcular_costo(valor_costo, id_boton, id_horario, credito_actual, logueado, fecha_clase)
{   


  //alert("Me ejecuto");  
  if($('#'+id_boton).css('opacity')=='1')// verifica si la opacidad del botón es 1
  {    
    total_costo=parseInt($('#total_costo').val())+parseInt(valor_costo);//suma puntos al seleccionar   
    $('#total_costo').val(total_costo);
    $('#'+id_boton).css('opacity', '0.6');
    partes=id_horario.split("_");
    $('#'+id_horario).val(partes[1]+'_1');
  }
  else
  {    
    total_costo=parseInt($('#total_costo').val())-parseInt(valor_costo);//resta puntos, quita seleccion
    $('#total_costo').val(total_costo);
    $('#'+id_boton).css('opacity', '1');
    partes=id_horario.split("_");
    $('#'+id_horario).val(partes[1]+'_0');
  }

  if((logueado=='S' && total_costo>credito_actual) || total_costo==0)
  {
    //si logueado y no tiene credito
    if(total_costo>0)
    {
      $('#mensaje_credito').css('display', 'block');//mensaje crédito insuficiente
    }
    $('#boton_confirmar').attr('disabled', 'true');//deshabilita boton de confirmar
  }
  else
  {
    $('#mensaje_credito').css('display', 'none');
    if(total_costo>0)
    {
      $('#boton_confirmar').removeAttr("disabled");
    }
    else
    {
      $('#boton_confirmar').attr('disabled', 'true');//deshabilita boton de confirmar
    }
  }

  if($('#'+id_boton).hasClass("btn-info"))
    { 
      if(total_costo>credito_actual)
      {
        $('#total_costo_modal_no_credit').val(valor_costo);
        $('#boton_abrio_no_credit').val(id_boton);
        $('#horario_modal_no_credit').val(partes[1]+'_1');
        $('#fecha_clase_modal_no_credit').val(fecha_clase);
        $("#ventana_invitacion_no_credit").modal("show");//Llamamos a la ventana modal para invitar     
      }
      else
      {
        $('#total_costo_modal').val(valor_costo);
        $('#boton_abrio').val(id_boton);
        $('#horario_modal').val(partes[1]+'_1');
        $('#fecha_clase_modal').val(fecha_clase);
        $("#ventana_invitacion").modal("show");//Llamamos a la ventana modal para invitar   
      }
      
    }
  
      armar_descripcion();
  }

function buscar_cliente(dni)// busca el cliente de la ventana modal
	{	
		//alert("DNI: "+dni);
		$.ajax({			
			url: 'buscar_cliente_invitacion.php',
			data: 'dni_cli=' + dni,
			success: function(resp) {
				$('#cliente_encontrado').html(resp)

			}
		});
	}

</script>
</head>

<body>
<br><br>
<?
//echo "Hoy es " . date("d.m.Y") . "<br>";
//echo "La hora es " . date("h:i:sa");
//exit();
?>

    <!--------------------VENTANA MODAL INVITACION---------------------------->
<div class="modal fade" id="ventana_invitacion">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  
                </div>
              <div class="modal-body">                  
                <form action="login_inscripcion_procesa.php?invitado=1" method="post" enctype="multipart/form-data" class="form-group">
                <h4 class="modal-title">Datos de invitado</h4>
                  <label>DNI</label>

                  <!--
                  <input type="text" name="dni" id="dni" class="form-control">-->

                  <input type="text" name="dni" id="dni" onkeyup="buscar_cliente(this.value)" class="form-control" pattern="[0-9]+" required>                 
                  <!--<input type="nombre" name="nom" id="nom" class="form-control">--> 
                  <div id="cliente_encontrado">
                    <label>Nombre</label>
                    <input type="nombre" name="nom" id="nom" value="" class="form-control" required>
                    <label>Mail</label>
                    <input type="email" name="mai" id="mai" class="form-control" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                  </div>

                  <div class="modal-footer">
                  <h6 class="card-subtitle mb-2 text-muted">costo:
                  
                  <input type="text" readonly value="0" id="total_costo_modal" name="total_costo_modal" class="form-control col-5"> puntos

                  </h6>

                  <input type="hidden" id="boton_abrio" value="0">

                  <input type="hidden" id="horario_modal" name="id_horario[]" value="0">

                  <input type="hidden" id="fecha_clase_modal" name="fecha_clase[]" value="">

                  <input type="submit" value="Enviar" class="btn btn-primary" id="boton_enviar">
                  </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!------------------------------------------------>



    <!---------------------VENTANA MODAL NO CREDITO--------------------------->
    <div class="modal fade" id="ventana_invitacion_no_credit">
      <div class="modal-dialog modal-sm">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  
                </div>
              <div class="modal-body">                  
                <form action="login_inscripcion_procesa.php?invitado=1" method="post" enctype="multipart/form-data" class="form-group">
                <h4 class="modal-title"><span class="badge badge-danger">Faltan puntos</span></h4>
                  <div class="modal-footer">
                  <h6 class="card-subtitle mb-2 text-muted">costo:
                  
                  <input type="text" readonly value="0" id="total_costo_modal_no_credit" class="form-control col-5"> puntos

                  </h6>

                  <input type="hidden" id="boton_abrio_no_credit" value="0">

                  <input type="hidden" id="horario_modal_no_credit" value="0">

                  <input type="hidden" id="fecha_clase_modal_no_credit" value="">

                  
                  </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!------------------------------------------------>



    <header>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
      <a class="navbar-brand" href="app_actividades.php"><img src="logo.gif" class="img-fluid" alt="Lokales Training spot logo image" width="200"></a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
        <?
        if(!isset($_SESSION['usuario_act']))
        {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="login.html">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login_inscripcion.html">Registrarte</a>
          </li>
        <?
        }
        else
        {
        ?>
          <li class="nav-item">
          <h6><a class="nav-link"><span class="badge badge-pill badge-light"><? echo $dato_usuario['nombre'];?></span><span class="badge badge-pill badge-dark"><? echo $credito_actual;?></span> puntos</a></h6>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
          </li>
        <?
        }
        ?>
        </ul>
        <!--
        <form class="form-inline mt-2 mt-md-0" method="post" action="app_reserva_procesa.php" enctype="multipart/form-data">
          <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Buscar">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
        -->
      </div>
    </nav>
</header>

<div class="container">
  <div class="row justify-content-center">
    <!--<div class="col-6">-->
      <h3 class="card-title"><span class="badge badge-pill badge-light"><? echo $act['nombre']; ?></span></h3>
    <!--</div>-->
  </div> 
    <div class="row justify-content-center">
      <!--<div class="col-6">-->
            <!--<h6 class="grande"><span class="badge badge-success">Hay lugar</span></h6>-->
            <h6 class="grande"><span class="badge badge-warning">Poco lugar</span></h6>
            <h6 class="grande"><span class="badge badge-danger">No hay</span></h6>
            <h6 class="grande"><span class="badge badge-info">Invitar</span></h6>
            <h6 class="grande"><span class="badge badge-dark">clase técnica</span></h6>

      <!--</div>-->
    </div>

    <?php  
      //Obtiene la fecha y hora, para ver si puede reservar o no... puede avisar que por la hora no podra cancelar...
	    $array_fecha=getdate();
	    $fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
	    //$hora=strval($array_fecha['hours']).":".strval($array_fecha['minutes']);
      $hora4=date("H:i:s");

      //$hora4='23:00:00';
      //echo "La hora es " . $hora4;

      //$fecha_final=$fecha+7;
      $dias_fecha='';
      $fecha_evaluar = date_create($fecha);
      
      for($i=0;$i<=6;$i++)
      {        
        $numero_dia=date('w', strtotime(date_format($fecha_evaluar,"Y-m-d"))); // w extrae el dia de la semana, con numero
        $dias_fecha=$dias_fecha.$numero_dia;
        if($i<6)
        {
          $dias_fecha=$dias_fecha.", ";
        }
        date_add($fecha_evaluar, date_interval_create_from_date_string("1 day"));
        //echo date_format($fecha_evaluar,"d-m-Y")."<br>";
      } 
     // echo $dias_fecha;
      $orden_dias=explode(",",$dias_fecha);//funcion que toma cadena de caracteres y divide tomando como base un caracter 6, 0, 1, 2, 3, 4, 5
     ?>
    <!-- DIV DE LA FILA DE DIASSSSSSSSS---------------------------------------------->
    <div class="row hori_line justify-content-center">
      <?php
      $fecha_evaluar = date_create($fecha);
      for($i=0;$i<sizeof($orden_dias);$i++) //calcula el tamano de 6, 0, 1, 2, 3, 4, 5
      {
          $numero_dia_mes=date('d', strtotime(date_format($fecha_evaluar,"Y-m-d"))); 
          $fecha_clase=date_format($fecha_evaluar,"Y-m-d");
          $dias_actividades=mysqli_query($mysqli, "SELECT * FROM actividad_horarios, actividad_dias WHERE actividad_dias_id_dia=".$orden_dias[$i]." AND actividad_dias_id_dia=id_dia AND actividad_id_actividad='$id_actividad' AND activo='S' GROUP BY actividad_dias_id_dia");
          $dia_actividad=mysqli_fetch_array($dias_actividades);
          $actividad_dias_id_dia=$dia_actividad['id_dia'];
          $horarios=mysqli_query($mysqli, "SELECT * FROM actividad_horarios WHERE actividad_id_actividad='$id_actividad' AND actividad_dias_id_dia=".$actividad_dias_id_dia." AND activo='S' ORDER BY hora");
          
      ?>
      <!-- DIV DE LAS COLUMNAS DIASSSSSSSSS---------------------------------------------->
      <div class="col vert_line flex-grow-0">
        <?
        if($dia_actividad['nombre_dia']<>'')
        {
        ?>
          <h6><span class="badge badge-light"><? echo mb_substr($dia_actividad['nombre_dia'],0,3);?></span><span class="badge badge-pill badge-dark"><? echo $numero_dia_mes;?></span></h6>
       <?
        }
        else
        {
          $nombres_dias=mysqli_query($mysqli,"SELECT * FROM actividad_dias WHERE id_dia=".$orden_dias[$i]);          
          $nombre_dia=mysqli_fetch_array($nombres_dias);          
        ?>
          <h6><span class="badge badge-light"><? echo mb_substr($nombre_dia['nombre_dia'],0,3);?></span><span class="badge badge-pill badge-dark"><? echo $numero_dia_mes;?></span></h6>
        <?
        }
        ?>
        <?
        /////////////////////////////////////////////pinta color de boton de acuerdo al cupo
        while($horario=mysqli_fetch_array($horarios))
        {
          $reservas=mysqli_query($mysqli, "SELECT COUNT(actividad_horarios_id_horario) AS cant_reservas FROM actividad_reservas WHERE actividad_reservas.fecha>='$fecha' AND actividad_horarios_id_horario=".$horario['id_horario']);
          $reserva=mysqli_fetch_array($reservas);
          $canceladas=mysqli_query($mysqli, "SELECT * FROM actividad_horarios_susp WHERE actividad_horarios_id_horario2=".$horario['id_horario']." AND fecha='$fecha_clase'");
          $cant_canceladas=mysqli_num_rows($canceladas);  
          if(isset($_SESSION['usuario_act']))
          {            
            //Verificamos si el usuario ya tiene reservado este horario
            $reservas2=mysqli_query($mysqli, "SELECT COUNT(actividad_horarios_id_horario) AS cant_reservas FROM actividad_reservas WHERE actividad_reservas.fecha>='$fecha' AND actividad_horarios_id_horario=".$horario['id_horario']." AND registrados_dni='".$_SESSION['usuario_act']."'");          
            $reserva2=mysqli_fetch_array($reservas2);
            $cant_reservas_usuario=$reserva2['cant_reservas'];           
           
          }
          else
          {
            $cant_reservas_usuario=0;
          }
          $porcentaje_ocupacion=($reserva['cant_reservas']*100)/$horario['cupo'];
          if($porcentaje_ocupacion<70)
          {
            $clase_boton="btn-success";
            $boton_habilitado=1;
          }
          else
          {
            if($porcentaje_ocupacion<100)
            {
              $clase_boton="btn-warning";
              $boton_habilitado=1;
            }
            else
            {
              $clase_boton="btn-danger";
              $boton_habilitado=0;
            }
          }
          if($cant_reservas_usuario==1)
          {
              $clase_boton="btn-info";
              $boton_habilitado=1;
          }  
          if($cant_canceladas==1)
          {
              $clase_boton="btn-danger";
              $boton_habilitado=0;  
          }

          $fecha_c=strtotime($fecha_clase);
          $fecha_a=strtotime($fecha);
          
        //echo  "Fecha c: ".$fecha_c;
        //echo "<br>Fecha a: ".$fecha_a;

        //POSIBILIDAD DE RESERVAR HORARIO POR HORA PASADA, 15 minutos
        $segundos_horaInicial=strtotime($horario['hora']); 
        $segundos_minutoAnadir=900;
        $hora_con_margen=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);       

          if($hora4>$hora_con_margen and $fecha_c==$fecha_a)
          {            
            $boton_habilitado=0;
          }
        ?>
        <form action="app_reserva_procesa.php" method="post" enctype="multipart/form-data">
        <div>
          <div>

          <input type="hidden" id="horario_<? echo $horario['id_horario'];?>" name="id_horario[]" value="<? echo $horario['id_horario'];?>_0">

          <input type="hidden" id="fecha_clase_<? echo $horario['id_horario'];?>" name="fecha_clase[]" value="<? echo $fecha_clase;?>">

          <input type="hidden" id="costo_<? echo $horario['id_horario'];?>" name="costo[]" value="<? echo $horario['costo'];?>">

          <input type="hidden" id="desc_especifica_<? echo $horario['id_horario'];?>" name="desc_especifica[]" value="<? echo $horario['desc_especifica'];?>" class="descripciones">

          <!---------------------BOTON DE HORARIOS DE CLASES Y COSTO------------------------------------->
          <button name="boton_<? echo $horario['id_horario'];?>" id="boton_<? echo $horario['id_horario'];?>" onClick="calcular_costo(costo_<? echo $horario['id_horario'];?>.value, this.id, 'horario_<? echo $horario['id_horario'];?>', <? echo $credito_actual;?>, '<? echo $logueado;?>', '<? echo $fecha_clase;?>')" 
          type="button" class="btn <? echo $clase_boton; ?> boton_horario btn-sm"><? echo date('H:i',strtotime($horario['hora']));?> <span class="badge valorpunto">
          <? echo $horario['costo'];?>
          </span>
          <?
          /*if(isset($_SESSION['usuario_act']))
          {
          ?>
          <i class="bi bi-info-square-fill"></i>
          <?
          }*/
          ?>
          </button>
          </div>
        </div>

          <?
            if($boton_habilitado==0)
            {
          ?>
              <script>
                $('#boton_<? echo $horario['id_horario'];?>').attr('disabled', 'true');
              </script>
          <?
            }
          ?>

        <?php
          }
          ?>
        </div>
        <?php
          date_add($fecha_evaluar, date_interval_create_from_date_string("1 day"));
        }
        ?>

</div>

      <div class="row justify-content-center row_boton">
          <div class="col-auto">
          <h6 class="card-subtitle mb-2 text-muted">Vas a reservar</h6>
          <div id="contenedor_descrip">
            <ul id="descrip_especifica">
            </ul>
          </div>
              <h6 class="card-subtitle mb-2 text-muted">costo: <input type="text" readonly value="0" id="total_costo" name="total_costo" class="form-control"> puntos</h6>
              <div id="mensaje_credito" style="display:none;"><span class="badge badge-danger">Faltan puntos</span></div>
              <input type="submit" value="Confirmar" class="btn btn-primary" id="boton_confirmar" disabled>
          </div>
      </div>
    </form>
    
    <h6 class="grande"><span class="badge badge-pill badge-info"><? echo $credito_actual;?></span> puntos <span class="badge badge-pill <? echo $clase_vencido; ?>"><? echo formato_latino ($dato_usuario['vencimiento']);?></span> vencimiento</h6>
    
    <br><br>


<!--------------------CIERRE VENTANA MODAL---------------------------->
<script>
  $("#ventana_invitacion").on("hidden.bs.modal", function () {    
    total_costo_actualizado=parseInt($('#total_costo').val())-parseInt($('#total_costo_modal').val());      
    $('#total_costo').val(total_costo_actualizado);

    id_boton_abrio=$('#boton_abrio').val();
    $('#'+id_boton_abrio).css('opacity', '1');

    armar_descripcion();   

    partes=$('#horario_modal').val().split("_");    

    //Vuelve a cero el value de un campo oculto del formulario donde están los botones de reserva e invitacion
    $('#horario_'+partes[0]).val(partes[0]+'_0');//LOS BOTONES! NO LA VENTANA MODAL

    //alert("Valor horario modal: "+$('#horario_modal').val());
    
    $('#horario_modal').val(partes[0]+'_0');
    //$('#horario_modal').val('0');

    //alert("Valor horario modal: "+$('#horario_modal').val());

    $('#boton_confirmar').attr('disabled', 'true');
      
    <? 
    unset($_SESSION['invitacion']); 
    unset($_SESSION['id_horario_guardados']);
    ?>
    
  });


  $("#ventana_invitacion_no_credit").on("hidden.bs.modal", function () {    
    total_costo_actualizado=parseInt($('#total_costo').val())-parseInt($('#total_costo_modal_no_credit').val());        
    $('#total_costo').val(total_costo_actualizado);

    id_boton_abrio=$('#boton_abrio_no_credit').val();
    $('#'+id_boton_abrio).css('opacity', '1');    
    
    if(total_costo_actualizado<=<? echo $credito_actual;?>)
    {
      $('#mensaje_credito').css('display', 'none');
      $('#boton_confirmar').removeAttr("disabled");
    }
    armar_descripcion();

    partes=$('#horario_modal_no_credit').val().split("_");    

    //Vuelve a cero el value de un campo oculto del formulario donde están los botones de reserva e invitacion
    $('#horario_'+partes[0]).val(partes[0]+'_0'); //LOS BOTONES! NO LA VENTANA MODAL

    $('#horario_modal_no_credit').val(partes[0]+'_0');

    $('#boton_confirmar').attr('disabled', 'true');

    <? 
    unset($_SESSION['invitacion']); 
    unset($_SESSION['id_horario_guardados']);
    ?>
    
  });
</script>
    
<footer class="fixed-bottom bg-light">
      <div class="row">
        <div class="col">
        <a href="app_actividades.php"><img src="svg/svgbike.svg" class="barra" alt="Actividades"></a>
        <br><h6 class="chica">Actividades</h6>
        </div>
        <div class="col">
        <a href="app_agenda.php"><img src="svg/svghora.svg" class="barra" alt="Agenda"/></a>
        <br><h6 class="chica">Agenda</h6>
        </div>
        <!--<div class="col">
        <a href="app_mensajes.php"><img src="svg/svgmessage.svg" class="barra" alt="Mensajes"/></a>
        <br><h6 class="chica">Mensajes</h6>
        </div>-->
        <div class="col">
        <a href="app_perfil.php"><img src="svg/svgsettings-brain.svg" class="barra" alt="Ajustes"/>
        <br></a><h6 class="chica">Ajustes</h6>
        </div>
      </div>
</footer>

<script>
$(document).ready(function(){						
   //Pintamos valor de los puntos si hay clase o no
   //jquery
    $('span.valorpunto').each(function(idx, el) {        
        valor=$(el).html().replace("$", "");        
        if(parseFloat(valor)<4)// comprueba si en valor es positivo o negativo
        {
            //$(el).removeClass('badge-success');
            $(el).addClass('badge-light');
            //alert('El elemento ' + idx + 'es negativo');
        }
        else
        {
            //$(el).removeClass('badge-danger');
            $(el).addClass('badge-dark');
            //alert('El elemento ' + idx + 'es positivo');
        }
    });
});
</script>

</body>
</html>

