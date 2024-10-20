<?php

session_name("app_admin");
session_start();
include("conex.php"); // Your database connection file
include("local_controla.php");

// Function to transfer puntos between users in 'usuarios' table
function transferPuntosUsuarios($from_id_usuario, $to_id_usuario, $puntos) {
    global $mysqli;
    
    // Check if both users exist and have sufficient points
    $result_from = mysqli_query($mysqli, "SELECT puntos FROM usuarios WHERE id_usuario='$from_id_usuario'");
    $result_to = mysqli_query($mysqli, "SELECT id_usuario FROM usuarios WHERE id_usuario='$to_id_usuario'");
    
    if (mysqli_num_rows($result_from) == 1 && mysqli_num_rows($result_to) == 1) {
        $row_from = mysqli_fetch_assoc($result_from);
        
        if ($row_from['puntos'] >= $puntos) {
            // Deduct puntos from sender
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos - $puntos WHERE id_usuario='$from_id_usuario'");
            
            // Add puntos to receiver
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos + $puntos WHERE id_usuario='$to_id_usuario'");
            
            return "Transfer successful: $puntos puntos transferred from user $from_id_usuario to user $to_id_usuario.";
        } else {
            return "Insufficient puntos.";
        }
    } else {
        return "One or both users not found.";
    }
}

// Function to transfer puntos from 'usuarios' to 'registrados' (using 'dni')
function transferPuntosToRegistrados($from_id_usuario, $to_dni, $puntos) {
    global $mysqli;

    // Check if user and registrado exist and if the user has enough points
    $result_from = mysqli_query($mysqli, "SELECT puntos FROM usuarios WHERE id_usuario='$from_id_usuario'");
    $result_to = mysqli_query($mysqli, "SELECT dni FROM registrados WHERE dni='$to_dni'");
    
    if (mysqli_num_rows($result_from) == 1 && mysqli_num_rows($result_to) == 1) {
        $row_from = mysqli_fetch_assoc($result_from);
        
        if ($row_from['puntos'] >= $puntos) {
            // Deduct puntos from sender
            mysqli_query($mysqli, "UPDATE usuarios SET puntos = puntos - $puntos WHERE id_usuario='$from_id_usuario'");
            
            // Add puntos to the 'registrados' table (you may need a puntos field here if it doesn't exist)
            mysqli_query($mysqli, "UPDATE registrados SET credito = credito + $puntos WHERE dni='$to_dni'");
            
            return "Transfer successful: $puntos puntos transferred from user $from_id_usuario to registrado with DNI $to_dni.";
        } else {
            return "Insufficient puntos.";
        }
    } else {
        return "User or registrado not found.";
    }
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_id_usuario = $_POST['from_id_usuario'];
    $to_id_usuario = $_POST['to_id_usuario'];
    $to_dni = $_POST['to_dni'];
    $puntos = $_POST['puntos'];

    if (!empty($to_id_usuario)) {
        // Transfer between usuarios
        echo transferPuntosUsuarios($from_id_usuario, $to_id_usuario, $puntos);
    } elseif (!empty($to_dni)) {
        // Transfer from usuarios to registrados (using dni)
        echo transferPuntosToRegistrados($from_id_usuario, $to_dni, $puntos);
    } else {
        echo "No valid recipient provided.";
    }
}
?>

<!-- HTML Form for Testing -->
<!DOCTYPE html>
<html>
<head>
    <title>Transfer Puntos</title>
</head>
<body>
    <h1>Transfer Puntos</h1>
    <form method="POST" action="">
        <label for="from_id_usuario">From User ID:</label>
        <input type="text" name="from_id_usuario" required><br><br>

        <label for="to_id_usuario">To User ID (optional):</label>
        <input type="text" name="to_id_usuario"><br><br>

        <label for="to_dni">To DNI (optional):</label>
        <input type="text" name="to_dni"><br><br>

        <label for="puntos">Puntos to Transfer:</label>
        <input type="number" name="puntos" required><br><br>

        <input type="submit" value="Transfer">
    </form>
</body>
</html>
