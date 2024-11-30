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

// Función para transferir puntos desde 'usuarios' hacia 'registrados' Si un registrado tiene vencimiento anterior al día actual, este será ajustado para que sea un día después del día actual. Si el vencimiento ya está en el futuro, no se modifica.
function transferPuntosToRegistrados($from_id_usuario, $to_dni, $puntos, &$puntos_actuales) {
    global $mysqli;

    if ($puntos <= 0) {
        return "Los puntos a transferir deben ser mayores a 0.";
    }

    $result_to = mysqli_query($mysqli, "SELECT dni, vencimiento FROM registrados WHERE dni='$to_dni'");
    
    if (mysqli_num_rows($result_to) == 1) {
        $row_to = mysqli_fetch_assoc($result_to);
        $vencimiento = $row_to['vencimiento'];

        // Convertir la fecha de vencimiento a timestamp
        $vencimiento_timestamp = strtotime($vencimiento);
        $fecha_actual_timestamp = time(); // Timestamp actual

        if ($vencimiento_timestamp < $fecha_actual_timestamp) {
            // Fecha de vencimiento ha pasado, sumar un día al actual
            $nuevo_vencimiento = date('Y-m-d', strtotime('+1 day', $fecha_actual_timestamp));
        } else {
            // Fecha de vencimiento es válida, mantenerla
            $nuevo_vencimiento = date('Y-m-d', $vencimiento_timestamp);
        }

        if ($puntos_actuales >= $puntos) {
            // Restar puntos al usuario que envía
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos - $puntos WHERE id_usuario='$from_id_usuario'");
            $puntos_actuales -= $puntos;  // Actualizar la variable local
            
            // Sumar puntos al registrado
            mysqli_query($mysqli, "UPDATE registrados SET credito = credito + $puntos, vencimiento = '$nuevo_vencimiento' WHERE dni='$to_dni'");
            
            // Convertir nuevo vencimiento a formato DD/MM/YYYY para mostrar en el mensaje
            $nuevo_vencimiento_latin = date('d/m/Y', strtotime($nuevo_vencimiento));
            
            return "Transferencia exitosa: $puntos puntos transferidos de usuario $from_id_usuario a registrado con DNI $to_dni. Nuevo vencimiento: $nuevo_vencimiento_latin.";
        } else {
            return "Puntos insuficientes.";
        }
    } else {
        return "Registrado no encontrado.";
    }
}





/*
// Función para transferir puntos desde 'usuarios' hacia 'registrados'
function transferPuntosToRegistrados($from_id_usuario, $to_dni, $puntos, &$puntos_actuales) {
    global $mysqli;

    $result_to = mysqli_query($mysqli, "SELECT dni, vencimiento FROM registrados WHERE dni='$to_dni'");
    
    if (mysqli_num_rows($result_to) == 1) {
        $row_to = mysqli_fetch_assoc($result_to);
        $vencimiento = $row_to['vencimiento'];

        // Convertir la fecha de vencimiento a formato timestamp
        $vencimiento_timestamp = strtotime($vencimiento);
        $fecha_actual_timestamp = time(); // Timestamp actual

        // Verificar si la fecha de vencimiento ha pasado
        if ($vencimiento_timestamp < $fecha_actual_timestamp) {
            return "No se puede transferir puntos. La cuenta del registrado con DNI $to_dni ha vencido.";
        }

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
*/

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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="js/bootstrap.min.css"  crossorigin="anonymous">
    <title>Transferir Puntos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="number"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            font-size: 16px;
            color: #555;
        }

        .form-group input[type="text"] {
            width: 100%;
        }

        .form-group input[type="number"] {
            width: 50%;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            .form-group input[type="number"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transferir Puntos</h1>
        
        <?php if (isset($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        
        <p>Puntos actuales: <?php echo $puntos_actuales; ?></p>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="to_id_usuario">Para Usuario ID (opcional):</label>
                <input type="text" name="to_id_usuario">
            </div>

            <div class="form-group">
                <label for="to_dni">Para DNI (opcional):</label>
                <input type="text" name="to_dni">
            </div>

            <div class="form-group">
                <label for="puntos">Puntos a Transferir:</label>
                <input type="number" name="puntos" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Transferir">
                <h6><a href="app_profe.php" class="badge badge-success">volver</a></h6><br>
            </div>
        </form>
    </div>
</body>
</html>

