<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Map Geofence</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: 500px; width: 100%; }
        #accuracy { margin: 10px 0; color: #2563eb; }
        #coords { margin: 10px 0; color: #333; }
        #status { margin: 10px 0; font-weight: bold; }
        #radius-control { margin: 10px 0; }
    </style>
</head>
<body>
    <button onclick="locate()">Retry Location</button>
    <div id="radius-control">
        <label for="radius">Geofence Radius (meters): </label>
        <input type="number" id="radius" value="500" min="50" max="5000" step="50" style="width:100px;">
        <button onclick="updateRadius()">Update Radius</button>
    </div>
    <div id="accuracy"></div>
    <div id="coords"></div>
    <div id="status"></div>
    <div id="map"></div>
    <script>
        let map, marker, circle, geofenceCircle;
        let geofenceCenter = [7.4474, 125.8025]; // Default center
        let geofenceRadius = 500; 

        function assignGeofenceArea(center, radius) {
            if (geofenceCircle) map.removeLayer(geofenceCircle);
            geofenceCircle = L.circle(center, {
                radius: radius,
                color: 'green',
                fillColor: '#a7f3d0',
                fillOpacity: 0.2
            }).addTo(map);
            geofenceCenter = center; 
            geofenceRadius = radius; 
        }

        function isInGeofence(userLatLng, center, radius) {
            const userPoint = L.latLng(userLatLng);
            const centerPoint = L.latLng(center);
            return userPoint.distanceTo(centerPoint) <= radius;
        }

        function showStatus(inCoverage) {
            const statusDiv = document.getElementById('status');
            if (inCoverage) {
                statusDiv.textContent = "✅ You are INSIDE the assigned area.";
                statusDiv.style.color = "green";
            } else {
                statusDiv.textContent = "❌ You are OUTSIDE the assigned area.";
                statusDiv.style.color = "red";
            }
        }

        function locate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(initMap, function(error) {
                    console.error("Error getting location: ", error);
                    alert("Unable to retrieve your location. Please allow location access.");
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }

        function initMap(position) {
            const center = [position.coords.latitude, position.coords.longitude];
            const accuracy = position.coords.accuracy;

            document.getElementById('accuracy').innerText =
                `Reported accuracy: ${accuracy} meters`;
            document.getElementById('coords').innerText =
                `Latitude: ${center[0]}, Longitude: ${center[1]}`;

            if (!map) {
                map = L.map('map').setView(center, 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                assignGeofenceArea(geofenceCenter, geofenceRadius); // Draw geofence on first load

                // Add click event to assign new geofence area
                map.on('click', function(e) {
                    assignGeofenceArea([e.latlng.lat, e.latlng.lng], geofenceRadius);
                    // After assigning, check if user is in new geofence
                    const inCoverage = isInGeofence(center, [e.latlng.lat, e.latlng.lng], geofenceRadius);
                    showStatus(inCoverage);
                });
            } else {
                map.setView(center, 15);
                if (marker) map.removeLayer(marker);
                if (circle) map.removeLayer(circle);
            }

            marker = L.marker(center).addTo(map).bindPopup("You are here!").openPopup();

    
            circle = L.circle(center, {
                radius: accuracy,
                color: '#2563eb',
                fillColor: '#60a5fa',
                fillOpacity: 0.2
            }).addTo(map);

           
            const inCoverage = isInGeofence(center, geofenceCenter, geofenceRadius);
            showStatus(inCoverage);
        }

        function updateRadius() {
            const newRadius = parseInt(document.getElementById('radius').value, 10);
            if (isNaN(newRadius) || newRadius < 50) {
                alert("Please enter a valid radius (minimum 50 meters).");
                return;
            }
            geofenceRadius = newRadius;
            assignGeofenceArea(geofenceCenter, geofenceRadius);

            // If we have the user's marker, check if they're in the new geofence
            if (marker) {
                const userLatLng = marker.getLatLng();
                const inCoverage = isInGeofence([userLatLng.lat, userLatLng.lng], geofenceCenter, geofenceRadius);
                showStatus(inCoverage);
            }
        }

        locate();
    </script>
</body>
</html>
