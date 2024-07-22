<?
/*


include("conex.php");


// Assuming $mysqli is your database connection

// Select records
$result = mysqli_query($mysqli, "SELECT * FROM registrados WHERE vencimiento>='2024-01-01' AND credito>'0'");

// Check if the SELECT query was successful
if ($result) {
    // Start HTML table
    echo '<table border="1">';
    echo '<tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI Registrado</th>
            <th>Previous Vencimiento</th>
            <th>Previous Credito</th>
            <th>New Vencimiento</th>
            <th>New Credito</th>
          </tr>';

    // Loop through the selected records
    while ($registrado = mysqli_fetch_assoc($result)) {
        // Retrieve current values
        $nombre = $registrado['nombre'];
        $apellido =$registrado['apellido'];
        $dni_registrado = $registrado['dni'];
        $vencimiento = $registrado['vencimiento'];
        $credito = $registrado['credito'];

        // Calculate new values
        $new_vencimiento = date("Y-m-d", strtotime($vencimiento . "+1 month"));
        $new_credito = $credito * 2;

        // Update records
        $update_query = "UPDATE registrados SET vencimiento='$new_vencimiento', credito='$new_credito' WHERE dni='$dni_registrado'";
        
        // Check if the UPDATE query was successful
        if (mysqli_query($mysqli, $update_query)) {
            // Display the information in HTML
            echo '<tr>
            
                    <td>' . $nombre . '</td>
                    <td>' . $apellido .'</td>
                    <td>' . $dni_registrado . '</td>
                    <td>' . $vencimiento . '</td>
                    <td>' . $credito . '</td>
                    <td>' . $new_vencimiento . '</td>
                    <td>' . $new_credito . '</td>
                  </tr>';
        } else {
            echo "Error updating record with dni: $dni_registrado - " . mysqli_error($mysqli);
        }
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

*/
?>