<?php

session_name("app_admin");
session_start();
include("conex.php"); // Archivo de conexión a la base de datos
include("local_controla.php"); // Controla quién está conectado y provee el ID del usuario actual

// Obtenemos el ID del usuario actual desde la sesión
$id_usuario = $_SESSION['usuario_act'];

// Función para transferir puntos entre usuarios en la tabla 'usuarios'
function transferPuntosUsuarios($from_id_usuario, $to_id_usuario, $puntos) {
    global $mysqli;
    
    // Verificamos si ambos usuarios existen y si el usuario que envía tiene suficientes puntos
    $result_from = mysqli_query($mysqli, "SELECT puntos FROM usuarios WHERE id_usuario='$from_id_usuario'");
    $result_to = mysqli_query($mysqli, "SELECT id_usuario FROM usuarios WHERE id_usuario='$to_id_usuario'");
    
    if (mysqli_num_rows($result_from) == 1 && mysqli_num_rows($result_to) == 1) {
        $row_from = mysqli_fetch_assoc($result_from);
        
        if ($row_from['puntos'] >= $puntos) {
            // Restar puntos al usuario que envía
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos - $puntos WHERE id_usuario='$from_id_usuario'");
            
            // Sumar puntos al usuario que recibe
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos + $puntos WHERE id_usuario='$to_id_usuario'");
            
            return "Transferencia exitosa: $puntos puntos transferidos de usuario $from_id_usuario a usuario $to_id_usuario.";
        } else {
            return "Puntos insuficientes.";
        }
    } else {
        return "Uno o ambos usuarios no encontrados.";
    }
}

// Función para transferir puntos desde 'usuarios' hacia 'registrados' (usando 'dni')
function transferPuntosToRegistrados($from_id_usuario, $to_dni, $puntos) {
    global $mysqli;

    // Verificamos si el usuario y el registrado existen y si el usuario tiene suficientes puntos
    $result_from = mysqli_query($mysqli, "SELECT puntos FROM usuarios WHERE id_usuario='$from_id_usuario'");
    $result_to = mysqli_query($mysqli, "SELECT dni FROM registrados WHERE dni='$to_dni'");
    
    if (mysqli_num_rows($result_from) == 1 && mysqli_num_rows($result_to) == 1) {
        $row_from = mysqli_fetch_assoc($result_from);
        
        if ($row_from['puntos'] >= $puntos) {
            // Restar puntos al usuario que envía
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos - $puntos WHERE id_usuario='$from_id_usuario'");
            
            // Sumar puntos al registrado (se debe tener una columna 'puntos' en la tabla 'registrados')
            mysqli_query($mysqli, "UPDATE registrados SET credito = credito + $puntos WHERE dni='$to_dni'");
            
            return "Transferencia exitosa: $puntos puntos transferidos de usuario $from_id_usuario a registrado con DNI $to_dni.";
        } else {
            return "Puntos insuficientes.";
        }
    } else {
        return "Usuario o registrado no encontrados.";
    }
}

// Uso del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to_id_usuario = $_POST['to_id_usuario'];
    $to_dni = $_POST['to_dni'];
    $puntos = $_POST['puntos'];

    if (!empty($to_id_usuario)) {
        // Transferir puntos entre usuarios
        echo transferPuntosUsuarios($id_usuario, $to_id_usuario, $puntos);
    } elseif (!empty($to_dni)) {
        // Transferir puntos a un registrado (usando DNI)
        echo transferPuntosToRegistrados($id_usuario, $to_dni, $puntos);
    } else {
        echo "Debe proporcionar un destinatario válido.";
    }
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
