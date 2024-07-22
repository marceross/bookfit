<?php
include 'conex.php';
session_start();
$actividad = '';
$enActividad = false;
$AverageActivity = false;
$Revenue_per_session = false;
$Revenue_per_client = false;
$square_foot = false;
$out_term = false;
$lead = false;
$conversion = false;
$gross_profit = false;
$retention = false;
$gym = false;
if (isset($_POST['ac'])) {
    $square_foot = true;
    $query = mysqli_query($mysqli, 'delete from gymroom');
    $query = mysqli_query($mysqli, "INSERT INTO gymroom(floor, wall, ramp) VALUES ('".$_POST['floor']."','".$_POST['wall']."','".$_POST['ramp']."')");
    $data['floor'] = $_POST['floor'];
    $data['wall'] = $_POST['wall'];
    $data['ramp'] = $_POST['ramp'];

    die('1');
}
if (isset($_GET['idactividad'])) {
    $enActividad = true;
    $query_more = mysqli_query($mysqli, "select b.nombre,a.num from (select count(*)num,dni from actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m') group by dni) a 
    join registrados b on (a.dni=b.dni)");
    $actividades_more = array();
    while ($fetch_actividad_more = mysqli_fetch_array($query_more)) {
        $actividade_more['nombre'] = $fetch_actividad_more['nombre'];
        $actividade_more['num'] = $fetch_actividad_more['num'];
        array_push($actividades_more, $actividade_more);
    }
}
if (isset($_GET['retention'])) {
    $retention = true;
    $query_more = mysqli_query($mysqli, "select b.Month, IFNULL(a.numLeads,0)numLeads,b.Leads, IFNULL(round(a.numLeads*100/(b.Leads+a.numLeads),2),0)percent from (SELECT date_format(fecha,'%M')Month,count(mes_pagado)Leads FROM registrados WHERE  mes_pagado='0000-00-00' and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m'))b
    left join (SELECT date_format(fecha,'%M')Month,count(fecha)numLeads from registrados where DATE_FORMAT(mes_pagado,'%Y-%m-%d')=DATE_FORMAT(fecha,'%Y-%m-%d') and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m')  and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')) a
   on (a.Month=b.Month)");
    $retentions = array();
    while ($fetch_actividad_more = mysqli_fetch_array($query_more)) {
        $item['Month'] = $fetch_actividad_more['Month'];
        $item['numLeads'] = $fetch_actividad_more['numLeads'];
        $item['Leads'] = $fetch_actividad_more['Leads'];
        $item['percent'] = $fetch_actividad_more['percent'];
        array_push($retentions, $item);
    }
} elseif (isset($_GET['lead'])) {
    $lead = true;
    $lead_query = mysqli_query($mysqli, "SELECT date_format(fecha,'%M')Month,count(mes_pagado)Leads FROM registrados WHERE  mes_pagado='0000-00-00' and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')");
    $leads = array();
    while ($fetch_max = mysqli_fetch_array($lead_query)) {
        $item['Month'] = $fetch_max['Month'];
        $item['Leads'] = $fetch_max['Leads'];
        array_push($leads, $item);
    }
} elseif (isset($_GET['conversion'])) {
    $conversion = true;
    $conversion_query = mysqli_query($mysqli, "select b.Month, IFNULL(a.numLeads,0)numLeads, IFNULL(b.Leads,0)Leads, IFNULL(round(a.numLeads*100/(b.Leads+a.numLeads),2),0)percent from (SELECT date_format(fecha,'%M')Month,count(mes_pagado)Leads FROM registrados WHERE  mes_pagado='0000-00-00' and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m'))b
    left join (SELECT date_format(fecha,'%M')Month,count(fecha)numLeads from registrados where mes_pagado>=fecha and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m')  and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')) a
   on (a.Month=b.Month)");
    $conversions = array();
    while ($fetch_max = mysqli_fetch_array($conversion_query)) {
        $item['Month'] = $fetch_max['Month'];
        $item['Leads'] = $fetch_max['Leads'];
        $item['numLeads'] = $fetch_max['numLeads'];
        $item['percent'] = $fetch_max['percent'];
        array_push($conversions, $item);
    }
} elseif (isset($_GET['AverageActivity'])) {
    $AverageActivity = true;
    $queryactivity = mysqli_query($mysqli, "select actividad from registrados where DATE_FORMAT(fecha,'%Y-%m')=DATE_FORMAT(mes_pagado,'%Y-%m') and DATE_FORMAT(mes_pagado,'%Y-%m')=DATE_FORMAT(CURDATE(), '%Y-%m') group by actividad");
    $actividades = array();
    while ($fetch_actividad = mysqli_fetch_array($queryactivity)) {
        $actividade['actividad'] = $fetch_actividad['actividad'];
        array_push($actividades, $actividade);
    }
    $data = array();
    foreach ($actividades as $actividad => $item) {
        $querypercent = mysqli_query($mysqli, "select Round(sum(q.percent)/count(*),2)percent,q.actividad,b.nombre from (select Round(w.num*100/w.total,2)percent,w.actividad from (select count(*)num,actividad,(select count(*) from registrados where DATE_FORMAT(fecha,'%Y-%m')=DATE_FORMAT(mes_pagado,'%Y-%m') and DATE_FORMAT(mes_pagado,'%Y-%m')=DATE_FORMAT(CURDATE(), '%Y-%m'))total from actividad_asistencia where actividad='".$item['actividad']."' and DATE_FORMAT(fecha,'%Y-%m')=DATE_FORMAT(CURDATE(), '%Y-%m') 
        group by fecha)w) q join actividad b on (q.actividad=b.id_actividad)");
        $actividades_percent = array();
        while ($fetch_actividad = mysqli_fetch_array($querypercent)) {
            $actividade['nombre'] = $fetch_actividad['nombre'];
            $actividade['percent'] = $fetch_actividad['percent'];
            array_push($actividades_percent, $actividade);
        }
        array_push($data, $actividades_percent);
    }
} elseif (isset($_GET['Revenue_per_session'])) {
    $Revenue_per_session = true;
    $RevenueSession_query = mysqli_query($mysqli, "select *, round(total*100/alltotal,2)percent from (select sum(b.precio)total, d.nombre,b.precio,count(*)num,(select sum(precio)alltotal from (select  *  from ventas where id_registrados<>0 and date_format(fecha,'%Y')= YEAR(CURDATE())) a
    left join ventas_detalle b on (a.id_venta=b.id_venta))alltotal from (select * from ventas where id_registrados<>0 and date_format(fecha,'%Y')= YEAR(CURDATE()) ) a
    left join  ventas_detalle b  on (a.id_venta=b.id_venta)
    left join registrados c  on (a.id_registrados=c.dni)
    left join actividad d on (c.actividad=d.id_actividad) group by actividad)w");
    $RevenueSessions = array();
    while ($fetch_max = mysqli_fetch_array($RevenueSession_query)) {
        $RevenueSession['nombre'] = $fetch_max['nombre'];
        $RevenueSession['total'] = $fetch_max['total'];
        $RevenueSession['precio'] = $fetch_max['precio'];
        $RevenueSession['num'] = $fetch_max['num'];
        $RevenueSession['alltotal'] = $fetch_max['alltotal'];
        $RevenueSession['percent'] = $fetch_max['percent'];
        array_push($RevenueSessions, $RevenueSession);
    }
} elseif (isset($_GET['Revenue_per_client'])) {
    $Revenue_per_client = true;
    $RevenueClient_query = mysqli_query($mysqli, "select sum(b.precio)total,count( DISTINCT id_registrados)num, DATE_FORMAT(a.fecha,'%M')Month from (select  *  from ventas where id_registrados<>0 and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m'))a
    join  ventas_detalle b on(a.id_venta=b.id_venta) group by DATE_FORMAT(a.fecha,'%Y-%m')");
    $RevenueClients = array();
    while ($fetch_max = mysqli_fetch_array($RevenueClient_query)) {
        $RevenueClient['Month'] = $fetch_max['Month'];
        $RevenueClient['total'] = $fetch_max['total'];
        $RevenueClient['num'] = $fetch_max['num'];
        array_push($RevenueClients, $RevenueClient);
    }
} elseif (isset($_GET['gym'])) {
    $gym = true;
    $gym_more = mysqli_query($mysqli, "select date_format(fecha,'%M')Month, count(fecha)total from actividad_asistencia  where DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')");
    $avedai = array();
    while ($fetch_actividad_more = mysqli_fetch_array($gym_more)) {
        $item['Month'] = $fetch_actividad_more['Month'];
        $item['total'] = $fetch_actividad_more['total'];
        array_push($avedai, $item);
    }
} elseif (isset($_GET['square_foot'])) {
    $square_foot = true;
    $square_query = mysqli_query($mysqli, 'select * from gymroom');
    $Revenue_square = mysqli_fetch_array($square_query);
    $square_query_val = mysqli_query($mysqli, 'select ROUND(floor/(floor+wall+ramp),6)floor_val, ROUND(wall/(floor+wall+ramp),6)wall_val,ROUND(ramp/(floor+wall+ramp),6)ramp_val from gymroom');
    $Revenue_square_val = mysqli_fetch_array($square_query_val);
} elseif (isset($_GET['out_term'])) {
    $out_term = true;
    $out_term_query = mysqli_query($mysqli, "select a.id_actividad,a.nombre,IFNULL(b.num,'')num  from actividad a
    left join (select count(*)num, actividad  from registrados where  date_format(mes_pagado,'%Y-%m')= date_format(CURDATE(),'%Y-%m') group by actividad)b
    on (a.id_actividad=b.actividad)");
    $out_terms = array();
    while ($fetch_max = mysqli_fetch_array($out_term_query)) {
        $out_term_list['nombre'] = $fetch_max['nombre'];
        $out_term_list['num'] = $fetch_max['num'];
        array_push($out_terms, $out_term_list);
    }
} elseif (isset($_GET['gross_profit'])) {
    $gross_profit = true;
    $gross_query = mysqli_query($mysqli, "select ww.revenue,ww.cost,(ww.revenue-ww.cost)diff,ww.Month from (select q.Month,q.revenue,IFNULL(w.cost,0)cost from (select sum(b.precio)revenue,date_format(a.fecha,'%M')Month from ventas  a join ventas_detalle b on (a.id_venta=b.id_venta) where a.id_registrados<>0 and date_format(a.fecha,'%Y')= YEAR(CURDATE()) group by date_format(a.fecha,'%Y-%m')) q
    left join (select sum(billetes)cost,date_format(fecha,'%M')Month from caja_extraccion where concepto<>4 and date_format(fecha,'%Y')= YEAR(CURDATE()) group by date_format(fecha,'%Y-%m'))w
    on (q.Month=w.Month)) ww");
    $gross_profits = array();
    while ($fetch_max = mysqli_fetch_array($gross_query)) {
        $item['Month'] = $fetch_max['Month'];
        $item['revenue'] = $fetch_max['revenue'];
        $item['cost'] = $fetch_max['cost'];
        $item['diff'] = $fetch_max['diff'];
        array_push($gross_profits, $item);
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8">
        <meta name='viewport' content='width=device-width, initial-scale=0.8, maximum-scale=1.0' />
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">    
        <title>LOKALES TRAINING SPOT | Control de asistencia</title>
        <link href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">  
        <style>
        .btn-danger {
            text-align: center;
            line-height: 18px;
            -webkit-font-smoothing: antialiased;
            display: inline-block;
            border-radius: 3px;
            padding: 8px 18px;
            background: -webkit-linear-gradient(top,#139ff0 0,#0087e0 100%);
            background: linear-gradient(to bottom,#139ff0 0,#0087e0 100%);
            border: 1px solid #0087e0;
            color: #F7F7F7;
            font-weight: 700;
            text-shadow: 0 -1px transparent;
            cursor: pointer;
        }
        </style>  
    </head>

    <body>
        <div class="container">
            <div class="head">
                <!-- <h1>Control de asistencia<?php echo $actividad == '' ? '' : ' | '.$actividad; ?></h1> -->
            </div>
            <div class="body">
                <?php
                if ($enActividad) {
                    ?>
                    <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                               
                                <tbody> 
                                        <tr>
                                            <th class="text-center">Nombre</th>
                                            <th class="text-center">AVERAGE</th>
                                        </tr>
                                <?php

                                        foreach ($actividades_more as $actividad => $body) {
                                            ?>
                                        <tr>
                                  
                                            <td class="text-center"><?php echo $body['nombre']; ?></td>
                                            <td class="text-center"><?php echo $body['num']; ?></td>
                                       
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                <?php
                } elseif ($AverageActivity) {
                    ?>
                     <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Actividad</th>
                                        <th class="text-center">Percent</th>      
                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($data as $actividad => $body) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $body[0]['nombre']; ?></td>
                                            <td class="text-center"><?php echo $body[0]['percent']; ?> %</td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($retention) {
                    ?>
                     <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                                <tbody> 
                                        <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Lead</th>
                                        <th class="text-center">New Member</th>
                                        <th class="text-center">Rate</th>
                                        </tr>
                                <?php

                                        foreach ($retentions as $actividad => $body) {
                                            ?>
                                        <tr>                                  
                                             <td class="text-center"><?php echo $body['Month']; ?></td>
                                            <td class="text-center"><?php echo $body['Leads']; ?></td> 
                                            <td class="text-center"><?php echo $body['numLeads']; ?></td>
                                            <td class="text-center"><?php echo $body['percent']; ?> %</td> 
                                       
                                    </tr>
                                    <?php
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($lead) {
                    ?>
                     <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Lead</th>      
                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($leads as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['Month']; ?></td>
                                            <td class="text-center"><?php echo $item['Leads']; ?></td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    <?php
                } elseif ($conversion) {
                    ?>
                     <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Lead</th>
                                        <th class="text-center">New Member</th> 
                                        <th class="text-center">Rate</th>      
                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($conversions as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['Month']; ?></td>
                                            <td class="text-center"><?php echo $item['Leads']; ?></td> 
                                            <td class="text-center"><?php echo $item['numLeads']; ?></td>
                                            <td class="text-center"><?php echo $item['percent']; ?> %</td>                                             
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                                    
                        <?php
                } elseif ($Revenue_per_client) {
                    ?>
                     <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Revenue per client</th>      
                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($RevenueClients as $actividad => $body) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $body['Month']; ?></td>
                                            <td class="text-center"><?php echo round($body['total'] / $body['num'], 2); ?> $</td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    <?php
                } elseif ($Revenue_per_session) {
                    ?>
                     <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Gimnasio</th>
                                        <th class="text-center">cantidad</th>  
                                        <th class="text-center">Precio</th>  
                                        <th class="text-center">Revenue</th>  
                                        <th class="text-center">Percent</th>      
                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($RevenueSessions as $actividad => $body) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $body['nombre']; ?></td>
                                            <td class="text-center"><?php echo $body['precio']; ?> $</td>
                                            <td class="text-center"><?php echo $body['num']; ?></td>
                                            <td class="text-center"><?php echo $body['total']; ?> $</td>
                                            <td class="text-center"><?php echo $body['percent']; ?> %</td> 
                                    </tr>
                                    <?php
                                        } ?>
                                          <tr>                                  
                                            <th class="text-center" ><?php echo $body['nombre']; ?></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"><?php echo $RevenueSessions[0]['alltotal']; ?> $</th>
                                            <th class="text-center">100 %</th> 
                                        </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($gym) {
                    ?>
                     <div class="table-responsive table-bordered mt-5">
                        <table class="table">
                               
                                <tbody> 
                                        <tr>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Member</th>
                                        </tr>
                                <?php

                                        foreach ($avedai as $actividad => $item) {
                                            ?>
                                        <tr>
                                  
                                            <td class="text-center"><?php echo $item['Month']; ?></td>
                                            <td class="text-center"><?php echo $item['total']; ?></td>
                                       
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($square_foot) {
                    ?>
                        
                        <h3>Total revenue</h3>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table" id="revenue">                               
                                <tbody>
                                    <tr >
                                        <td class="text-center" width="33%" >Revenue floor</td>
                                        <td class="text-center" width="33%"  ><input type='number' class="form-control" id="floor" value="<?php echo $Revenue_square['floor']; ?>" min="0"/></td>
                                        <td class="text-center" width="33%"  ><?php echo $Revenue_square_val['floor_val']; ?></td>
                                    </tr> 
                                    <tr >
                                        <td class="text-center" width="33%"  >Revenue Wall</td>
                                        <td class="text-center" width="33%"  ><input type='number' class="form-control" id="wall" value="<?php echo $Revenue_square['wall']; ?>" min="0"/></td>
                                        <td class="text-center" width="33%"  ><?php echo $Revenue_square_val['wall_val']; ?></td>
                                    </tr> 
                                    <tr >
                                        <td class="text-center" width="33%"  >Revenue Ramp</td>
                                        <td class="text-center" width="33%"  ><input type='number' class="form-control" id="ramp" value="<?php echo $Revenue_square['ramp']; ?>" min="0"/></td>
                                        <td class="text-center" width="33%"  ><?php echo $Revenue_square_val['ramp_val']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                           
                        </div> 
                        <div class="text-right">
                        <button class="btn btn-danger text-center" id ="save"> Save </button>   
                        </div> 
                        <?php
                } elseif ($out_term) {
                    ?>
                        
                        <h3>Out of term payment</h3>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">  
                                <thead>
                                     <tr >
                                        <th class="text-center" >Actividad</th>
                                        <th class="text-center" >out of</th>
                                        </tr> 
                                </thead>                             
                                <tbody>  
                                    <?php
                                        foreach ($out_terms as $actividad => $body) {
                                            ?>                                 
                                    <tr >
                                        <td class="text-center" ><?php echo $body['nombre']; ?></td>
                                        <td class="text-center" ><?php echo $body['num']; ?></td>
                                    </tr>
                                        <?php
                                        } ?>
                                </tbody>
                            </table>
                           
                        </div>    
                        <?php
                } elseif ($gross_profit) {
                    ?>
                        
                        <h3>Gross profit</h3>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">  
                                <thead>
                                     <tr >
                                        <th class="text-center" >Month</th>
                                        <th class="text-center" >Revenue</th>
                                        <th class="text-center" >Cost</th>
                                        <th class="text-center" >Toal Revenue</th>
                                        </tr> 
                                </thead>                             
                                <tbody>  
                                    <?php
                                        foreach ($gross_profits as $actividad => $item) {
                                            ?>                                 
                                    <tr >
                                        <td class="text-center" ><?php echo $item['Month']; ?></td>
                                        <td class="text-center" ><?php echo $item['revenue']; ?> $</td>
                                        <td class="text-center" ><?php echo $item['cost']; ?> $</td>
                                        <td class="text-center" ><?php echo $item['diff']; ?> $</td>
                                    </tr>
                                        <?php
                                        } ?>
                                </tbody>
                            </table>
                           
                        </div>                        
                  <?php
                }
                    ?>
               
            </div>
            <div class="footer">
                <?php
                if ($enActividad || $AverageActivity || $Revenue_per_client || $Revenue_per_session || $square_foot || $out_term || $lead || $conversion || $gross_profit || $retention || $gym) {
                    ?>
                    <button onclick="location.href='indicadores.php';">Volver</button>
                    <?php
                }
                ?>
            </div>
        </div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script> 
<script>
    $(document).ready(function(){
        var floor, wall, ramp;
        $("#floor").on('change', function(e){

            floor=+e.target.value;
            wall=+$("#wall").val();
            ramp=+$("#ramp").val();
            just_calculous(floor,wall,ramp);
        });
        $("#wall").on('change', function(e){
            floor=+$("#floor").val();
            wall=+e.target.value;
            ramp=+$("#ramp").val();
            just_calculous(floor,wall,ramp);
        });
        $("#ramp").on('change', function(e){
            floor=+$("#floor").val();
            wall=+$("#wall").val();
            ramp=+e.target.value;
            just_calculous(floor,wall,ramp);
        });
        function just_calculous(floor,wall,ramp){
           var total=floor+wall+ramp;
           var floor_val=floor/total;
           var wall_val=wall/total;
           var ramp_val=ramp/total;
           $("#revenue tr:nth-child(1) td:nth-child(3)").text(floor_val.toFixed(6));
           $("#revenue tr:nth-child(2) td:nth-child(3)").text(wall_val.toFixed(6));
           $("#revenue tr:nth-child(3) td:nth-child(3)").text(ramp_val.toFixed(6));
           $("#save").text("save");
           $("#save").css("background","linear-gradient(to bottom,#139ff0 0,#0087e0 100%)");
        }
        $("#save").on("click", function(){
            var floor=$("#floor").val();
            var wall=$("#wall").val();
            var ramp=$("#ramp").val();
            $.ajax({
                 timeout: 100000,
                    cache: false,
                    type:"post",
                    url:"indicmore.php",
                    data: { ac: "Revenue_floor_edit", floor: floor, wall: wall,ramp: ramp, square_foot: true},
                    dataType: "text",
                    success:function(data) {            
                      $("#save").text("saved");
                      $("#save").css("background","linear-gradient(to bottom,red 0,red 100%)");
                    },
                    error:function(data) {
                        alert("Ha ocurrido un error en la petición");
                    }
                });
        })
         
    });

</script>  
    </body>
</html>