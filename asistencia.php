<?php
include("conex.php");
session_start(); 

/*
if ($_SESSION['autentificado'] != "SI" || ($_SESSION['tipo_usuario_act'] != 1 && $_SESSION['tipo_usuario_act'] != 2) )
{
    header("Location:local_inicio.php");
    die();
}
*/

if (isset($_POST['ac']) == "cambiar_asistencia")
{
    $dni = $_POST['dni'];
    $asiste = $_POST['asiste'];
    $idactividad = intval($_POST['idactividad']);
    
    if ($asiste)
    {
        $query = mysqli_query($mysqli,"INSERT INTO actividad_asistencia (actividad, dni, fecha) VALUES (" . $idactividad . ",'" . $dni . "',CURDATE())");
    }
    else
    {
        $query = mysqli_query($mysqli,"DELETE FROM actividad_asistencia WHERE actividad = " . $idactividad . " AND dni = '" . $dni . "' AND fecha = CURDATE()");
    }
    
    die("1");
}

if (isset($_GET['idactividad']))
{
    $enActividad = true;   
    $query = mysqli_query($mysqli,"SELECT * FROM actividad WHERE id_actividad = " . intval($_GET['idactividad']) . " LIMIT 1");
    if (!mysqli_num_rows($query))
    {
        die("LA ACTIVIDAD NO EXISTE");
    }
    $fetch_actividad = mysqli_fetch_array($query);
    $actividad = $fetch_actividad["nombre"];

    $query = mysqli_query($mysqli,"SELECT * FROM registrados WHERE actividad = " . $fetch_actividad['id_actividad'] . " AND mes_pagado  BETWEEN DATE_ADD(CURDATE(), INTERVAL -45 DAY) AND CURDATE() ORDER BY apellido ASC");
    while ($fetch_registrados = mysqli_fetch_array($query))
    {
        $alumnos[$fetch_registrados['dni']]['nombre'] = $fetch_registrados['nombre'];
        $alumnos[$fetch_registrados['dni']]['apellido'] = $fetch_registrados['apellido'];
        $alumnos[$fetch_registrados['dni']]['dni'] = $fetch_registrados['dni'];
        $query_asistencia = mysqli_query($mysqli,"SELECT * FROM actividad_asistencia WHERE dni = '" . $fetch_registrados['dni'] . "' AND actividad = " . $fetch_actividad['id_actividad'] . " AND fecha = CURDATE() LIMIT 1");
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
    $query = mysqli_query($mysqli,"SELECT * FROM actividad WHERE activa='s' ORDER BY nombre");
    while ($fetch_actividad = mysqli_fetch_array($query))
    {
        $actividades[$fetch_actividad["id_actividad"]]["id"] = $fetch_actividad["id_actividad"];
        $actividades[$fetch_actividad["id_actividad"]]["nombre"] = $fetch_actividad["nombre"];
        
        
        $actividad = $fetch_actividad["nombre"];
         $enActividad = false;   
    }
    
}

?>
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
    </head>
    <body>
        <div class="container">
            <div class="head">
                <h1>Control de asistencia<?php echo ($actividad == "" ? "" : " | " . $actividad);?></h1>
            </div>
            <div class="body">
                <?php
                if ($enActividad)
                {
                    ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Apellidos</th>
                                <th>Nombre</th>
                                <th>Cambiar Asistencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             if(isset($alumnos)){ foreach ($alumnos as $alumno=>$id)
                            {
                                ?>
                            <tr id="tr_<?php echo $id["dni"];?>" <?php echo ($id["asistencia"] ? "class=\"asiste\"" : "");?>>
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
                        </tbody>
                    </table>
                </div>
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
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($actividades as $actividad=>$id)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $id["nombre"];?></td>
                                    <td><a href="asistencia.php?idactividad=<?php echo $id["id"];?>">GESTIONAR</a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                    <?php
                }
                ?>
            </div>
            <div class="footer">
                <?php
                if ($enActividad)
                {
                    ?>
                    <button onclick="location.href='asistencia.php';">Volver</button>
                    <?php
                }
                ?>
            </div>
        </div>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            <?php
            if ($enActividad)
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
                    url:"asistencia.php",
                    data: { ac: "cambiar_asistencia", dni: dni, asiste: asiste, idactividad: <?php echo $fetch_actividad['id_actividad'];?>},
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