function toggleReservaAuto() {
    // Get the current value of reserva_auto (you might need to fetch this from your backend)
    let currentValue = 1; // Example value, replace it with your actual value or fetch it dynamically
    
    // Toggle the value (change 1 to 0 and vice versa)
    let newValue = currentValue === 1 ? 0 : 1;

    // Make AJAX request to update the database
    updateDatabase(newValue);
}

function updateDatabase(value) {
    // Use XMLHttpRequest or fetch API to make an AJAX request to update the database
    // Replace the URL and other parameters with your actual backend endpoint
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'your_backend_endpoint_here', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Example data to be sent to the backend (adjust as per your backend requirements)
    let data = {
        fieldToUpdate: 'reserva_auto',
        newValue: value
    };

    // Convert the data to JSON before sending
    let jsonData = JSON.stringify(data);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response from the server (if needed)
            console.log('Database updated successfully!');
        }
    };

    // Send the request with the JSON data
    xhr.send(jsonData);
}
