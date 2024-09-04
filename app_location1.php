<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_name('app_reservas');
session_start();
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba'); // poner en todos los archivos

if(isset($_SESSION['usuario_act'])) { 
    // Usuario está autenticado
    // Puedes agregar lógica adicional aquí si es necesario
} else {
    // Redirigir a login si no está autenticado
    // header("Location:login_inscripcion.php");
}

// Incluir jQuery
?>
<script src="js/jquery-3.6.0.js"></script>
<?php

// Manejo de invitado_dni para mostrar el modal
if(isset($_GET['invitado_dni'])){
    ?>
    <script>
    $(document).ready(function(){
        $('#myModal').modal('show');
    });
    </script>
    
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
            
                <div class="modal-body">
                    <h5 class="modal-title">CLAVE LOKALES</h5>
                    <label>Usuario</label>
                    <input type="text" name="dni" id="dni" class="form-control" value="<?php echo htmlspecialchars($_GET['invitado_dni']); ?>" readonly>
                    
                    <div id="cliente_encontrado">
                        <label>Clave</label>
                        <input type="text" name="cla" id="cla" class="form-control" value="<?php echo htmlspecialchars($_GET['invitado_cla']); ?>" readonly>
                        
                        <label>Nombre</label>
                        <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($_GET['invitado_nom']); ?>" class="form-control" readonly>
                        
                        <label>Mail</label>
                        <input type="email" name="mai" id="mai" class="form-control" value="<?php echo htmlspecialchars($_GET['invitado_mai']); ?>" readonly>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
                
            </div>
        </div>
    </div>
    <?php
}

// Obtener todas las sucursales
$sucursales_query = "SELECT * FROM actividad_sucursal ORDER BY nombre";
$sucursales_result = mysqli_query($mysqli, $sucursales_query);

if(!$sucursales_result){
    die("Error al obtener las sucursales: " . mysqli_error($mysqli));
}

// Verificar si se ha seleccionado una sucursal
$sucursal_id = null;
if(isset($_GET['sucursal_id'])){
    $sucursal_id = intval($_GET['sucursal_id']); // Convertir a entero para seguridad
}

// Obtener las actividades filtradas por sucursal si es necesario
if($sucursal_id){
    // Suponiendo que 'actividad_horarios' tiene 'actividad_id' y 'sucursal_id_sucursal'
    $stmt = $mysqli->prepare("
        SELECT DISTINCT a.* 
        FROM actividad a
        JOIN actividad_horarios ah ON a.id_actividad = ah.actividad_id
        WHERE a.activa = 'S' AND ah.sucursal_id_sucursal = ?
        ORDER BY a.nombre
    ");
    if($stmt === false){
        die("Error en la preparación de la consulta: " . $mysqli->error);
    }
    $stmt->bind_param("i", $sucursal_id);
    $stmt->execute();
    $actividades = $stmt->get_result();
    $stmt->close();
} else {
    // Si no hay sucursal seleccionada, mostrar todas las actividades activas
    $actividades = mysqli_query($mysqli, "SELECT * FROM actividad WHERE activa='S' ORDER BY nombre");
    if(!$actividades){
        die("Error al obtener las actividades: " . mysqli_error($mysqli));
    }
}

// Obtener el crédito del usuario si está autenticado
if(isset($_SESSION['usuario_act'])) { 
    // Usar declaraciones preparadas para mayor seguridad
    $stmt_user = $mysqli->prepare("SELECT * FROM registrados WHERE dni = ?");
    if($stmt_user === false){
        die("Error en la preparación de la consulta de usuario: " . $mysqli->error);
    }
    $stmt_user->bind_param("s", $_SESSION['usuario_act']);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $dato_usuario = $result_user->fetch_assoc();	
    $credito_actual = $dato_usuario['credito'];
    $stmt_user->close();
} else {
    $credito_actual = 0;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Lokales Socio</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilo.css" rel="stylesheet" type="text/css">
    <link href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="js/bootstrap.min.css" crossorigin="anonymous">
    
    <!-- Bootstrap JS and dependencies -->
    <script src="js/jquery-3.6.0.js" defer></script>
    <script src="js/popper.min.js" defer></script>
    <script src="js/bootstrap.bundle.min.js" defer></script>
    
    <script>
    /*
    document.addEventListener("DOMContentLoaded", function(){
        // llamamos cada 500 segundos ;)
        const milisegundos = 5 *1000;
        setInterval(function(){
            // No esperamos la respuesta de la petición porque no nos importa
            fetch("./refrescar.php");
        },milisegundos);
    });
    */
    </script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
            <a class="navbar-brand" href="app_actividades.php"><img src="logo.gif" class="img-fluid" alt="Lokales Training spot logo image" width="200"></a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="info_puntos.php"><span class="badge badge-pill badge-info">info puntos</span></a>
                    </li>
                <?php
                if(!isset($_SESSION['usuario_act'])) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login_inscripcion.php">Registrarte</a>
                    </li>
                <?php
                }
                else
                {
                ?>
                    <li class="nav-item">
                        <h6>
                            <a class="nav-link">
                                <span class="badge badge-pill badge-light"><?php echo htmlspecialchars($dato_usuario['nombre']); ?></span>
                                <span class="badge badge-pill badge-dark"><?php echo htmlspecialchars($credito_actual); ?></span> puntos
                            </a>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="app_kill.php">cerrar sesión</a>
                    </li>
                <?php
                }
                ?>
                </ul>
                <!--
                <form class="form-inline mt-2 mt-md-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Buscar">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
                </form>
                -->
            </div>
        </nav>
    </header>

    <div class="container" style="margin-top:30px">
        
        <!-- Sección de selección de sucursal -->
        <div class="row mb-4">
            <?php
            // Reiniciar el puntero del resultado para poder iterar nuevamente
            mysqli_data_seek($sucursales_result, 0);
            while($sucursal = mysqli_fetch_assoc($sucursales_result)):
                // Determinar si esta sucursal está seleccionada
                $active = ($sucursal_id == $sucursal['id_sucursal']) ? 'btn-primary' : 'btn-secondary';
            ?>
                <div class="col-md-3 mb-2">
                    <a href="app_actividades.php?sucursal_id=<?php echo $sucursal['id_sucursal']; ?>" class="btn <?php echo $active; ?> btn-block">
                        <?php echo htmlspecialchars($sucursal['nombre']); ?>
                    </a>
                </div>
            <?php endwhile; ?>
            <!-- Botón para mostrar todas las sucursales -->
            <div class="col-md-3 mb-2">
                <a href="app_actividades.php" class="btn <?php echo is_null($sucursal_id) ? 'btn-primary' : 'btn-secondary'; ?> btn-block">
                    Todas las Sucursales
                </a>
            </div>
        </div>
        <hr>
        
        <!-- Sección para mostrar actividades -->
        <?php
        $cuenta_act = 0;
        while($actividad = mysqli_fetch_array($actividades)) {
            if($cuenta_act == 0) {
                echo '<div class="row">';
            }
        ?>
            <div class="col-xs-12 col-md-4 caja_actividad">
                <div class="card text-center caja_individual">
                    <div class="card-body">
                        <h5>
                            <a href="app_reserva.php?act_seleccionada=<?php echo $actividad['id_actividad']; ?>" id="btn-act">
                                <?php echo htmlspecialchars($actividad['nombre']); ?>
                            </a>
                        </h5>
                        <img src="<?php echo htmlspecialchars($actividad['imagen']); ?>" class="img-fluid caja_imagen" alt="<?php echo htmlspecialchars($actividad['nombre']); ?>">
                        <p class="card-text"><?php echo htmlspecialchars($actividad['descripcion']); ?></p>
                        <a href="app_reserva.php?act_seleccionada=<?php echo $actividad['id_actividad']; ?>" class="btn btn-primary">Horarios</a>
                    </div>
                </div>
            </div>
        <?php
            $cuenta_act++;

            if($cuenta_act == 3) {
                echo '</div>';
            }

            if($cuenta_act == 3){
                $cuenta_act = 0;
            }
        }

        // Cerrar el último row si no está cerrado
        if($cuenta_act != 0){
            echo '</div>';
        }
        ?>
    </div>
    <br><br>

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
            <!--
            <div class="col">
                <a href="app_mensajes.php"><img src="svg/svgmessage.svg" class="barra" alt="Mensajes"/></a>
                <br><h6 class="chica">Mensajes</h6>
            </div>
            -->
            <div class="col">
                <a href="app_perfil.php"><img src="svg/svgsettings-brain.svg" class="barra" alt="Ajustes"/>
                <br></a><h6 class="chica">Ajustes</h6>
            </div>
        </div>
    </footer>
</body>
</html>
