<?php
/*
include("conex.php");
include("local_controla.php");

// Assuming $mysqli is your database connection

// Get today's date
$today = date("m-d");

// Select records with birth dates matching today's date
$result = mysqli_query($mysqli, "SELECT nombre, apellido, nacimiento, vencimiento, fecha, celular FROM registrados WHERE DATE_FORMAT(nacimiento, '%m-%d') = '$today'");

// Check if the SELECT query was successful
if ($result) {
    // Start HTML table
    echo '<table border="1">';
    echo '<tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Fecha de Nacimiento</th>
            <th>Vencimiento</th>
            <th>Fecha inscripcion</th>
            <th>Celular</th>
          </tr>';

    // Loop through the selected records
    while ($registrado = mysqli_fetch_assoc($result)) {
        // Retrieve values
        $nombre = $registrado['nombre'];
        $apellido = $registrado['apellido'];
        $fecha_nacimiento = $registrado['nacimiento'];
        $vencimiento = $registrado['vencimiento'];
        $fecha = $registrado['fecha'];
        $celular = $registrado['celular'];

        // Display the information in HTML
        echo '<tr>
                <td>' . $nombre . '</td>
                <td>' . $apellido . '</td>
                <td>' . $fecha_nacimiento . '</td>
                <td>' . $vencimiento . '</td>
                <td>' . $fecha . '</td>
                <td>' . $celular . '</td>
              </tr>';
    }

    // End HTML table
    echo '</table>';

    // Free the result set
    mysqli_free_result($result);
} else {
    // Display an error message if the SELECT query fails
    echo "Error selecting records - " . mysqli_error($mysqli);
}

// Close the database connection
mysqli_close($mysqli);




/*
// Assuming $mysqli is your database connection

// Select records for birthdays
$today = date("m-d");
$birthday_result = mysqli_query($mysqli, "SELECT nombre, apellido, dni FROM registrados WHERE DATE_FORMAT(nacimiento, '%m-%d') = '$today'");

// Select records for upcoming registration expirations (vencimiento)
$warningDays = 7;
$expiryDate = date("Y-m-d", strtotime("+$warningDays days"));
$expiry_result = mysqli_query($mysqli, "SELECT nombre, apellido, dni, vencimiento FROM registrados WHERE vencimiento <= '$expiryDate'");

// Select records for new members
$new_members_result = mysqli_query($mysqli, "SELECT nombre, apellido, dni FROM registrados WHERE fecha = CURDATE()");

// Check if the SELECT queries were successful
if ($birthday_result && $expiry_result && $new_members_result) {
    // Start HTML table
    $output = '<table border="1">';
    $output .= '<tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Fecha de Nacimiento</th>
            <th>Vencimiento</th>
            <th>Fecha de Registro</th>
          </tr>';

    // Fetch and append records for birthdays
    while ($row = mysqli_fetch_assoc($birthday_result)) {
        $output .= '<tr>
                    <td>' . $row['nombre'] . '</td>
                    <td>' . $row['apellido'] . '</td>
                    <td>' . $row['dni'] . '</td>
                    <td>Birthday</td>
                    <td></td>
                    <td></td>
                  </tr>';
    }

    // Fetch and append records for upcoming registration expirations
    while ($row = mysqli_fetch_assoc($expiry_result)) {
        $output .= '<tr>
                    <td>' . $row['nombre'] . '</td>
                    <td>' . $row['apellido'] . '</td>
                    <td>' . $row['dni'] . '</td>
                    <td></td>
                    <td>' . $row['vencimiento'] . '</td>
                    <td></td>
                  </tr>';
    }

    // Fetch and append records for new members
    while ($row = mysqli_fetch_assoc($new_members_result)) {
        $output .= '<tr>
                    <td>' . $row['nombre'] . '</td>
                    <td>' . $row['apellido'] . '</td>
                    <td>' . $row['dni'] . '</td>
                    <td></td>
                    <td></td>
                    <td>Today</td>
                  </tr>';
    }

    // End HTML table
    $output .= '</table>';

    // Free the result sets
    mysqli_free_result($birthday_result);
    mysqli_free_result($expiry_result);
    mysqli_free_result($new_members_result);
    
    



    // Send email
    if (!empty($output)) {
        $to = "marceross@gmail.com";
        $subject = "Daily Report";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: hola@lokales.com.ar" . "\r\n";

        mail($to, $subject, $output, $headers);
    }
} else {
    // Display an error message if any SELECT query fails
    echo "Error fetching records - " . mysqli_error($mysqli);
}

// Close the database connection
mysqli_close($mysqli);
*/
?>