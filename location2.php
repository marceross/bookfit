<html>
<body>  

<form method="post" action="location2_update.php">
 dni:<input type="text" name="dni" id="dni" value="29583715"><br>

 <p><button onclick="geoFindMe()">Show my location</button></p>
 <div id="out"></div>
 <script>
  function geoFindMe() {
   var output = document.getElementById("out");

   if (!navigator.geolocation){
    output.innerHTML = "<p>Geolocation is not supported by your 
     browser</p>";
    return;
  }

   function success(position) {
    var latitude  = position.coords.latitude;
    var longitude = position.coords.longitude;
    document.getElementById("lat").value = latitude;
    document.getElementById("long").value = longitude;

    output.innerHTML = '<p>Latitude is ' + latitude + '° <br>Longitude is ' + longitude + '°</p>';

    var img = new Image();
    img.src = "https://maps.googleapis.com/maps/api/staticmap?center=" + 
latitude + "," + longitude + "&zoom=13&size=300x300&sensor=false";

   output.appendChild(img);
 }

   function error() {
      output.innerHTML = "Unable to retrieve your location";
  }

   output.innerHTML = "<p>Locating…</p>";

   navigator.geolocation.getCurrentPosition(success, error);
  }
 </script>
   <input id="lat" name="latitude" type="hidden" value=" ">
   <input id="long" name="longitude" type="hidden" value=" ">
   <input type="submit" value="submit" required>
  </form>
  
</body>
</html>