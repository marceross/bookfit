<?php
session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");
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

?>
<!DOCTYPE html>
<html lang="es">

<head>  
<title>Lokales Socio</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link href="estilo.css" rel="stylesheet" type="text/css">
<LINK href="https://lokales.com.ar/favico.ico" rel="shortcut icon">

<script src="js/jquery-3.6.0.js"></script>
<script src="js/bootstrap.min.js"  crossorigin="anonymous"></script>
<link rel="stylesheet" href="js/bootstrap.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<script>
//funcion para contar el costo total de las actividades seleccionadas y cambiar la condicion de los botones de hora

function calcular_costo(id_boton, id_horario, fecha_clase)
{   


  //alert("Me ejecuto");  
  if($('#'+id_boton).css('opacity')=='1')// verifica si la opacidad del botón es 1
  {    
    
    $('#'+id_boton).css('opacity', '0.6');
    partes=id_horario.split("_");
    $('#'+id_horario).val(partes[1]+'_1');
  }
  else
  {    
    $('#'+id_boton).css('opacity', '1');
    partes=id_horario.split("_");
    $('#'+id_horario).val(partes[1]+'_0');
  }   
}

</script>
</head>

<body>
<br><br>
<?php
//echo "Hoy es " . date("d.m.Y") . "<br>";
//echo "La hora es " . date("h:i:sa");
//exit();
?>

<div class="container">
  <div class="row justify-content-center">
    <!--<div class="col-6">-->
      <h3 class="card-title"><span class="badge badge-pill badge-light"><?php echo $act['nombre']; ?></span></h3>
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
      //echo date_format($fecha_evaluar, 'Y-m-d');
      date_add($fecha_evaluar, date_interval_create_from_date_string("7 day"));
      //echo date_format($fecha_evaluar, 'Y-m-d');
      //exit();
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
      date_add($fecha_evaluar, date_interval_create_from_date_string("7 day"));
      for($i=0;$i<sizeof($orden_dias);$i++) //calcula el tamano de 6, 0, 1, 2, 3, 4, 5
      {
          $numero_dia_mes=date('d', strtotime(date_format($fecha_evaluar,"Y-m-d"))); 
          $fecha_clase=date_format($fecha_evaluar,"Y-m-d");
          $dias_actividades=mysqli_query($mysqli, "SELECT * FROM actividad_horarios, actividad_dias WHERE actividad_dias_id_dia=".$orden_dias[$i]." AND actividad_dias_id_dia=id_dia AND actividad_id_actividad='$id_actividad' GROUP BY actividad_dias_id_dia");
          
          
          
          
          $dia_actividad=mysqli_fetch_array($dias_actividades);
          
          if($dia_actividad){
          
          $actividad_dias_id_dia=$dia_actividad['id_dia'];
          
          
          
          $horarios=mysqli_query($mysqli, "SELECT * FROM actividad_horarios WHERE actividad_id_actividad='$id_actividad' AND actividad_dias_id_dia=".$actividad_dias_id_dia." ORDER BY hora");
          
      ?>
      <!-- DIV DE LAS COLUMNAS DIASSSSSSSSS---------------------------------------------->
      <div class="col vert_line flex-grow-0">
        <?php
        if($dia_actividad['nombre_dia']<>'')
        {
        ?>
          <h6><span class="badge badge-light"><?php echo mb_substr($dia_actividad['nombre_dia'],0,3);?></span><span class="badge badge-pill badge-dark"><?php echo $numero_dia_mes;?></span></h6>
       <?php
        }
        else
        {
          $nombres_dias=mysqli_query($mysqli,"SELECT * FROM actividad_dias WHERE id_dia=".$orden_dias[$i]);          
          $nombre_dia=mysqli_fetch_array($nombres_dias);          
        ?>
          <h6><span class="badge badge-light"><?php echo mb_substr($nombre_dia['nombre_dia'],0,3);?></span><span class="badge badge-pill badge-dark"><?php echo $numero_dia_mes;?></span></h6>
        <?php
        }
        ?>
        <?php
        /////////////////////////////////////////////pinta color de boton de acuerdo al cupo
        while($horario=mysqli_fetch_array($horarios))
        {
          

          //quedamos acá//////////////////////////////////($cant_canceladas<>1)
          //echo "SELECT * FROM actividad_horarios_susp WHERE actividad_horarios_id_horario2=".$horario['id_horario']." AND fecha='$fecha'"."<br>";

          $canceladas=mysqli_query($mysqli, "SELECT * FROM actividad_horarios_susp WHERE actividad_horarios_id_horario2=".$horario['id_horario']." AND fecha='$fecha_clase'");
          $cant_canceladas=mysqli_num_rows($canceladas);         

          if($cant_canceladas<>1)
          {
            $clase_boton="btn-success";
            $boton_habilitado=1;
          }
          else
          {
              $clase_boton="btn-danger";
              $boton_habilitado=0;  
          }

          $fecha_c=strtotime($fecha_clase);
          $fecha_a=strtotime($fecha);
          
        //echo  "Fecha c: ".$fecha_c;
        //echo "<br>Fecha a: ".$fecha_a;

          if($hora4>$horario['hora'] and $fecha_c==$fecha_a)
          {
            $boton_habilitado=0;
          }
        ?>
        <form action="app_cancela_horarios_procesa.php" method="post" enctype="multipart/form-data">
        <div>
          <div>

          <input type="hidden" id="horario_<?php echo $horario['id_horario'];?>" name="id_horario[]" value="<?php echo $horario['id_horario'];?>_0">

          <input type="hidden" id="fecha_clase_<?php echo $horario['id_horario'];?>" name="fecha_clase[]" value="<?php echo $fecha_clase;?>">
                  

          <!---------------------BOTON DE HORARIOS DE CLASES Y COSTO------------------------------------->
          <button name="boton_<?php echo $horario['id_horario'];?>" id="boton_<?php echo $horario['id_horario'];?>" onClick="calcular_costo(this.id, 'horario_<?php echo $horario['id_horario'];?>', '<?php echo $fecha_clase;?>')" 
          type="button" class="btn <?php echo $clase_boton; ?> boton_horario btn-sm"><?php echo date('H:i',strtotime($horario['hora']));?> <span class="badge badge-light">
          <?php echo $horario['costo'];?>
          </span>
          <?php
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

          <?php
            if($boton_habilitado==0)
            {
          ?>
              <script>
                $('#boton_<?php echo $horario['id_horario'];?>').attr('disabled', 'true');
              </script>
          <?php
            }
          ?>

        <?php
          }
          ?>
        </div>
        <?php
          date_add($fecha_evaluar, date_interval_create_from_date_string("1 day"));
          
      }
        }
        ?>

</div>

      <div class="row justify-content-center row_boton">
              <input type="submit" value="Confirmar" class="btn btn-primary" id="boton_confirmar">
          </div>
      </div>
    </form><br><br>


</body>
</html>
