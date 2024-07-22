<!DOCTYPE html>
<html>
<body>

<p>Click the button to get your coordinates.</p>

<button onclick="getLocation()">Update location</button>

<p id="demo"></p>

<script>
var x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}
    
function showPosition(position) {
    x.innerHTML="Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude;

    //agregado
    var latitude  = position.coords.latitude;
    var longitude = position.coords.longitude;
    document.getElementById("lat").value = latitude;
    document.getElementById("long").value = longitude;
    //hasta aca
}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      x.innerHTML = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.innerHTML = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML = "An unknown error occurred."
      break;
  }
}
</script>


<!-- parte nueva-->
<form method="post" action="location2_update.php">

 <input type="text" name="dni" id="dni" value="29583715"><br>

<input id="lat" name="latitude" type="text" value=" ">
<input id="long" name="longitude" type="text" value=" ">
<input type="submit" value="submit" required>
</form>
<!-- -->

</body>
</html>