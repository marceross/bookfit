<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_name('app_reservas');
session_start();
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba');

if (!isset($_SESSION['usuario_act'])) {
    // header("Location:login_inscripcion.php");
}

if (isset($_GET['invitado_dni'])) {
?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myModal').modal('show');
    });
    </script>

    <div class="modal" id="myModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title">CLAVE LOKALES</h5>
                    <label>Usuario</label>
                    <input type="text" name="dni" id="dni" class="form-control" value="<?php echo $_GET['invitado_dni']; ?>" readonly>
                    <div id="cliente_encontrado">
                        <label>Clave</label>
                        <input type="text" name="cla" id="cla" class="form-control" value="<?php echo $_GET['invitado_cla']; ?>" readonly>
                        <label>Nombre</label>
                        <input type="nombre" name="nom" id="nom" value="<?php echo $_GET['invitado_nom']; ?>" class="form-control" readonly>
                        <label>Mail</label>
                        <input type="email" name="mai" id="mai" class="form-control" value="<?php echo $_GET['invitado_mai']; ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
<?php
}

$sucursales = mysqli_query($mysqli, "SELECT * FROM actividad_sucursal ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Lokales Socio</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilo.css" rel="stylesheet" type="text/css">
    <link href="https://lokales.com.ar/favico.ico" rel="shortcut icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
                    <?php if (!isset($_SESSION['usuario_act'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login_inscripcion.php">Registrarte</a>
                        </li>
                    <?php } else {
                        $datos_usuarios = mysqli_query($mysqli, "SELECT * FROM registrados WHERE dni='" . $_SESSION['usuario_act'] . "'");
                        $dato_usuario = mysqli_fetch_array($datos_usuarios);
                        $credito_actual = $dato_usuario['credito'];
                    ?>
                        <li class="nav-item">
                            <h6><a class="nav-link"><span class="badge badge-pill badge-light"><?php echo $dato_usuario['nombre']; ?></span><span class="badge badge-pill badge-dark"><?php echo $credito_actual; ?></span> puntos</a></h6>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="app_kill.php">cerrar sesión</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container" style="margin-top:80px">
        <h1 class="text-center">Selecciona una Sucursal</h1>
        <div class="row mt-4">
            <?php while ($sucursal = mysqli_fetch_array($sucursales)) { ?>
                <div class="col-md-4">
                    <button class="btn btn-primary btn-block" onclick="seleccionarSucursal(<?php echo $sucursal['id_sucursal']; ?>)">
                        <?php echo $sucursal['nombre']; ?>
                    </button>
                </div>
            <?php } ?>
        </div>

        <br><br>
        <div id="actividades" style="display:none;">
            <h2>Actividades Disponibles</h2>
            <div class="row mt-4">
                <?php
                $actividades = mysqli_query($mysqli, "SELECT * FROM actividad WHERE activa='S' ORDER BY nombre");
                $cuenta_act = 0;
                while ($actividad = mysqli_fetch_array($actividades)) {
                    if ($cuenta_act == 0) { ?>
                        <div class="row">
                    <?php }
                    ?>
                    <div class="col-xs-12 col-md-4 caja_actividad">
                        <div class="card text-center caja_individual">
                            <div class="card-body">
                                <h5><a href="app_reserva.php?act_seleccionada=<?php echo $actividad['id_actividad']; ?>" id="btn-act"><?php echo $actividad['nombre']; ?></a></h5>
                                <img src="<?php echo $actividad['imagen']; ?>" class="img-fluid caja_imagen" alt="<?php echo $actividad['nombre']; ?>">
                                <p class="card-text"><?php echo $actividad['descripcion']; ?></p>
                                <a href="app_reserva.php?act_seleccionada=<?php echo $actividad['id_actividad']; ?>" class="btn btn-primary">Horarios</a>
                            </div>
                        </div>
                    </div>
                    <?php
                    $cuenta_act++;
                    if ($cuenta_act == 3) { ?>
                        </div>
                    <?php $cuenta_act = 0;
                    }
                } ?>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom bg-light">
        <div class="row">
            <div class="col">
                <a href="app_actividades.php"><img src="svg/svgbike.svg" class="barra" alt="Actividades"></a>
                <br>
                <h6 class="chica">Actividades</h6>
            </div>
            <div class="col">
                <a href="app_agenda.php"><img src="svg/svghora.svg" class="barra" alt="Agenda" /></a>
                <br>
                <h6 class="chica">Agenda</h6>
            </div>
            <div class="col">
                <a href="app_perfil.php"><img src="svg/svgsettings-brain.svg" class="barra" alt="Ajustes" />
                    <br></a>
                <h6 class="chica">Ajustes</h6>
            </div>
        </div>
    </footer>

    <script>
        function seleccionarSucursal(idSucursal) {
            localStorage.setItem('sucursalSeleccionada', idSucursal);
            document.getElementById('actividades').style.display = 'block';
        }
    </script>
</body>

</html>
