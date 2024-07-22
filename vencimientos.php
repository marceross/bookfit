<?php
/*include("conex.php");
include("local_controla.php");

// Assuming $mysqli is your database connection

// Get today's date
$today = date("Y-m-d");

// Get tomorrow's date
$tomorrow = date("Y-m-d", strtotime("+1 day"));

// Get the date after tomorrow
$date_after_tomorrow = date("Y-m-d", strtotime("+2 days"));

// Get the date two days after tomorrow
$date_two_days_after_tomorrow = date("Y-m-d", strtotime("+3 days"));

// Select records where 'vencimiento' will happen today, tomorrow, or the day after tomorrow, ordered by 'vencimiento'
$result = mysqli_query($mysqli, "SELECT nombre, apellido, vencimiento, credito, celular FROM registrados WHERE vencimiento = '$today' OR vencimiento = '$tomorrow' OR vencimiento = '$date_after_tomorrow' OR vencimiento = '$date_two_days_after_tomorrow' ORDER BY vencimiento");

// Check if the SELECT query was successful
if ($result) {
    // Start HTML table
    echo '<table border="1">';
    echo '<tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Vencimiento</th>
            <th>Credito</th>
            <th>Celular</th>
          </tr>';

    // Loop through the selected records
    while ($registrado = mysqli_fetch_assoc($result)) {
        // Retrieve values
        $nombre = $registrado['nombre'];
        $apellido = $registrado['apellido'];
        $vencimiento = $registrado['vencimiento'];
        $credito = $registrado['credito'];
        $celular = $registrado['celular'];

        // Check if the vencimiento is today
        $is_today = ($vencimiento == $today) ? ' style="color: red;"' : '';

        // Display the information in HTML, with red color if it's today
        echo '<tr' . $is_today . '>
                <td>' . $nombre . '</td>
                <td>' . $apellido . '</td>
                <td>' . $vencimiento . '</td>
                <td>' . $credito . '</td>
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
?>



<?php/*
include("conex.php");

// Assuming $mysqli is your database connection

// Get today's date
$today = date("Y-m-d");

// Get the date after tomorrow
$date_after_tomorrow = date("Y-m-d", strtotime("+2 days"));

// Get the date two days after tomorrow
$date_two_days_after_tomorrow = date("Y-m-d", strtotime("+3 days"));

// Select records where 'vencimiento' will happen today, the day after tomorrow or two more days after, ordered by 'vencimiento'
$result = mysqli_query($mysqli, "SELECT nombre, apellido, vencimiento, credito, celular FROM registrados WHERE vencimiento = '$today' OR vencimiento = '$date_after_tomorrow' OR vencimiento = '$date_two_days_after_tomorrow' ORDER BY vencimiento");

// Check if the SELECT query was successful
if ($result) {
    // Start HTML table
    echo '<table border="1">';
    echo '<tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Vencimiento</th>
            <th>Credito</th>
            <th>Celular</th>
          </tr>';

    // Loop through the selected records
    while ($registrado = mysqli_fetch_assoc($result)) {
        // Retrieve values
        $nombre = $registrado['nombre'];
        $apellido = $registrado['apellido'];
        $vencimiento = $registrado['vencimiento'];
        $credito = $registrado['credito'];
        $celular = $registrado['celular'];

        // Check if the vencimiento is today
        $is_today = ($vencimiento == $today) ? ' style="color: red;"' : '';

        // Display the information in HTML, with red color if it's today
        echo '<tr' . $is_today . '>
                <td>' . $nombre . '</td>
                <td>' . $apellido . '</td>
                <td>' . $vencimiento . '</td>
                <td>' . $credito . '</td>
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
mysqli_close($mysqli);*/
?>
