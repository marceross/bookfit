<?php

session_name('app_admin');
session_start();
include("conex.php");
include("local_controla.php"); // session_name(''); session_start(); se agrega si no está el controla
date_default_timezone_set('America/Argentina/Cordoba');
$array_fecha=getdate();
$fecha=strval($array_fecha['year']) ."/".strval($array_fecha['mon'])."/".strval($array_fecha['mday']);
$hora4=date("H:i:s");

if ($_SESSION['autentificado'] != "SI" || $_SESSION['tipo_usuario_act'] != 3 )
{
    header("Location:local_inicio.php");
    die();
}

if (isset($_POST['ac']))
{

if ($_POST['ac'] == "cambiar_asistencia")
{
    $dni = $_POST['dni'];
    $asiste = $_POST['asiste'];    
    $idhorario = $_POST['idhorario'];
    
    if ($asiste)
    {
        
        
        
        
        $query = mysqli_query($mysqli,"INSERT INTO actividad_reservas_asist (actividad_horarios_id_horario, registrados_dni, fecha, id_usuario) VALUES (" . $idhorario . ",'" . $dni . "','$fecha', ".$_SESSION['tipo_usuario_act'].")");
    }
    else
    {
        $query = mysqli_query($mysqli,"DELETE FROM actividad_reservas_asist WHERE actividad_horarios_id_horario ='$idhorario' AND registrados_dni = '$dni' AND fecha='$fecha'");
    }
    
    die("1");
}

}

if (isset($_GET['idhorario']))
{
    $enActividad = true;   
    $query = mysqli_query($mysqli,"SELECT * FROM actividad, actividad_horarios WHERE id_actividad=actividad_id_actividad AND id_horario = " . intval($_GET['idhorario']) . " LIMIT 1");
    
    //$query = mysqli_query($mysqli,"SELECT * FROM actividad, actividad_reservas, actividad_horarios WHERE (actividad_horarios_id_horario=id_horario AND actividad_id_actividad=id_actividad) AND fecha = CURDATE()");
// AND id_usuario 
    //SELECT * FROM actividad, actividad_reservas, actividad_horarios WHERE (actividad_horarios_id_horario=id_horario AND actividad_id_actividad=id_actividad) AND fecha = CURDATE() AND (hora>=ADDTIME(NOW(),'4:00:0.000000') OR hora<=ADDTIME(NOW(),'5:00:0.000000'))

    if (!mysqli_num_rows($query))
    {
        die("LA ACTIVIDAD NO EXISTE");
    }
    $fetch_actividad = mysqli_fetch_array($query);
    $actividad = $fetch_actividad["nombre"];
    $desc = $fetch_actividad["desc_especifica"];
    $hora=$fetch_actividad["hora"];
    //$query = mysqli_query($mysqli,"SELECT * FROM registrados WHERE actividad = " . $fetch_actividad['id_actividad'] . "  AND  mes_pagado  BETWEEN DATE_ADD(CURDATE(), INTERVAL -45 DAY) AND CURDATE() ORDER BY apellido ASC");
    $query = mysqli_query($mysqli,"SELECT * FROM registrados, actividad_reservas, actividad_horarios WHERE dni=registrados_dni AND id_horario=actividad_horarios_id_horario AND actividad_horarios_id_horario=".$_GET['idhorario']."  AND  actividad_reservas.fecha='$fecha' ORDER BY apellido ASC");
    
    while ($fetch_registrados = mysqli_fetch_array($query))
    {
        $alumnos[$fetch_registrados['dni']]['nombre'] = $fetch_registrados['nombre'];
        $alumnos[$fetch_registrados['dni']]['apellido'] = $fetch_registrados['apellido'];
        $alumnos[$fetch_registrados['dni']]['dni'] = $fetch_registrados['dni'];
        $query_asistencia = mysqli_query($mysqli,"SELECT * FROM actividad_reservas_asist WHERE registrados_dni = '" . $fetch_registrados['dni'] . "' AND actividad_horarios_id_horario = " . $fetch_actividad['id_horario'] . " AND fecha='$fecha'");
        if (mysqli_num_rows($query_asistencia))
        {
            $alumnos[$fetch_registrados['dni']]['asistencia'] = 1;
        }
        else
        {
            $alumnos[$fetch_registrados['dni']]['asistencia'] = 0;
        }
    }
}
else
{
    $query = mysqli_query($mysqli,"SELECT * FROM actividad, actividad_reservas, actividad_horarios WHERE (actividad_horarios_id_horario=id_horario AND actividad_id_actividad=id_actividad) AND fecha='$fecha'");
    
   
    
    
    while ($fetch_actividad = mysqli_fetch_array($query))
    {
        
        //print_r($fetch_actividad);
        
        
        if($fetch_actividad['id_usuario']==0)//verifica si id_usuario existe en la tabla actividades_reservas
        {
            $horarios[$fetch_actividad["id_horario"]]["id"] = $fetch_actividad["id_horario"];
            $horarios[$fetch_actividad["id_horario"]]["hora"] = $fetch_actividad["hora"];
            $horarios[$fetch_actividad["id_horario"]]["nombre"] = $fetch_actividad["nombre"];
            $horarios[$fetch_actividad["id_horario"]]["desc"] = $fetch_actividad["desc_especifica"];
            
            $horarios[$fetch_actividad["id_horario"]]["dni"] = $fetch_actividad["registrados_dni"];
        }
    }
    
}

?>

<style>
    


@media screen and (max-width: 767px) {
  .table td, .table th {
    padding: .25rem!important;
   font-size: 14px;
}
}
</style>

<!DOCTYPE html>
<html lang="es">
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--<meta name='viewport' content='width=device-width, initial-scale=0.8, maximum-scale=1.0' />-->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">    
        <title>LOKALES TRAINING SPOT | Control de asistencia</title>
        <link href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">    
        <script>
        function buscar_cliente(dni)
	    {	
		//alert("DNI: "+dni);
            $.ajax({			
                url: 'buscar_alumno.php',
                data: 'dni_cli=' + dni + '&idhorario=' + <?php echo $_GET['idhorario'];?>,
                success: function(resp) {				
                    if(resp!='no encontrado')
                    {					
                        $('#datos_alumno').html(resp);
                    
                    }
                    else
                    {					
                        alert("El Dni ingresado no existe");
                        
                    }
                }
            });
	    }

        function agregar_alumno(dni, idhorario)
	    {	
		//alert("DNI: "+dni);
            $.ajax({			
                url: 'agregar_alumno.php',
                data: 'dni_cli=' + dni + '&idhorario=' + idhorario,
                success: function(resp) {				
                    if(resp=="sin_credito")
                    {
                        alert("El alumno no tiene suficiente crédito");
                        
                    }
                    window.location.href="asiste.php?idhorario="+idhorario+"&dni="+dni;
                }
            });
	    }

        function permitir_confirmar(hora4, hora_con_margen2)
        {
            if(hora4<hora_con_margen2)
            {
                alert('SE PUEDE CONFIRMAR TERMINADA LA HORA');
            }
            else
            {
                location.href='asistencia_confirma.php?idhorario=<?php echo $_GET['idhorario'];?>';
            }
        }


        </script>
    </head>
    <body>
        <div class="container">
            <div class="head">
                
                <?php 
                    if(isset($actividad)){
                        
                    
                ?>
                
                
                
                <h5>Asistencia<?php echo ($actividad == "" ? "" : " | " . $actividad." ".date('H:i',strtotime($hora)));?></h5>
                
                <?php }else{?>
                <h5>Asistencia : </h5>
                <?php }?>
            </div>
            <div class="body">
                <?php
                if (isset($enActividad))
                {
                    ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <!--<th>Dni</th>-->
                                <th>Apellidos</th>
                                <th>Nombre</th>
                                <th>Asistencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             if($alumnos){ foreach ($alumnos as $alumno=>$id)
                            {
                                ?>
                            <tr id="tr_<?php echo $id["dni"];?>" <?php echo ($id["asistencia"] ? "class=\"asiste\"" : "");?>>
                                <!--<td><?php //echo $id["dni"];?></td>-->
                                <td><?php echo $id["apellido"];?></td>
                                <td><?php echo $id["nombre"];?></td>
                                <td>
                                    <?php
                                    if ($id["asistencia"])
                                    {
                                        ?>
                                        <button id="bt_<?php echo $id["dni"];?>" onclick="asistencia('<?php echo $id["dni"];?>',0,this);">Ha asistido</button>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <button id="bt_<?php echo $id["dni"];?>" onclick="asistencia('<?php echo $id["dni"];?>',1,this);">No ha asistido</button>
                                        <?php
                                    }
                                    ?>
                                    </td>
                            </tr>
                            <?php
                            }
                            ?>
                               <?php
                            }
                            ?>
                            <tr id="datos_alumno">
                                                         
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="dni_buscado" id="dni_buscado" class="form-control" required value="<?php //echo 
                                    $_GET['dni'];?>" >
                                    <button type="button" onclick="buscar_cliente(dni_buscado.value)" class="badge badge-info form-control">Buscar</button>
                                </td> 
                            </tr>
                        </tbody>
                    </table>
                </div>


                <!--<div>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>
                                <input type="text" name="dni_buscado" id="dni_buscado" class="form-control" required value="<?php echo $dni_nuevo;?>">
                                <button type="button" onclick="buscar_cliente(dni_buscado.value)" class="badge badge-info form-control">Buscar</button>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>-->



                <?php
                }
                else
                {
                    ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Descripcion</th>
                                <th>Horario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(isset($horarios)){
                            
                            foreach ($horarios as $horario=>$id)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $id["nombre"];?></td>
                                    <td><?php echo $id["desc"];?></td>
                                    <td><?php echo $id["hora"];?></td>
                                    <td>
                                        
                                        <?php 
                                        
                                        
                                            
                                            
                                            $temporales=mysqli_query($mysqli,"
                                            SELECT * FROM actividad_reservas_asist, usuarios WHERE actividad_reservas_asist.id_usuario=usuarios.id_usuario AND fecha=".$fecha." AND actividad_horarios_id_horario=".$id["id"]."  
                                            ");
                                        ?>
                                        
                                        
                                        
                                        <?php
                                        
                                        /*$temporales=mysqli_query($mysqli, "SELECT * FROM actividad_reservas_asist, usuarios WHERE actividad_reservas_asist.id_usuario=usuarios.id_usuario AND fecha='".$fecha."' AND actividad_horarios_id_horario='".$id["id"].'" AND actividad_reservas_asist.id_usuario<>'.$_SESSION['usuario_act'].');
*/
                                        if(mysqli_num_rows($temporales)==0)
                                        {
                                        ?>
                                            <a href="asiste.php?idhorario=<?php echo $id["id"];?>&dni=<?php echo $id["dni"];?>">GESTIONAR</a>
                                        <?php
                                        }
                                        else
                                        {
                                            $usu = mysqli_fetch_array($temporales);
                                            ?>
                                            <h5><span class="badge badge-light"><?php echo $usu['usuario'];?></span></h5>
                                            <?php
                                            /*
                                            $usu = mysqli_fetch_array($temporales);
                                            $usuario=mysqli_query($mysqli, "SELECT usuario FROM usuarios WHERE id_usuario=$usu");
                                            $nombre=mysqli_fetch_array($usuario);
                                            echo $nombre['usuario'];*/
                                            ?>
                                        <?php
                                        }
                                        ?>

                                    </td>
                                </tr>
                                <?php
                            }
                            }
                            ?>
                        </tbody>
                    </table>
                <!--</div>-->
                <div class="footer">
                    <button onclick="location.href='app_profe.php';">Volver</button>
                </div>
                <?php
                }
                ?>

                <?php
                    if(isset($enActividad))
                    {
                ?>
                         <div class="footer">
                            <button onclick="location.href='asiste.php';">Volver</button>
                            <?php
                            $segundos_horaInicial=strtotime($hora); 
                            $segundos_minutoAnadir=3600;
                            $hora_con_margen2=date("H:i",$segundos_horaInicial+$segundos_minutoAnadir);  
                            ?>
                            <button id="boton_confirmar" onclick="permitir_confirmar('<?php echo $hora4;?>', '<?php echo $hora_con_margen2;?>');">Confirmar</button>   
                        </div>
                <?php
                    }
                ?>
                </div>
            </div>
        </div>
           
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            <?php
            if (isset($enActividad))
            {
                ?>
            function asistencia(dni,asiste,button)
            {
                $(button).attr("disabled","disabled");
                $(button).html("cargando...");
                $.ajax({
                    timeout: 100000,
                    cache: false,
                    type:"post",
                    url:"asiste.php",
                    data: { ac: "cambiar_asistencia", dni: dni, asiste: asiste, idhorario: <?php echo $fetch_actividad['id_horario'];?>},
                    dataType: "text",
                    success:function(data) {            
                        if (data == "1")
                        {
                            if (asiste == "1")
                            {
                                $(button).removeAttr("disabled");
                                $(button).html("Ha asistido");
                                $("#tr_" + dni).addClass("asiste");                                
                                $(button).attr("onclick","asistencia('" + dni + "',0,this);");
                            }
                            else
                            {
                                $(button).removeAttr("disabled");
                                $(button).html("No ha asistido");
                                $("#tr_" + dni).removeClass("asiste");
                                $(button).attr("onclick","asistencia('" + dni + "',1,this);");
                            }
                        }
                        else
                        {
                            //OK
                        }
                    },
                    error:function(data) {
                        alert("Ha ocurrido un error en la petición");
                    }
                });
            }
            <?php
            }
            ?>
        </script>
    </body>
</html>