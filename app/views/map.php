<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geofence Map Selector</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-image:
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .hover-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15);
        }
        #map { 
            height: 500px; 
            width: 100%; 
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .info-label { 
            font-weight: 600; 
            color: #374151; 
            font-size: 0.875rem;
        }
        .coordinate { 
            font-family: 'Courier New', monospace; 
            color: #059669; 
            font-weight: 500;
        }
        .geofence-center-marker {
            background: transparent !important;
        }
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-6xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-map-marker-alt text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Geofence Map Selector</h1>
    </div>
    <p class="text-gray-600 mt-2">Click on the map to set the geofence center, then adjust the radius as needed.</p>
</header>

<div class="max-w-6xl mx-auto">
    <!-- Controls Section -->
    <div class="glass-card rounded-2xl p-6 mb-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="radius" class="text-sm font-medium text-gray-700">Radius (meters):</label>
                    <input type="number" id="radius" value="500" min="50" max="5000" step="50" 
                           class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                </div>
                <button onclick="updateRadius()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Update
                </button>
            </div>
            <button onclick="sendGeofenceToParent()" 
                    class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-check"></i> Use This Location
            </button>
        </div>
    </div>

    <!-- Map Section -->
    <div class="glass-card rounded-2xl p-6 mb-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
    <div id="map"></div>
    </div>

    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Geofence Info Card -->
        <div class="glass-card rounded-2xl p-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover-card">
            <div class="flex items-center space-x-2 mb-4">
                <i class="fas fa-circle text-blue-500 text-xl"></i>
                <h3 class="text-lg font-semibold text-gray-800">Geofence Details</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <div class="info-label">Center Coordinates:</div>
                    <div id="geofence-center" class="coordinate">Latitude: 7.4474, Longitude: 125.8025</div>
                </div>
                <div>
                    <div class="info-label">Radius:</div>
                    <div id="geofence-radius" class="coordinate">500 meters</div>
                </div>
            </div>
        </div>

        <!-- Distance Info Card -->
        <div class="glass-card rounded-2xl p-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover-card">
            <div class="flex items-center space-x-2 mb-4">
                <i class="fas fa-ruler text-green-500 text-xl"></i>
                <h3 class="text-lg font-semibold text-gray-800">Distance Information</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <div class="info-label">To Center:</div>
                    <div id="distance-to-center" class="coordinate">Click on map to calculate</div>
                </div>
                <div>
                    <div class="info-label">To Edge:</div>
                    <div id="distance-to-edge" class="coordinate">Click on map to calculate</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Section -->
    <div class="glass-card rounded-2xl p-6 mt-6 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <div id="status" class="text-center text-lg font-semibold text-gray-700">
            Click anywhere on the map to set the geofence center
        </div>
    </div>
</div>
    <script>
        let map, marker, circle, geofenceCircle, centerMarker;
        let geofenceCenter = [7.4474, 125.8025]; // Default center
        let geofenceRadius = 500; 
        let userPosition = null;

        function assignGeofenceArea(center, radius) {
            if (geofenceCircle) map.removeLayer(geofenceCircle);
            if (centerMarker) map.removeLayer(centerMarker);
            
            // Add center marker
            centerMarker = L.marker(center, {
                icon: L.divIcon({
                    className: 'geofence-center-marker',
                    html: '<div style="background-color: red; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                })
            }).addTo(map).bindPopup("Geofence Center<br>Click to set new center").openPopup();
            
            // Add geofence circle
            geofenceCircle = L.circle(center, {
                radius: radius,
                color: 'green',
                fillColor: '#a7f3d0',
                fillOpacity: 0.2,
                weight: 2
            }).addTo(map);
            
            geofenceCenter = center; 
            geofenceRadius = radius; 
            
            // Update geofence info display
            updateGeofenceInfo();
            
            // Update distance calculations if user position is available
            if (userPosition) {
                updateDistanceInfo();
            }
        }

        function updateGeofenceInfo() {
            document.getElementById('geofence-center').innerHTML = 
                `<span class="coordinate">Latitude: ${geofenceCenter[0].toFixed(6)}, Longitude: ${geofenceCenter[1].toFixed(6)}</span>`;
            document.getElementById('geofence-radius').innerHTML = 
                `<span class="coordinate">${geofenceRadius} meters</span>`;
        }

        function updateDistanceInfo() {
            if (!userPosition) {
                document.getElementById('distance-to-center').innerHTML = 
                    '<span class="coordinate text-gray-500">Click on map to calculate</span>';
                document.getElementById('distance-to-edge').innerHTML = 
                    '<span class="coordinate text-gray-500">Click on map to calculate</span>';
                return;
            }
            
            const userPoint = L.latLng(userPosition);
            const centerPoint = L.latLng(geofenceCenter);
            const distanceToCenter = userPoint.distanceTo(centerPoint);
            const distanceToEdge = Math.abs(distanceToCenter - geofenceRadius);
            
            document.getElementById('distance-to-center').innerHTML = 
                `<span class="coordinate">${distanceToCenter.toFixed(2)} meters</span>`;
            document.getElementById('distance-to-edge').innerHTML = 
                `<span class="coordinate">${distanceToEdge.toFixed(2)} meters</span>`;
        }

        function isInGeofence(userLatLng, center, radius) {
            const userPoint = L.latLng(userLatLng);
            const centerPoint = L.latLng(center);
            return userPoint.distanceTo(centerPoint) <= radius;
        }

        function showStatus(inCoverage) {
            const statusDiv = document.getElementById('status');
            if (inCoverage) {
                statusDiv.innerHTML = '<i class="fas fa-check-circle text-green-500 mr-2"></i>You are INSIDE the assigned area.';
                statusDiv.className = 'text-center text-lg font-semibold text-green-600';
            } else {
                statusDiv.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-2"></i>You are OUTSIDE the assigned area.';
                statusDiv.className = 'text-center text-lg font-semibold text-red-600';
            }
        }

        // function locate() {
        //     if (navigator.geolocation) {
        //         navigator.geolocation.getCurrentPosition(initMap, function(error) {
        //             console.error("Error getting location: ", error);
        //             alert("Unable to retrieve your location. Please allow location access.");
        //     }, {
        //             enableHighAccuracy: true,
        //             timeout: 10000,
        //             maximumAge: 0
        //         });
        //     } else {
        //         alert("Geolocation is not supported by your browser.");
        //     }
        // }

        function initMap(position) {
            const center = [position.coords.latitude, position.coords.longitude];
            const accuracy = position.coords.accuracy;
            userPosition = center;

            document.getElementById('accuracy').innerText =
                `Reported accuracy: ${accuracy} meters`;
            document.getElementById('coords').innerText =
                `Your Location: Latitude: ${center[0].toFixed(6)}, Longitude: ${center[1].toFixed(6)}`;

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

            // Update distance information
            updateDistanceInfo();
           
            // Check if user is in geofence
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

        // Function to get current geofence data (for saving/exporting)
        function getGeofenceData() {
            return {
                center: {
                    latitude: geofenceCenter[0],
                    longitude: geofenceCenter[1]
                },
                radius: geofenceRadius,
                timestamp: new Date().toISOString()
            };
        }
        
        // Function to send geofence data to parent window
        function sendGeofenceToParent() {
            const geofenceData = getGeofenceData();
            if (window.opener) {
                window.opener.postMessage({
                    type: 'geofenceData',
                    geofence: geofenceData
                }, window.location.origin);
            }
        }

        // Function to set geofence from data
        function setGeofenceData(data) {
            if (data.center && data.radius) {
                geofenceCenter = [data.center.latitude, data.center.longitude];
                geofenceRadius = data.radius;
                document.getElementById('radius').value = data.radius;
                if (map) {
                    assignGeofenceArea(geofenceCenter, geofenceRadius);
                }
            }
        }

        // Initialize map with default center
        if (!map) {
            const defaultCenter = [7.4474, 125.8025]; // Default center
            map = L.map('map').setView(defaultCenter, 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            assignGeofenceArea(geofenceCenter, geofenceRadius); // Draw geofence on first load

            // Add click event to assign new geofence area
            map.on('click', function(e) {
                assignGeofenceArea([e.latlng.lat, e.latlng.lng], geofenceRadius);
            });
        }
    </script>
</body>
</html>
