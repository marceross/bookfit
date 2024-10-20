<?php

session_name("app_admin");
session_start();
include("conex.php");
include("local_controla.php");

$id_usuario = $_SESSION['usuario_act'];

// Obtener puntos actuales del usuario logueado
$result_user = mysqli_query($mysqli, "SELECT puntos FROM usuarios WHERE id_usuario='$id_usuario'");
$row_user = mysqli_fetch_assoc($result_user);
$puntos_actuales = $row_user['puntos'];

// Función para transferir puntos entre usuarios
function transferPuntosUsuarios($from_id_usuario, $to_id_usuario, $puntos, &$puntos_actuales) {
    global $mysqli;

    $result_to = mysqli_query($mysqli, "SELECT id_usuario FROM usuarios WHERE id_usuario='$to_id_usuario'");
    
    if (mysqli_num_rows($result_to) == 1) {
        if ($from_id_usuario == $to_id_usuario) {
            return "No puedes mandarte puntos a ti mismo.";
        }
        
        if ($puntos_actuales >= $puntos) {
            // Restar puntos al usuario que envía
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos - $puntos WHERE id_usuario='$from_id_usuario'");
            $puntos_actuales -= $puntos;  // Actualizar la variable local
            
            // Sumar puntos al usuario que recibe
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos + $puntos WHERE id_usuario='$to_id_usuario'");
            
            return "Transferencia exitosa: $puntos puntos transferidos de usuario $from_id_usuario a usuario $to_id_usuario.";
        } else {
            return "Puntos insuficientes.";
        }
    } else {
        return "Usuario no encontrado.";
    }
}

// Función para transferir puntos desde 'usuarios' hacia 'registrados'
function transferPuntosToRegistrados($from_id_usuario, $to_dni, $puntos, &$puntos_actuales) {
    global $mysqli;

    $result_to = mysqli_query($mysqli, "SELECT dni FROM registrados WHERE dni='$to_dni'");
    
    if (mysqli_num_rows($result_to) == 1) {
        if ($puntos_actuales >= $puntos) {
            // Restar puntos al usuario que envía
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos - $puntos WHERE id_usuario='$from_id_usuario'");
            $puntos_actuales -= $puntos;  // Actualizar la variable local
            
            // Sumar puntos al registrado
            mysqli_query($mysqli, "UPDATE registrados SET credito = credito + $puntos WHERE dni='$to_dni'");
            
            return "Transferencia exitosa: $puntos puntos transferidos de usuario $from_id_usuario a registrado con DNI $to_dni.";
        } else {
            return "Puntos insuficientes.";
        }
    } else {
        return "Registrado no encontrado.";
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to_id_usuario = $_POST['to_id_usuario'];
    $to_dni = $_POST['to_dni'];
    $puntos = $_POST['puntos'];
    $mensaje = '';

    if (!empty($to_id_usuario) && !empty($to_dni)) {
        $mensaje = "Por favor, completa solo un campo (Usuario ID o DNI) para realizar la transferencia.";
    } elseif (!empty($to_id_usuario)) {
        $mensaje = transferPuntosUsuarios($id_usuario, $to_id_usuario, $puntos, $puntos_actuales);
    } elseif (!empty($to_dni)) {
        $mensaje = transferPuntosToRegistrados($id_usuario, $to_dni, $puntos, $puntos_actuales);
    } else {
        $mensaje = "Debe proporcionar un destinatario válido.";
    }

    // Redirigir usando PRG para evitar reenvío de datos al refrescar
    header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=" . urlencode($mensaje));
    exit;
}

// Mostrar mensaje de la operación si existe
if (isset($_GET['mensaje'])) {
    $mensaje = urldecode($_GET['mensaje']);
}

?>

<!-- HTML Formulario para Transferir Puntos -->
<!DOCTYPE html>
<html>
<head>
    <title>Transferir Puntos</title>
</head>
<body>
    <h1>Transferir Puntos</h1>
    
    <?php if (isset($mensaje)) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
    
    <p>Puntos actuales: <?php echo $puntos_actuales; ?></p>
    
    <form method="POST" action="">
        <label for="to_id_usuario">Para Usuario ID (opcional):</label>
        <input type="text" name="to_id_usuario"><br><br>

        <label for="to_dni">Para DNI (opcional):</label>
        <input type="text" name="to_dni"><br><br>

        <label for="puntos">Puntos a Transferir:</label>
        <input type="number" name="puntos" required><br><br>

        <input type="submit" value="Transferir">
    </form>
</body>
</html>
