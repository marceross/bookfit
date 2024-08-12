<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include("conex.php");


//https://crontab.guru/#0_5_*_*_1
// cronjob
// 05**1
//“At 05:00 on Monday.”

//HACE LA RESERVA DE LOS USUARIOS CON LA OPCION RESERVA AUTO SELECCIONADA
//BUSCA LAS RESERVAS DE LAS SEMANA ANTERIOR Y LAS REPITE A LA SEMANA ACTUAL
//SE FIJA QUE LA ACTIVIDAD NO ESTE SUSPENDIDA
//SE FIJA QUE EL USUARIO TENGA CREDITO
//HACE LA RESERVA Y DESCUENTA EL CREDITO


// Get all users with auto-reservation option
$eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE reserva_auto = 1");

while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    // Check if the user made reservations last week
    $lastWeek = strtotime('-1 week');
    $reservationsLastWeekQuery = mysqli_query($mysqli, "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");

    if (mysqli_num_rows($reservationsLastWeekQuery) > 0) {
        // User made reservations last week, automate reservations for the same time slots

        // Get the time slots reserved by the user last week (assuming it's the same time slots for demonstration)
        $timeSlotsLastWeekQuery = mysqli_query($mysqli, "SELECT DISTINCT ahr.actividad_horarios_id_horario, ahr.fecha, ah.costo
            FROM actividad_reservas ahr
            JOIN actividad_horarios ah ON ahr.actividad_horarios_id_horario = ah.id_horario
            WHERE ahr.registrados_dni='" . $user['dni'] . "' AND ahr.fecha >= '" . date('Y-m-d', $lastWeek) . "'");
        
        while ($row = mysqli_fetch_assoc($timeSlotsLastWeekQuery)) {
            $id_horario = $row['actividad_horarios_id_horario'];
            $fechaLastWeek = new DateTime($row['fecha']);
            $fechaLastWeek->modify('+1 week');
            $fecha = $fechaLastWeek->format('Y-m-d');
            $costo = $row['costo'];

            // Check if the id_horario and fecha combination exists in actividad_horarios_susp
            $isSuspendedQuery = mysqli_query($mysqli, "SELECT * FROM actividad_horarios_susp WHERE actividad_horarios_id_horario2='" . $id_horario . "' AND fecha='" . $fecha . "'");

            if (mysqli_num_rows($isSuspendedQuery) == 0) {
                // Check if the user has enough credits for the reservation
                if ($user['credito'] >= $costo) {
                    
                    $if_check = "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha='" . $fecha . "' AND actividad_horarios_id_horario='".$id_horario."'";
                    //echo $if_check;
                    $if_check = mysqli_query($mysqli,$if_check);
                    
                    if (mysqli_num_rows($if_check) == 0) {
                    
                   
                    
                    // Insert reservation record
                    mysqli_query($mysqli, "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('" . $user['dni'] . "', '" . $fecha . "', '" . $id_horario . "')");

                    // Deduct the cost from the user's credits
                    mysqli_query($mysqli, "UPDATE registrados SET credito = credito - " . $costo . " WHERE dni = '" . $user['dni'] . "'");

                    echo "Reservation made for User: " . $user['nombre'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
                    }else{
                        echo 'Already Reservation maded ';
                    }
                    
                } else {
                    echo "Insufficient credits for User: " . $user['nombre'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
                }
            } else {
                echo "Reservation not made for User: " . $user['nombre'] . ", DNI: " . $user['dni'] . " for the same time slot as last week due to suspension<br>";
            }
        }
    }
}




/*
// HACE LA RESERVA DESCONTANDO EL CREDITO
include("conex.php");

// Get all users with auto-reservation option
$eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE reserva_auto = 1");

while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    // Check if the user made reservations last week
    $lastWeek = strtotime('-1 week');
    $reservationsLastWeekQuery = mysqli_query($mysqli, "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");

    if (mysqli_num_rows($reservationsLastWeekQuery) > 0) {
        // User made reservations last week, automate reservations for the same time slots

        // Get the time slots reserved by the user last week (assuming it's the same time slots for demonstration)
        $timeSlotsLastWeekQuery = mysqli_query($mysqli, "SELECT DISTINCT ahr.actividad_horarios_id_horario, ahr.fecha, ah.costo
            FROM actividad_reservas ahr
            JOIN actividad_horarios ah ON ahr.actividad_horarios_id_horario = ah.id_horario
            WHERE ahr.registrados_dni='" . $user['dni'] . "' AND ahr.fecha >= '" . date('Y-m-d', $lastWeek) . "'");
        
        while ($row = mysqli_fetch_assoc($timeSlotsLastWeekQuery)) {
            $id_horario = $row['actividad_horarios_id_horario'];
            $fechaLastWeek = new DateTime($row['fecha']);
            $fechaLastWeek->modify('+1 week');
            $fecha = $fechaLastWeek->format('Y-m-d');
            $costo = $row['costo'];

            // Check if the user has enough credits for the reservation
            if ($user['credito'] >= $costo) {
                // Insert reservation record
                mysqli_query($mysqli, "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('" . $user['dni'] . "', '" . $fecha . "', '" . $id_horario . "')");

                // Deduct the cost from the user's credits
                mysqli_query($mysqli, "UPDATE registrados SET credito = credito - " . $costo . " WHERE dni = '" . $user['dni'] . "'");

                echo "Reservation made for User: " . $user['name'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
            } else {
                echo "Insufficient credits for User: " . $user['name'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
            }
        }
    }
}*/


/*

HACE LA RESERVA, BIEN LA FECHA

include("conex.php");


// Get all users with auto-reservation option
$eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE reserva_auto = 1");

while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    // Check if the user made reservations last week
    $lastWeek = strtotime('-1 week');
    $reservationsLastWeekQuery = mysqli_query($mysqli, "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");

    if (mysqli_num_rows($reservationsLastWeekQuery) > 0) {
        // User made reservations last week, automate reservations for the same time slots

        // Get the time slots reserved by the user last week (assuming it's the same time slots for demonstration)
        $timeSlotsLastWeekQuery = mysqli_query($mysqli, "SELECT DISTINCT actividad_horarios_id_horario, fecha FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");
        
        while ($row = mysqli_fetch_assoc($timeSlotsLastWeekQuery)) {
            $id_horario = $row['actividad_horarios_id_horario'];
            
            // Calculate fecha for the current week by adding 1 week to the fecha from the database
            $fechaLastWeek = new DateTime($row['fecha']);
            $fechaLastWeek->modify('+1 week');
            $fecha = $fechaLastWeek->format('Y-m-d');

            // Insert reservation record
            mysqli_query($mysqli, "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('" . $user['dni'] . "', '" . $fecha . "', '" . $id_horario . "')");

            echo "Reservation made for User: " . $user['name'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
        }
    }
}*/



/*
include("conex.php");

// Get all users with auto-reservation option
$eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE reserva_auto = 1");

while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    // Check if the user made reservations last week
    $lastWeek = strtotime('-1 week');
    $reservationsLastWeekQuery = mysqli_query($mysqli, "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");

    if (mysqli_num_rows($reservationsLastWeekQuery) > 0) {
        // User made reservations last week, automate reservations for the same time slots

        // Get the time slots reserved by the user last week (assuming it's the same time slots for demonstration)
        $timeSlotsLastWeekQuery = mysqli_query($mysqli, "SELECT DISTINCT actividad_horarios_id_horario FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");
        
        while ($row = mysqli_fetch_assoc($timeSlotsLastWeekQuery)) {
            $id_horario = $row['actividad_horarios_id_horario'];
            $fecha = date('Y-m-d'); // Current date

            // Insert reservation record
            mysqli_query($mysqli, "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('" . $user['dni'] . "', '" . $fecha . "', '" . $id_horario . "')");

            echo "Reservation made for User: " . $user['name'] . ", DNI: " . $user['dni'] . " for the same time slot as last week<br>";
        }
    }
}*/



/*
include("conex.php");

// Check for users with auto-reservation option
$eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE reserva_auto = 1");

while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    echo "User with Auto-Reservation Option: ";
    echo "ID: " . $user['id'] . ", ";
    echo "Name: " . $user['name'] . ", ";
    echo "DNI: " . $user['dni'] . "<br>";
}*/



/*

include("conex.php");

// Get all users with auto-reservation option
$eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE reserva_auto = 1");

while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    // Example: Reserve the first time slot for the current week for each eligible user
    $firstTimeSlotQuery = mysqli_query($mysqli, "SELECT * FROM actividad_horarios LIMIT 1");
    $firstTimeSlot = mysqli_fetch_assoc($firstTimeSlotQuery);

    $id_horario = $firstTimeSlot['id_horario'];
    $fecha = date('Y-m-d'); // Current date

    // Insert reservation record
    mysqli_query($mysqli, "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('" . $user['dni'] . "', '" . $fecha . "', '" . $id_horario . "')");
    
    echo "Reservation made for User: " . $user['name'] . ", DNI: " . $user['dni'] . "<br>";
}*/



/*
include("conex.php");
date_default_timezone_set('America/Argentina/Cordoba');
// Check for users with auto-reservation option and available credits
$eligibleUsersQuery = mysqli_query($mysqli, "SELECT * FROM registrados WHERE reserva_auto = 1 AND credito >= 1");
while ($user = mysqli_fetch_assoc($eligibleUsersQuery)) {
    // Check if the user has made reservations in the previous week
    $lastWeek = strtotime('-1 week');
    $reservationsLastWeek = mysqli_query($mysqli, "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha >= '" . date('Y-m-d', $lastWeek) . "'");
    if (mysqli_num_rows($reservationsLastWeek) > 0) {
        // User made reservations last week, automate reservations for the same time slots
        // Iterate through previous week's reservations
        while ($row = mysqli_fetch_assoc($reservationsLastWeek)) {
            $id_horario = $row['actividad_horarios_id_horario'];
            $fecha = $row['fecha'];
            // Check if the reservation already exists for the current week
            $existingReservation = mysqli_query($mysqli, "SELECT * FROM actividad_reservas WHERE registrados_dni='" . $user['dni'] . "' AND fecha='" . $fecha . "' AND actividad_horarios_id_horario='" . $id_horario . "'");
            if (mysqli_num_rows($existingReservation) == 0) {
                // Check if the id_horario is listed in actividad_horarios_susp (holiday or suspended activity)
                $isSuspended = mysqli_query($mysqli, "SELECT * FROM actividad_horarios_susp WHERE id_horario='" . $id_horario . "' AND fecha='" . $fecha . "'");

                if (mysqli_num_rows($isSuspended) == 0) {
                    // Check if the user has enough credits for the reservation
                    $cost = getReservationCost($mysqli, $id_horario); // Assuming there is a function to retrieve the cost

                    if ($user['credito'] >= $cost) {
                        // Reserve the same time slot for the current week
                        mysqli_query($mysqli, "INSERT INTO actividad_reservas (registrados_dni, fecha, actividad_horarios_id_horario) VALUES ('" . $user['dni'] . "', '" . $fecha . "', '" . $id_horario . "')");

                        // Deduct the cost from the user's credits
                        mysqli_query($mysqli, "UPDATE registrados SET credito = credito - " . $cost . " WHERE dni = '" . $user['dni'] . "'");
                    }
                }
            }
        }
    }
}
function getReservationCost($mysqli, $id_horario) {
    // Implement the logic to retrieve the reservation cost based on $id_horario
    // For example, you might have a table with activity costs, and you can fetch the cost using a query
    $costQuery = mysqli_query($mysqli, "SELECT cost FROM activity_costs WHERE id_horario = '" . $id_horario . "'");
    $costRow = mysqli_fetch_assoc($costQuery);
    return $costRow['cost'];
}
*/
?>