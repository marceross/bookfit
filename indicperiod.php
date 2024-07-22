<?php
include 'conex.php';
include 'conex_history.php';
session_start();
$actividad = '';
$enActividad = false;
$AverageActivity = false;
$Revenue_per_session = false;
$Revenue_per_client = false;
$out_term = false;
$lead = false;
$conversion = false;
$gross_profit = false;
$retention = false;
$gym = false;
if (isset($_POST['first_month']) && isset($_POST['last_month'])) {
    $first_month = $_POST['first_month'];
    $last_month = $_POST['last_month'];
} else {
    $first_month = date('Y-m');
    $last_month = date('Y-m');
}
$query_period = mysqli_query($mysqli_history, "select *, DATE_FORMAT(fecha,'%M %Y')date from record where DATE_FORMAT(fecha,'%Y-%m') BETWEEN DATE_FORMAT('".$first_month."-01','%Y-%m') and DATE_FORMAT('".$last_month."-01','%Y-%m')  order by fecha");
$history_data = array();
while ($historyData = mysqli_fetch_array($query_period)) {
    $item['leads'] = $historyData['leads'];
    $item['conrate'] = $historyData['conrate'];
    $item['retrate'] = $historyData['retrate'];
    $item['earusa'] = $historyData['earusa'];
    $item['avecla'] = $historyData['avecla'];
    $item['avedai'] = $historyData['avedai'];
    $item['revcli'] = $historyData['revcli'];
    $item['revses'] = $historyData['revses'];
    $item['revsqu'] = $historyData['revsqu'];
    $item['outpay'] = $historyData['outpay'];
    $item['revenue'] = $historyData['revenue'];
    $item['cost'] = $historyData['cost'];
    $item['date'] = $historyData['date'];
    array_push($history_data, $item);
}

if (isset($_GET['idactividad']) || isset($_POST['idactividad'])) {
    $enActividad = true;
    $state = 'idactividad';
}
if (isset($_GET['retention']) || isset($_POST['retention'])) {
    $retention = true;
    $state = 'retention';
} elseif (isset($_GET['lead']) || isset($_POST['lead'])) {
    $lead = true;
    $state = 'lead';
} elseif (isset($_GET['conversion']) || isset($_POST['conversion'])) {
    $conversion = true;
    $state = 'conversion';
} elseif (isset($_GET['AverageActivity']) || isset($_POST['AverageActivity'])) {
    $AverageActivity = true;
    $state = 'AverageActivity';
} elseif (isset($_GET['Revenue_per_session']) || isset($_POST['Revenue_per_session'])) {
    $Revenue_per_session = true;
    $state = 'Revenue_per_session';
} elseif (isset($_GET['Revenue_per_client']) || isset($_POST['Revenue_per_client'])) {
    $Revenue_per_client = true;
    $state = 'Revenue_per_client';
} elseif (isset($_GET['gym']) || isset($_POST['gym'])) {
    $gym = true;
    $state = 'gym';
} elseif (isset($_GET['out_term']) || isset($_POST['out_term'])) {
    $out_term = true;
    $state = 'out_term';
} elseif (isset($_GET['gross_profit']) || isset($_POST['gross_profit'])) {
    $gross_profit = true;
    $state = 'gross_profit';
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
        <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">  
        <style>
        .btn-danger {
            text-align: center;
            line-height: 18px;
            -webkit-font-smoothing: antialiased;
            display: inline-block;
            border-radius: 3px;
            padding: 11px  18px;
            background: -webkit-linear-gradient(top,red 0,red 100%);
            background: linear-gradient(to bottom,red 0,red 100%);
            border: 1px solid #0087e0;
            color: #F7F7F7;
            font-weight: 700;
            text-shadow: 0 -1px transparent;
            cursor: pointer;
            border:unset;
        }
        </style>  
    </head>
    <body>
        <div class="container">
            <div class="head">
            </div>
            <div class="body">
                <form name="form" class="mt-3" method="post" action="indicperiod.php">
                    <div class="row">                
                        <div class="offset-lg-2 col-lg-3">
                            <input type="month" name="first_month" class="form-control" value="<?php echo $first_month; ?>">
                        </div>
                        <div class="col-lg-3">
                            <input type="month" name="last_month" class="form-control" value="<?php echo $last_month; ?>">
                            <input type="hidden" name="<?php echo $state; ?>" class="form-control" value="<?php echo $state; ?>">
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-danger btn-block" type="submit"><i class="fa fa-search"></i>  </button>
                        </div>
                    </div>
                </form>
                <?php
                if ($lead) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Lead</th>      
                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                 foreach ($history_data as $actividad => $item) {
                                     ?>
                                    <tr>                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['leads']; ?></td> 
                                    </tr>
                                <?php
                                 } ?>                                    
                                </tbody>
                            </table>
                        </div>
                    <?php
                } elseif ($conversion) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Conversion rate</th>                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($history_data as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['conrate']; ?> %</td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($retention) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Tasa de retencion de clientes</th>                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($history_data as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['retrate']; ?> %</td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($enActividad) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Early stage usage</th>                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($history_data as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['earusa']; ?></td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($AverageActivity) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Average class attendance</th>                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($history_data as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['avecla']; ?> %</td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                                                   
                        <?php
                } elseif ($Revenue_per_client) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Revenue per client</th>      
                                                                              
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                        foreach ($history_data as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['revcli']; ?> $</td> 
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    <?php
                } elseif ($Revenue_per_session) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">                               
                                <tbody> 
                                    <tr>
                                        <th class="text-center">Month</th>
                                        <th class="text-center">Revenue per session</th>  
                                                                              
                                    </tr>
                                <?php
                                        foreach ($history_data as $actividad => $item) {
                                            ?>
                                        <tr>                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['revses']; ?> $</td>
                                    </tr>
                                    <?php
                                        } ?>                                       
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php
                } elseif ($gym) {
                    ?>
                     <div class="table-responsive table-bordered mt-3">
                        <table class="table">
                               
                                <tbody> 
                                        <tr>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Average daily attendance</th>
                                        </tr>
                                <?php

                                        foreach ($history_data as $actividad => $item) {
                                            ?>
                                        <tr>
                                  
                                            <td class="text-center"><?php echo $item['date']; ?></td>
                                            <td class="text-center"><?php echo $item['avedai']; ?></td>
                                       
                                    </tr>
                                    <?php
                                        } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                 <?php
                } elseif ($out_term) {
                    ?>
                        
                        <div class="table-responsive table-bordered mt-3">
                            <table class="table">  
                                <thead>
                                     <tr >
                                        <th class="text-center" >Month</th>
                                        <th class="text-center" >out of term</th>
                                        </tr> 
                                </thead>                             
                                <tbody>  
                                    <?php
                                        foreach ($history_data as $actividad => $item) {
                                            ?>                                 
                                    <tr >
                                        <td class="text-center" ><?php echo $item['date']; ?></td>
                                        <td class="text-center" ><?php echo $item['outpay']; ?></td>
                                    </tr>
                                        <?php
                                        } ?>
                                </tbody>
                            </table>
                           
                        </div>    
                        <?php
                } elseif ($gross_profit) {
                    ?>
                        
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
                                        foreach ($history_data as $actividad => $item) {
                                            ?>                                 
                                    <tr >
                                        <td class="text-center" ><?php echo $item['date']; ?></td>
                                        <td class="text-center" ><?php echo $item['revenue']; ?> $</td>
                                        <td class="text-center" ><?php echo $item['cost']; ?> $</td>
                                        <td class="text-center" ><?php echo round($item['revenue'] - $item['cost'], 0); ?> $</td>
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
                if ($enActividad || $AverageActivity || $Revenue_per_client || $Revenue_per_session || $out_term || $lead || $conversion || $gross_profit || $retention || $gym) {
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
         
         $("#search").on("click", function(e){
            var first_month=$("#first_month").val();
            var last_month=$("#last_month").val();
            var state = <?php echo json_encode($state); ?>;            
            $.ajax({
                 timeout: 100000,
                    cache: false,
                    type:"post",
                    url:"indicperiod.php",
                    data: { first_month: first_month, last_month: last_month,state:state},
                    dataType: "text",
                    success:function(data) {            
                        console.log(data);
                    },
                    error:function(data) {
                        alert("Ha ocurrido un error en la petición");
                    }
                });

         });
    });

</script>   
    </body>
</html>