<?php
    include 'conex.php';
    include 'conex_history.php';
    session_start();
  /* Leads */
  $query = mysqli_query($mysqli, "SELECT date_format(fecha,'%M')Month,count(mes_pagado)Leads FROM registrados WHERE  mes_pagado='0000-00-00' and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')");
  $sampleDatas = array();
  while ($sample_data = mysqli_fetch_array($query)) {
      $sampleData['Month'] = $sample_data['Month'];
      $sampleData['Leads'] = $sample_data['Leads'];
      array_push($sampleDatas, $sampleData);
  }
 /* Conversion rate */
  $query1 = mysqli_query($mysqli, "select b.Month, IFNULL(round(a.numLeads*100/(b.Leads+a.numLeads),2),0)percent from (SELECT date_format(fecha,'%M')Month,count(mes_pagado)Leads FROM registrados WHERE  mes_pagado='0000-00-00' and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m'))b
  left join (SELECT date_format(fecha,'%M')Month,count(fecha)numLeads from registrados where mes_pagado>=fecha and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m')  and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')) a
 on (a.Month=b.Month)");
  $conversion_rate_vals = array();
  if ($query1) {
      while ($conversion_rate_val = mysqli_fetch_array($query1)) {
          $conversion['Month'] = $conversion_rate_val['Month'];
          $conversion['percent'] = $conversion_rate_val['percent'];
          array_push($conversion_rate_vals, $conversion);
      }
  }
  /* Retention rate  */
  $query2 = mysqli_query($mysqli, "select b.Month, IFNULL(round(a.numLeads*100/(b.Leads+a.numLeads),2),0)percent from (SELECT date_format(fecha,'%M')Month,count(mes_pagado)Leads FROM registrados WHERE  mes_pagado='0000-00-00' and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m') and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m'))b
  left join (SELECT date_format(fecha,'%M')Month,count(fecha)numLeads from registrados where DATE_FORMAT(mes_pagado,'%Y-%m-%d')=DATE_FORMAT(fecha,'%Y-%m-%d') and DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m')  and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')) a
 on (a.Month=b.Month)");
  $retention_rate_vals = array();
  if ($query2) {
      while ($retention_rate_val = mysqli_fetch_array($query2)) {
          $retention['Month'] = $retention_rate_val['Month'];
          $retention['percent'] = $retention_rate_val['percent'];
          array_push($retention_rate_vals, $retention);
      }
  }
$queryw = mysqli_query($mysqli, "select (select round(q.total/q.num,2)first from (select (select count(*)total from actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m')= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m'))total,(select count(DISTINCT dni)num from actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m')= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m'))num)q ) first,
(select round(q.total/q.num,2)first from (select (select count(*)total from actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m')= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m'))total,(select count(DISTINCT dni)num from actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m')= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m'))num)q ) second,
(select round(q.total/q.num,2)first from (select (select count(*)total from actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m'))total,(select count(DISTINCT dni)num from actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m'))num)q ) third");
$actividades = array();
while ($fetch_actividad = mysqli_fetch_array($queryw)) {
    $actividade['first'] = $fetch_actividad['first'];
    $actividade['second'] = $fetch_actividad['second'];
    $actividade['third'] = $fetch_actividad['third'];
    array_push($actividades, $actividade);
}
$queryactivity = mysqli_query($mysqli, "select actividad from registrados where DATE_FORMAT(fecha,'%Y-%m')=DATE_FORMAT(mes_pagado,'%Y-%m') and DATE_FORMAT(mes_pagado,'%Y-%m')=DATE_FORMAT(CURDATE(), '%Y-%m') group by actividad");
    $actividadess = array();
    while ($fetch_actividad = mysqli_fetch_array($queryactivity)) {
        $actividade['actividad'] = $fetch_actividad['actividad'];
        array_push($actividadess, $actividade);
    }
    $data = array();
    $sum = 0;
    foreach ($actividadess as $actividad => $item) {
        $querypercent = mysqli_query($mysqli, "select Round(sum(q.percent)/count(*),2)percent,q.actividad,b.nombre from (select Round(w.num*100/w.total,2)percent,w.actividad from (select count(*)num,actividad,(select count(*) from registrados where DATE_FORMAT(fecha,'%Y-%m')=DATE_FORMAT(mes_pagado,'%Y-%m') and DATE_FORMAT(mes_pagado,'%Y-%m')=DATE_FORMAT(CURDATE(), '%Y-%m'))total from actividad_asistencia where actividad='".$item['actividad']."' and DATE_FORMAT(fecha,'%Y-%m')=DATE_FORMAT(CURDATE(), '%Y-%m') 
        group by fecha)w) q join actividad b on (q.actividad=b.id_actividad)");
        $actividades_percent = array();
        while ($fetch_actividad = mysqli_fetch_array($querypercent)) {
            $actividade['nombre'] = $fetch_actividad['nombre'];
            $actividade['percent'] = $fetch_actividad['percent'];
            $sum = $sum + $fetch_actividad['percent'];
            array_push($actividades_percent, $actividade);
        }
        array_push($data, $actividades_percent);
    }
    $ch_data = array();
    foreach ($data as $actividadd => $item) {
        $actividade['nombre'] = $item[0]['nombre'];
        $actividade['percent'] = $item[0]['percent'];
        array_push($ch_data, $actividade);
    }

    $percent = array_column($ch_data, 'percent');
    $Promedios_min = $ch_data[array_search(min($percent), $percent)];
    $Promedios_max = $ch_data[array_search(max($percent), $percent)];
    $Average_percent = round($sum / count($ch_data), 2);
    /* Average class attendance end */
$gym_query = mysqli_query($mysqli, "select DATE_FORMAT(fecha,'%M')Month, count(*)num from  actividad_asistencia where DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -2 MONTH),'%Y-%m')  and DATE_FORMAT(CURDATE(),'%Y-%m') group by DATE_FORMAT(fecha,'%Y-%m')");
$gyms = array();
while ($fetch_gym = mysqli_fetch_array($gym_query)) {
    $gym['Month'] = $fetch_gym['Month'];
    $gym['num'] = $fetch_gym['num'];
    array_push($gyms, $gym);
}

$Revenue_query = mysqli_query($mysqli, "select round(q.price/q.num,2)Average_val from (select sum(precio)price ,(select count(*)num from (select  id_registrados  from ventas where id_registrados<>0 and date_format(fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m') group by id_registrados) w)num  from ventas a 
left join ventas_detalle b on (a.id_venta=b.id_venta) where a.id_registrados<>0 and date_format(a.fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m'))q");
$fetch_Revenue = mysqli_fetch_array($Revenue_query);

$Revenue_min_query = mysqli_query($mysqli, "select * from (select (w.val)value, w.nombre, w.id_actividad from (select sum(c.precio)val,d.nombre,d.id_actividad from (select * from ventas where id_registrados<>0 and date_format(fecha,'%Y')= YEAR(CURDATE())) a
left join (select * from registrados where date_format(mes_pagado,'%Y')= YEAR(CURDATE())) b on (a.id_registrados=b.dni)
left join ventas_detalle c on(a.id_venta=c.id_venta)
left join actividad d on(b.actividad=d.id_actividad) group by d.nombre)w) q where  value in (select min(w.val)value from (select sum(c.precio)val,d.nombre,d.id_actividad from (select * from ventas where id_registrados<>0 and date_format(fecha,'%Y')= YEAR(CURDATE())) a
left join (select * from registrados where date_format(mes_pagado,'%Y')= YEAR(CURDATE())) b on (a.id_registrados=b.dni)
left join ventas_detalle c on(a.id_venta=c.id_venta)
left join actividad d on(b.actividad=d.id_actividad) group by d.nombre)w)");
$Revenue_min = mysqli_fetch_array($Revenue_min_query);
$Revenue_max_query = mysqli_query($mysqli, "select * from (select (w.val)value, w.nombre, w.id_actividad from (select sum(c.precio)val,d.nombre,d.id_actividad from (select * from ventas where id_registrados<>0 and date_format(fecha,'%Y')= YEAR(CURDATE())) a
left join (select * from registrados where date_format(mes_pagado,'%Y')= YEAR(CURDATE())) b on (a.id_registrados=b.dni)
left join ventas_detalle c on(a.id_venta=c.id_venta)
left join actividad d on(b.actividad=d.id_actividad) group by d.nombre)w) q where  value in (select max(w.val)value from (select sum(c.precio)val,d.nombre,d.id_actividad from (select * from ventas where id_registrados<>0 and date_format(fecha,'%Y')= YEAR(CURDATE())) a
left join (select * from registrados where date_format(mes_pagado,'%Y')= YEAR(CURDATE())) b on (a.id_registrados=b.dni)
left join ventas_detalle c on(a.id_venta=c.id_venta)
left join actividad d on(b.actividad=d.id_actividad) group by d.nombre)w)");
$Revenue_max = mysqli_fetch_array($Revenue_max_query);

$out_team_query = mysqli_query($mysqli, "select (q.num1-q.num2)num from (select (select count(*)num1 from  registrados where date_format(fecha, '%Y-%m-%d') between date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-01') and date_format(CURDATE(),'%Y-%m-10')
and date_format(mes_pagado, '%Y-%m-%d') BETWEEN date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-11') and LAST_DAY(date_format(CURDATE(),'%Y-%m-%d')))num1, (select count(*)num2 from registrados where date_format(fecha, '%Y-%m-%d') between date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-11') and LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -1 MONTH))
and date_format(mes_pagado, '%Y-%m-%d') between date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-11') and LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -1 MONTH)))num2)q");
$out_team = mysqli_fetch_array($out_team_query);
$total_out_team_query = mysqli_query($mysqli, "select (q.num1+q.num2)num from (select (select count(*)num1 from registrados where date_format(mes_pagado, '%Y-%m-%d') between date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-11') and LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -1 MONTH))
and date_format(fecha, '%Y-%m-%d') between date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-01') and date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-10'))num1, (select count(*)num2 from registrados where date_format(mes_pagado, '%Y-%m-%d') >= date_format(CURDATE(),'%Y-%m-11') 
and date_format(fecha, '%Y-%m-%d') between date_format(DATE_ADD(CURDATE(), INTERVAL -1 MONTH),'%Y-%m-12') and date_format(CURDATE(),'%Y-%m-10'))num2) q");
$total_out_team = mysqli_fetch_array($total_out_team_query);
$gross_query = mysqli_query($mysqli, "select ww.revenue,ww.cost,(ww.revenue-ww.cost)diff from (select q.Month,q.revenue,IFNULL(w.cost,0)cost from (select sum(b.precio)revenue,date_format(a.fecha,'%M')Month from ventas  a join ventas_detalle b on (a.id_venta=b.id_venta) where a.id_registrados<>0 and date_format(a.fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m')group by date_format(a.fecha,'%Y-%m')) q
left join (select sum(billetes)cost,date_format(fecha,'%M')Month from caja_extraccion where concepto<>4 and date_format(fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m') group by date_format(fecha,'%Y-%m'))w
on (q.Month=w.Month)) ww");
$gross_profit = mysqli_fetch_array($gross_query);
/* historic data */
$current_rev_query = mysqli_query($mysqli, "select q.Month,q.revenue,IFNULL(w.cost,'')cost from (select sum(b.precio)revenue,date_format(a.fecha,'%M')Month from ventas  a join ventas_detalle b on (a.id_venta=b.id_venta) where a.id_registrados<>0 and date_format(a.fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m')group by date_format(a.fecha,'%Y-%m')) q
left join (select sum(billetes)cost,date_format(fecha,'%M')Month from caja_extraccion where concepto<>4 and date_format(fecha,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m') group by date_format(fecha,'%Y-%m'))w
on (q.Month=w.Month)");
$current_revenue = mysqli_fetch_array($current_rev_query);
$square_query = mysqli_query($mysqli, "select ROUND(floor*'".$current_revenue['revenue']."'/(floor+wall+ramp),2)floor_val, ROUND(wall*'".$current_revenue['revenue']."'/(floor+wall+ramp),2)wall_val,ROUND(ramp*'".$current_revenue['revenue']."'/(floor+wall+ramp),2)ramp_val from gymroom");
$Revenue_square = mysqli_fetch_array($square_query);
$squre_average = round(($Revenue_square['floor_val'] + $Revenue_square['wall_val'] + $Revenue_square['ramp_val']) / 3, 2);

if (date('Y-m-t') == date('Y-m-d')) {
    /* update */
    $update_query = mysqli_query($mysqli_history, "delete from record where fecha='".date('Y-m-d')."'");
    /* insert */
    $fecha_val = date('Y-m-d');
    $lead_val = $sampleDatas[2]['Leads'];
    $conrate_val = $conversion_rate_vals[2]['percent'];
    $retrate_val = $retention_rate_vals[2]['percent'];
    $earusa_val = $actividades[0]['third'];
    $revcli_val = $fetch_Revenue['Average_val'];
    $revses_val = round(($Revenue_max['value'] + $Revenue_min['value']) / 2, 2);
    $outpay_val = $out_team['num'];
    $revenue_val = $gross_profit['revenue'];
    $cost_val = $gross_profit['cost'];
    $avedai_val = $gyms[2]['num'];
    $insert_query = mysqli_query($mysqli_history, "INSERT INTO  `lokaleskpi`.`record` (`fecha` , `leads` ,  `conrate` , `retrate` ,`earusa` ,`avecla` , `avedai` , `revcli` , `revses` ,  `revsqu` , `outpay` , `revenue` , `cost`  )
    VALUES ('".$fecha_val."',  '".$lead_val."',  '".$conrate_val."',  '".$retrate_val."',  '".$earusa_val."',  '".$Average_percent."',  '".$avedai_val."',  '".$revcli_val."',  '".$revses_val."',  '".$squre_average."',  '".$outpay_val."',  '".$revenue_val."', '".$cost_val."' )");
}
?>
<html>
<head>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LOKALES TRAINING SPOT | Control de asistencia</title>
<link href="http://www.lokales.com.ar/favico.ico" rel="shortcut icon">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">    
<link rel="stylesheet" href="css/style.min.css" id="stylesheet"> 
<link rel="stylesheet" href="css/jqx.base.css" type="text/css" />
<!-- <link rel="stylesheet" href="css/demos.css" type="text/css" /> -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="js/webcomponents-lite.min.js"></script>
<script type="text/javascript" src="js/demos.js"></script>
<script type="text/javascript" src="js/jqxcore.js"></script>
<script type="text/javascript" src="js/jqxcore.elements.js"></script>
<script type="text/javascript" src="js/jqxdata.js"></script>
<script type="text/javascript" src="js/jqxdraw.js"></script>
<script type="text/javascript" src="js/jqxchart.core.js"></script>
<style>
body{    
    overflow-y: scroll !important;
    padding: 5px;
    margin: 0;
}
.jqx-chart-legend-text-light,.jqx-chart-title-text-light,.jqx-chart-title-description-light{
    display:none;
}
.h4, h4 {
    font-size: 1.5rem;
    font-weight: 800;
}
</style>
</head>
<body>
    <div class="container-fulid mx-5">
        <div class="head">
        </div>
        <div class="body">							
            <div class="row">
                <div class="col-lg-4">
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Visitas/interesados</h4>
                        <h5>(Leads)</h5>
                        <jqx-chart settings="chartSettingsbar" style="height:250px; width:100%"></jqx-chart>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?lead=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?lead=true">More</a> 
                        </div>                        
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Uso temprano (nuevo cliente)</h4>
                        <h5>Early stage usage  (new member)</h5>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">
                                <tbody> 
                                    <tr>
                                        <th class="text-center"><?php echo date('M', strtotime('-2 month', strtotime(date('Y-m-d')))); ?></th>       
                                        <th class="text-center"><?php echo date('M', strtotime('-1 month', strtotime(date('Y-m-d')))); ?></th>       
                                        <th class="text-center"><?php echo date('M', strtotime('0 month', strtotime(date('Y-m-d')))); ?></th>  
                                    </tr>
                                    <?php
                                        foreach ($actividades as $actividad => $body) {
                                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $body['first']; ?></td>
                                        <td class="text-center"><?php echo $body['second']; ?></td>
                                        <td class="text-center"><?php echo $body['third']; ?></td>  
                                    </tr>
                                    <?php
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?idactividad=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?idactividad=true">More</a> 
                        </div> 
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Ingreso por cliente</h4>
                        <h5>Revenue per client</h5>
                        <h4 style="padding-top:50px">$ <?php echo $fetch_Revenue['Average_val']; ?></h4>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?Revenue_per_client=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?Revenue_per_client=true">More</a> 
                        </div>
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Pago fuera de termino</h4>
                        <h5>Out of term payment</h5>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">                               
                                <tbody>  
                                    <tr >
                                        <th class="text-center">total</th>
                                        <th class="text-center">out of</th>
                                    </tr>                                    
                                    <tr >
                                        <td class="text-center"><?php echo $total_out_team['num']; ?></td>
                                        <td class="text-center"><?php echo $out_team['num']; ?> </td>  
                                    </tr> 
                                    <?php if ($out_team['num'] != 0) {
                                            ?>
                                    <tr>
                                        <td class="text-center" colspan="2"><?php echo round($total_out_team['num'] * 100 / $out_team['num'], 2); ?> %</td>
                                    </tr> 
                                    <?php
                                        } else {
                                            ?>
                                        <tr>
                                        <td class="text-center" colspan="2" >0 %</td>
                                                                               
                                    </tr> 
                                    <?php
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?out_term=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?out_term=true">More</a> 
                        </div> 
                    </div>
                </div>	
                <div class="col-lg-4">  
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Visitas convertidas a clientes </h4>
                        <h5>(Conversion rate)</h5>                 
                        <jqx-chart settings="chartSettingsline1" style=" height:250px; width:100%"></jqx-chart>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?conversion=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?conversion=true">More</a> 
                        </div>                        
                        
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Promedio de asistencia a clase</h4>
                        <h5>Average class attendance</h5>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">                               
                                <tbody>  
                                <?php if ($Promedios_max) {
                                            ?>
                                    <tr >
                                        <td class="text-center"></td>
                                        <th class="text-center">Average</th>
                                        <th class="text-center"><?php echo $Average_percent; ?> %</th>  
                                    </tr>                                    
                                    <tr style="background:#92d050">
                                        <td class="text-center">mayor</td>
                                        <td class="text-center"><?php echo $Promedios_max['nombre']; ?></td>
                                        <td class="text-center"><?php echo $Promedios_max['percent']; ?> %</td>  
                                    </tr> 
                                    <tr style="background:#f79646">
                                        <td class="text-center">menor</td>
                                        <td class="text-center"><?php echo $Promedios_min['nombre']; ?></td>
                                        <td class="text-center"><?php echo $Promedios_min['percent']; ?> %</td>  
                                    </tr> 
                                <?php
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?AverageActivity=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?AverageActivity=true">More</a> 
                        </div>
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Ingreso por clase</h4>
                        <h5>Revenue per session</h5>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">                               
                                <tbody>  
                                    <tr >
                                        <td class="text-center"></td>
                                        <th class="text-center">Average</th>
                                        <th class="text-center"><?php echo round(($Revenue_max['value'] + $Revenue_min['value']) / 2, 2); ?> $</th>  
                                    </tr>                                    
                                    <tr style="background:#92d050">
                                        <td class="text-center">mayor</td>
                                        <td class="text-center"><?php echo $Revenue_max['nombre']; ?></td>
                                        <td class="text-center"><?php echo $Revenue_max['value']; ?> $</td>  
                                    </tr> 
                                    <tr style="background:#f79646">
                                        <td class="text-center">menor</td>
                                        <td class="text-center"><?php echo $Revenue_min['nombre']; ?></td>
                                        <td class="text-center"><?php echo $Revenue_min['value']; ?> $</td>  
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?Revenue_per_session=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?Revenue_per_session=true">More</a> 
                        </div>
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center" >
                        <h4>Ganancia bruta</h4>
                        <h5>Gross profit</h5>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">                                                              
                                <tbody>  
                                    <tr >
                                        <th class="text-center">Revenue</th>
                                        <th class="text-center">Cost</th>                                       
                                    </tr>                                                                      
                                    <tr >
                                        <td class="text-center"><?php echo $gross_profit['revenue']; ?> $</td>
                                        <td class="text-center"><?php echo $gross_profit['cost']; ?> $</td>
                                    </tr> 
                                    <tr >
                                        <td class="text-center"  colspan="2" >$ <?php echo $gross_profit['diff']; ?> </td>                                      
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?gross_profit=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?gross_profit=true">More</a> 
                        </div> 
                    </div>
                </div>	
                <div class=" col-lg-4">
                    <div class="card px-3 pt-3 mt-2 text-center">
                        <h4>Retention rate</h4>
                        <h5>Tasa de retencion de clientes</h5>
                        <jqx-chart settings="chartSettingsline2" style=" height:250px; width:100%"></jqx-chart> 
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?retention=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?retention=true">More</a> 
                        </div>                       
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center">
                        <h4>Promedio de asistencia al gym</h4>
                        <h5>Average daily attendance</h5>
                        <jqx-chart settings="chartSettingsgym" style=" height:250px; width:100%"></jqx-chart> 
                        <div>
                            <a class="text-left" style="float:left" href="indicperiod.php?gym=true">Period</a>
                            <a class="text-right " style="float:right" href="indicmore.php?gym=true">More</a> 
                        </div>                        
                    </div>
                    <div class="card px-3 pt-3 mt-2 text-center">
                        <h4>Ingreso por metro cuadrado</h4>
                        <h5>Revenue per square foot</h5>
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table" id="revenue">                               
                                <tbody>  
                                    <tr >
                                        <th class="text-center">General</th>
                                        <th class="text-center"><?php echo $squre_average; ?> $</th>
                                    </tr> 
                                    <tr >
                                        <td class="text-center" >Floor</td>
                                        <td class="text-center"><?php echo $Revenue_square['floor_val']; ?> $</td>
                                    </tr> 
                                    <tr >
                                         <td class="text-center" >Wall</td>
                                        <td class="text-center"><?php echo $Revenue_square['wall_val']; ?> $</td>                                        
                                    </tr> 
                                    <tr >
                                        <td class="text-center" >Ramp</td>
                                        <td class="text-center"><?php echo $Revenue_square['ramp_val']; ?> $</td>
                                    </tr>                                    
                                   
                                </tbody>
                            </table>
                        </div>                                    
                        <a class="text-right " href="indicmore.php?square_foot=true">More</a>
                    </div>
                </div>			
            </div>
        </div>
        <div class="footer">        
        </div>	
    </div>	
</body>

<script>

         /* gym*/
         var gyms = <?php echo json_encode($gyms); ?>;
        JQXElements.settings['chartSettingsgym'] =
            {
                title: '',
                enableAnimations: true,
                showLegend: false,
                description: '',
                padding: { left: 5, top: 15, right: 10, bottom: 30 },
                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                source: gyms,
                colorScheme: 'scheme02',
                borderLineWidth:'0',
                xAxis:
                {
                    dataField: 'Month',
                    unitInterval: 1,
                    valuesOnTicks: false,
                    labels: {
                        angle: 0,
                        formatFunction: function (value) {
                            return value.toString();
                        }
                    },
                    tickMarks: {
                        visible: true,
                        interval: 1
                    },
                    gridLines: {
                        visible: true,
                        interval: 3
                    }
                },
                valueAxis:
                {
                    visible: true,
                    minValue: 0,
                    unitInterval: 10,
                    title: { text: 'number' },
                    labels: { horizontalAlignment: 'right' }
                },
                seriesGroups:
                [
                    {
                        type: 'column',
                        series: [
                            { dataField: 'num', displayText: 'number', showLabels: true }
                        ]
                    }
                ]
            };
            
         /*Leads*/
         var sampleData = <?php echo json_encode($sampleDatas); ?>;
        JQXElements.settings['chartSettingsbar'] =
            {
                title: '',
                enableAnimations: true,
                showLegend: false,
                description: '',
                padding: { left: 5, top: 15, right: 10, bottom: 30 },
                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                source: sampleData,
                colorScheme: 'scheme02',
                borderLineWidth:0,
                xAxis:
                {
                    dataField: 'Month',
                    unitInterval: 1,
                    valuesOnTicks: false,
                    labels: {
                        angle: 0,
                        formatFunction: function (value) {
                            return value.toString();
                        }
                    },
                    tickMarks: {
                        visible: true,
                        interval: 1
                    },
                    gridLines: {
                        visible: true,
                        interval: 3
                    }
                },
                valueAxis:
                {
                    visible: true,
                    minValue: 0,
                    unitInterval: 10,
                    title: { text: 'Leads' },
                    labels: { horizontalAlignment: 'right' }
                },
                seriesGroups:
                [
                    {
                        type: 'column',
                        series: [
                            { dataField: 'Leads', displayText: 'Leads', showLabels: true }
                        ]
                    }
                ]
            };
        /*Conversion rate*/
     
        var conversion_rate_val = <?php echo json_encode($conversion_rate_vals); ?>;

            var conversion_rate_source = {
                datafields: [
                    { name: 'Month'},
                    { name: 'percent'}
                ],
                datatype: "json",
                localdata: conversion_rate_val
            };      
        var dataAdapter = new $.jqx.dataAdapter(conversion_rate_source);
        var init_val=conversion_rate_val[0]['percent'];
        JQXElements.settings['chartSettingsline1'] =
            {             
                title: '',
                enableAnimations: true,
                description: '',
                showLegend: false,
                padding: { left: 5, top: 15, right: 10, bottom: 30 },
                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                source: dataAdapter,
                borderLineWidth:0,

                categoryAxis:
                {
                    dataField: 'Month',
                    unitInterval: 1,
                    // textRotationAngle: -75,
                    formatFunction: function (value, itemIndex, serie, group) {
                        return value;
                    },
                    valuesOnTicks: false,
                    tickMarks: {
                        visible: true,
                        interval: 1
                    },
                    gridLines: {
                        visible: true,
                        interval: 3
                    }
                },
                colorScheme: 'scheme05',
                seriesGroups:
                [
                    {
                        type: 'line',
                        valueAxis:
                        {
                            description: 'Conversion rate',
                            formatFunction: function(value) {
                                return value + '%';
                            }
                        },
                        series:
                        [
                            {
                                dataField: 'percent',
                                displayText: 'Percent',                             
                                colorFunction: function (value, itemIndex, serie, group) {
                                        var val=init_val-value;
                                        init_val=value;
                                    return (val < 0) ? 'green' : 'red';
                                }
                            }
                        ]
                    }
                ]
            }
        /*Retention rate*/
            var retention_rate_val = <?php echo json_encode($retention_rate_vals); ?>; 
            var retention_rate_source = {
                datafields: [
                    { name: 'Month'},
                    { name: 'percent'}
                ],
                datatype: "json",
                localdata: retention_rate_val
            };      
        var dataAdapter = new $.jqx.dataAdapter(retention_rate_source);
        var init_val=retention_rate_val[0]['percent'];
        JQXElements.settings['chartSettingsline2'] =
            {
                title: '',
                borderLineWidth: 1,
                showBorderLine: true,
                enableAnimations: true,
                description: '',
                showLegend: false,
                padding: { left: 5, top: 15, right: 10, bottom: 30 },
                titlePadding: { left: 0, top: 0, right: 0, bottom: 10 },
                source: dataAdapter,
                borderLineWidth:0,
                categoryAxis:
                {
                    dataField: 'Month',
                    unitInterval: 1,
                    // textRotationAngle: -75,
                    formatFunction: function (value, itemIndex, serie, group) {
                        return value;
                    },
                    valuesOnTicks: false,
                    tickMarks: {
                        visible: true,
                        interval: 1
                    },
                    gridLines: {
                        visible: true,
                        interval: 3
                    }
                },
                colorScheme: 'scheme05',
                seriesGroups:
                [
                    {
                        type: 'line',
                        valueAxis:
                        {
                            description: 'Tasa de retencion de clientes',
                            formatFunction: function(value) {
                                return value + '%';
                            }
                        },
                        series:
                        [
                            {
                                dataField: 'percent',
                                displayText: 'Percent',                             
                                colorFunction: function (value, itemIndex, serie, group) {
                                        var val=init_val-value;
                                        init_val=value;
                                    return (val < 0) ? 'green' : 'red';
                                }
                            }
                        ]
                    }
                ]
            }
 
</script>
</html>
