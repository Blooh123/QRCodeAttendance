<?php
global $AttendanceID, $EventName, $EventDate, $EventTime, $isOngoing, $latitude, $longitude, $radius;
require_once '../app/core/config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
    <title>QR Code Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!--    <script src="../node_modules/html5-qrcode/html5-qrcode.min.js"></script>-->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
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
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
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
        
        /* Main container */
        .main-container {
            animation: slideInUp 0.8s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Scanner container */
        .scanner-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 2px solid #a31d1d;
            position: relative;
        }
        
        .scanner-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #a31d1d, #ff6b6b, #a31d1d);
            background-size: 400% 400%;
            border-radius: 22px;
            z-index: -1;
            animation: borderGlow 2s ease-in-out infinite;
        }
        
        @keyframes borderGlow {
            0%, 100% { 
                background-position: 0% 50%;
                box-shadow: 0 0 20px rgba(163, 29, 29, 0.3);
            }
            50% { 
                background-position: 100% 50%;
                box-shadow: 0 0 30px rgba(163, 29, 29, 0.6);
            }
        }
        
        #reader {
            width: 100%;
            max-width: 500px;
            height: auto;
            margin: 0 auto;
            position: relative;
            border-radius: 18px;
            overflow: hidden;
        }
        
        #result, #student-info {
            margin-top: 20px;
            font-size: 16px;
            font-weight: 600;
            padding: 15px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        
        #student-info {
            color: #4CAF50;
            border-left: 4px solid #4CAF50;
        }
        
        .btn-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        
        button, a {
            padding: 12px 24px;
            border: none;
            background-color: #a31d1d;
            color: white;
            cursor: pointer;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(163, 29, 29, 0.3);
        }
        
        button:hover, a:hover {
            background-color: #8a1818;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(163, 29, 29, 0.4);
        }
        
        .secondary-btn {
            background-color: #6c757d;
        }
        
        .secondary-btn:hover {
            background-color: #5a6268;
        }
        
        .success-btn {
            background-color: #28a745;
        }
        
        .success-btn:hover {
            background-color: #218838;
        }
        
        @media (max-width: 600px) {
            #reader {
                width: 100%;
            }
            button, a {
                width: 100%;
                padding: 15px;
            }
        }

        /* Location permission modal styles */
        #location-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }
        #location-modal > div {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 90%;
            width: 450px;
            color: black;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 2px solid #a31d1d;
        }
        #location-modal h3 {
            color: #a31d1d;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 24px;
        }
        #location-modal p {
            margin-bottom: 25px;
            line-height: 1.6;
            color: #666;
            font-size: 16px;
        }
        #location-modal .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        #location-modal button {
            margin: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        #location-modal button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Geofence error modal styles */
        #geofence-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }
        #geofence-modal > div {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            max-width: 95%;
            width: 700px;
            max-height: 90vh;
            color: black;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            overflow-y: auto;
            border: 2px solid #a31d1d;
        }
        #geofence-modal h3 {
            color: #a31d1d;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 24px;
        }
        #geofence-modal p {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #666;
            font-size: 16px;
        }
        #geofence-modal .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        #geofence-modal button {
            margin: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        #geofence-modal button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        #geofence-map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            margin: 15px 0;
            border: 2px solid #ddd;
        }
        .location-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
            text-align: left;
        }
        .location-info strong {
            color: #333;
        }
        .distance-info {
            background: #e8f5e8;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #4CAF50;
        }

        /* Responsive styles for the confirmation modal */
        #confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }
        #confirmation-modal > div {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 90%;
            width: 450px;
            color: black;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 2px solid #a31d1d;
        }
        #confirmation-modal h3 {
            color: #a31d1d;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 24px;
        }
        #student-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 15px;
            margin: 20px auto;
            display: block;
            border: 3px solid #a31d1d;
            box-shadow: 0 8px 25px rgba(163, 29, 29, 0.3);
        }

        #confirmation-modal button {
            margin: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        #confirmation-modal button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .loader {
            border: 8px solid #f3f3f3; /* Light gray */
            border-top: 8px solid #3ddf20; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Scanner content that will be hidden initially */
        #scanner-content {
            display: none;
        }
    </style>
</head>
<body class="p-4 md:p-6">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-qrcode text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">QR Code Scanner</h1>
    </div>
</header>

<!-- Location Permission Modal -->
<div id="location-modal">
    <div>
        <div class="icon">📍</div>
        <h3>Location Permission Required</h3>
        <p>To use the QR Code Scanner, you need to enable location services. This helps ensure accurate attendance tracking within the designated area.</p>
        <button id="enable-location-btn" class="success-btn">Enable Location</button>
        <button id="cancel-location-btn" class="secondary-btn">Cancel</button>
    </div>
</div>

<!-- Geofence Error Modal -->
<div id="geofence-modal">
    <div>
        <div class="icon">🚫</div>
        <h3>Location Outside Assigned Area</h3>
        <p>You are currently outside the designated attendance area. Please move to the correct location to continue scanning.</p>
        
        <div id="geofence-map"></div>
        
        <div class="location-info">
            <strong>Your Current Location:</strong><br>
            <span id="user-location">Loading...</span>
        </div>
        
        <div class="location-info">
            <strong>Assigned Area:</strong><br>
            <span id="assigned-area">Loading...</span>
        </div>
        
        <div class="distance-info">
            <strong>Distance to Area:</strong><br>
            <span id="distance-info">Calculating...</span>
        </div>
        
        <button id="retry-location-btn" class="success-btn">Check Location Again</button>
        <button id="close-geofence-btn" class="secondary-btn">Close</button>
    </div>
</div>

<?php if ($isOngoing): ?>
    <div id="scanner-content" class="max-w-2xl mx-auto main-container">
        <!-- Event Info Card -->
        <div class="glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                    <div class="text-blue-600 text-2xl mb-2">📅</div>
                    <div class="text-blue-700 font-semibold">Event</div>
                    <div class="text-blue-600 text-sm"><?= $EventName; ?></div>
                </div>
                <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                    <div class="text-green-600 text-2xl mb-2">📆</div>
                    <div class="text-green-700 font-semibold">Date</div>
                    <div class="text-green-600 text-sm"><?= $EventDate; ?></div>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                    <div class="text-purple-600 text-2xl mb-2">⏰</div>
                    <div class="text-purple-700 font-semibold">Time</div>
                    <div class="text-purple-600 text-sm"><?= $EventTime; ?></div>
                </div>
            </div>
        </div>

        <!-- Scanner Container -->
        <div class="scanner-container p-6">
            <div id="reader"></div>
            <div id="result"></div>
            <div id="student-info"></div>
        </div>

        <!-- Confirmation Modal -->
        <div id="confirmation-modal">
            <div>
                <h3>Confirm Attendance</h3>
                <img id="student-image" src="" alt="Student Profile">
                <p id="student-name" class="text-lg font-semibold text-gray-800 mb-2"></p>
                <p id="student-program" class="text-gray-600 mb-4"></p>
                <div class="flex justify-center space-x-4">
                    <button id="confirm-btn" class="success-btn">Confirm</button>
                    <button id="cancel-btn" class="secondary-btn">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6">
            <button id="restart-btn" class="success-btn w-full mb-4" style="display: none;">Scan Again</button>
            <div class="btn-container">
                <button id="flip-camera-btn" class="secondary-btn">
                    <i class="fas fa-sync-alt mr-2"></i>Flip Camera
                </button>
                <a id="back-btn" href="<?php echo ROOT ?>facilitator" class="secondary-btn">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <script>
        let html5QrCode = new Html5Qrcode("reader");
        let currentFacingMode = { facingMode: "environment" }; // Default camera mode
        let locationPermissionGranted = false;
        let geofenceMap = null;
        let userMarker = null;
        let geofenceCircle = null;
        let userLocation = null;
        
        // Geofence data from PHP
        const assignedLatitude = <?= $latitude ?: 'null' ?>;
        const assignedLongitude = <?= $longitude ?: 'null' ?>;
        const assignedRadius = <?= $radius ?: 'null' ?>;

        // Debug geofence data
        console.log('Geofence Data:', {
            latitude: assignedLatitude,
            longitude: assignedLongitude,
            radius: assignedRadius
        });

        // Validate coordinates
        function validateCoordinates(lat, lng) {
            const isValidLat = lat !== null && lat !== undefined && !isNaN(lat) && lat >= -90 && lat <= 90;
            const isValidLng = lng !== null && lng !== undefined && !isNaN(lng) && lng >= -180 && lng <= 180;
            
            console.log('Coordinate validation:', {
                lat: lat,
                lng: lng,
                isValidLat: isValidLat,
                isValidLng: isValidLng
            });
            
            return isValidLat && isValidLng;
        }

        // Check if geofence data is valid - handle coordinate swapping
        let correctedLatitude = assignedLatitude;
        let correctedLongitude = assignedLongitude;
        
        // If coordinates appear to be swapped (latitude > 90 or longitude < 90), swap them
        if (assignedLatitude > 90 || assignedLongitude < 90) {
            console.log('Detected coordinate swapping, correcting...');
            correctedLatitude = assignedLongitude;
            correctedLongitude = assignedLatitude;
            console.log('Corrected coordinates:', {
                original: { lat: assignedLatitude, lng: assignedLongitude },
                corrected: { lat: correctedLatitude, lng: correctedLongitude }
            });
        }
        
        const isGeofenceValid = validateCoordinates(correctedLatitude, correctedLongitude) && 
                               assignedRadius !== null && 
                               assignedRadius !== undefined && 
                               !isNaN(assignedRadius) && 
                               assignedRadius > 0;

        console.log('Geofence validation result:', isGeofenceValid);

        // Check location permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkLocationPermission();
        });

        function checkLocationPermission() {
            if (!navigator.geolocation) {
                showLocationError("Geolocation is not supported by this browser.");
                return;
            }

            navigator.permissions.query({ name: 'geolocation' }).then(function(result) {
                if (result.state === 'granted') {
                    locationPermissionGranted = true;
                    checkGeofenceAndShowScanner();
                } else if (result.state === 'denied') {
                    showLocationModal();
                } else {
                    // Permission is prompt, try to get location
                    requestLocationPermission();
                }
            }).catch(function(error) {
                // Fallback for browsers that don't support permissions API
                requestLocationPermission();
            });
        }

        function requestLocationPermission() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    locationPermissionGranted = true;
                    checkGeofenceAndShowScanner();
                },
                function(error) {
                    showLocationModal();
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                }
            );
        }

        function checkGeofenceAndShowScanner() {
            // Debug: Log the geofence check
            console.log('Checking geofence:', {
                hasLatitude: !!assignedLatitude,
                hasLongitude: !!assignedLongitude,
                hasRadius: !!assignedRadius,
                latitude: assignedLatitude,
                longitude: assignedLongitude,
                radius: assignedRadius,
                isGeofenceValid: isGeofenceValid
            });

            if (isGeofenceValid) {
                // Check if user is within geofence
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        
                        const distance = calculateDistance(
                            userLocation.lat, userLocation.lng,
                            correctedLatitude, correctedLongitude
                        );
                        
                        console.log('Location check:', {
                            userLocation: userLocation,
                            assignedCenter: [correctedLatitude, correctedLongitude],
                            distance: distance,
                            radius: assignedRadius,
                            isInside: distance <= assignedRadius
                        });
                        
                        if (distance <= assignedRadius) {
                            // User is within geofence, show scanner
                            showScanner();
                        } else {
                            // User is outside geofence, show map
                            showGeofenceError(userLocation, distance);
                        }
                    },
                    function(error) {
                        console.error("Error getting location:", error);
                        showLocationError("Unable to get your current location.");
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                // Invalid geofence data - show error but allow scanning
                console.warn('Invalid geofence data:', {
                    latitude: assignedLatitude,
                    longitude: assignedLongitude,
                    radius: assignedRadius
                });
                showGeofenceDataError();
            }
        }

        function showGeofenceError(userLocation, distance) {
            // Update location info
            document.getElementById('user-location').innerHTML = 
                `Latitude: ${userLocation.lat.toFixed(6)}, Longitude: ${userLocation.lng.toFixed(6)}`;
            
            document.getElementById('assigned-area').innerHTML = 
                `Center: ${correctedLatitude.toFixed(6)}, ${correctedLongitude.toFixed(6)}<br>Radius: ${assignedRadius} meters`;
            
            document.getElementById('distance-info').innerHTML = 
                `${distance.toFixed(2)} meters from the center (${(distance - assignedRadius).toFixed(2)} meters outside the area)`;
            
            // Show modal
            document.getElementById('geofence-modal').style.display = 'flex';
            
            // Initialize map if not already done
            if (!geofenceMap) {
                console.log('Initializing geofence map with:', {
                    center: [correctedLatitude, correctedLongitude],
                    radius: assignedRadius,
                    userLocation: userLocation
                });
                initGeofenceMap();
            } else {
                console.log('Updating existing geofence map with user location:', userLocation);
                updateGeofenceMap(userLocation);
            }
        }

        function showGeofenceWarning() {
            // Show a warning that no geofence is set, but allow scanning
            const warningDiv = document.createElement('div');
            warningDiv.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: #ff9800;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 1000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                font-weight: bold;
            `;
            warningDiv.innerHTML = '⚠️ No geofence area set for this event. Location checking disabled.';
            document.body.appendChild(warningDiv);
            
            // Remove warning after 5 seconds
            setTimeout(() => {
                if (warningDiv.parentNode) {
                    warningDiv.parentNode.removeChild(warningDiv);
                }
            }, 5000);
            
            // Show scanner anyway
            showScanner();
        }

        function showGeofenceDataError() {
            // Show an error about invalid geofence data
            const errorDiv = document.createElement('div');
            errorDiv.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: #f44336;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 1000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                font-weight: bold;
                max-width: 400px;
                text-align: center;
            `;
            errorDiv.innerHTML = '⚠️ Invalid geofence data for this event. Location checking disabled.<br><small>Please contact the administrator to set proper coordinates.</small>';
            document.body.appendChild(errorDiv);
            
            // Remove error after 8 seconds
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.parentNode.removeChild(errorDiv);
                }
            }, 8000);
            
            // Show scanner anyway
            showScanner();
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Earth's radius in meters
            const φ1 = lat1 * Math.PI/180;
            const φ2 = lat2 * Math.PI/180;
            const Δφ = (lat2-lat1) * Math.PI/180;
            const Δλ = (lon2-lon1) * Math.PI/180;

            const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                    Math.cos(φ1) * Math.cos(φ2) *
                    Math.sin(Δλ/2) * Math.sin(Δλ/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

            return R * c; // Distance in meters
        }

        function initGeofenceMap() {
            console.log('Creating new map with center:', [correctedLatitude, correctedLongitude]);
            
            // Initialize the map
            geofenceMap = L.map('geofence-map').setView([correctedLatitude, correctedLongitude], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(geofenceMap);
            
            // Add geofence circle
            geofenceCircle = L.circle([correctedLatitude, correctedLongitude], {
                radius: assignedRadius,
                color: 'green',
                fillColor: '#4CAF50',
                fillOpacity: 0.2,
                weight: 2
            }).addTo(geofenceMap).bindPopup('Assigned Area<br>Radius: ' + assignedRadius + 'm');
            
            console.log('Added geofence circle at:', [correctedLatitude, correctedLongitude], 'with radius:', assignedRadius);
            
            // Add center marker
            L.marker([correctedLatitude, correctedLongitude], {
                icon: L.divIcon({
                    className: 'geofence-center-marker',
                    html: '<div style="background-color: green; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                })
            }).addTo(geofenceMap).bindPopup('Area Center');
            
            console.log('Added center marker at:', [correctedLatitude, correctedLongitude]);
        }

        function updateGeofenceMap(userLocation) {
            console.log('Updating map with user location:', userLocation);
            
            if (userMarker) {
                geofenceMap.removeLayer(userMarker);
                console.log('Removed existing user marker');
            }
            
            // Add user marker
            userMarker = L.marker([userLocation.lat, userLocation.lng], {
                icon: L.divIcon({
                    className: 'user-marker',
                    html: '<div style="background-color: red; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.5);"></div>',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                })
            }).addTo(geofenceMap).bindPopup('Your Location');
            
            console.log('Added user marker at:', [userLocation.lat, userLocation.lng]);
            
            // Fit map to show both user and geofence
            const bounds = L.latLngBounds([
                [userLocation.lat, userLocation.lng],
                [correctedLatitude, correctedLongitude]
            ]);
            geofenceMap.fitBounds(bounds, { padding: [20, 20] });
            
            console.log('Fitted map bounds to:', bounds);
        }

        function showLocationModal() {
            document.getElementById('location-modal').style.display = 'flex';
        }

        function showLocationError(message) {
            document.getElementById('location-modal').style.display = 'flex';
            document.querySelector('#location-modal h3').textContent = 'Location Error';
            document.querySelector('#location-modal p').textContent = message;
            document.getElementById('enable-location-btn').style.display = 'none';
        }

        function showScanner() {
            document.getElementById('location-modal').style.display = 'none';
            document.getElementById('geofence-modal').style.display = 'none';
            document.getElementById('scanner-content').style.display = 'block';
            startScanner();
        }

        // Enable location button handler
        document.getElementById('enable-location-btn').addEventListener('click', function() {
            requestLocationPermission();
        });

        // Cancel location button handler
        document.getElementById('cancel-location-btn').addEventListener('click', function() {
            window.location.href = '<?php echo ROOT ?>facilitator';
        });

        // Retry location button handler
        document.getElementById('retry-location-btn').addEventListener('click', function() {
            document.getElementById('geofence-modal').style.display = 'none';
            checkGeofenceAndShowScanner();
        });

        // Close geofence modal button handler
        document.getElementById('close-geofence-btn').addEventListener('click', function() {
            document.getElementById('geofence-modal').style.display = 'none';
        });

        function startScanner() {
            document.getElementById("result").textContent = "Waiting for scan...";
            document.getElementById("restart-btn").style.display = "none";
            document.getElementById("student-info").textContent = "";

            html5QrCode.start(
                currentFacingMode,
                { fps: 10, qrbox: { width: 300, height: 300 } },
                (decodedText) => {
                    document.getElementById("result").innerHTML = `<p>Decoded QR Code: ${decodedText}</p>`;
                    document.getElementById("loading-screen").style.display = "flex";
                    // Fetch student details before confirming attendance
                    fetch("", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `qrData=${encodeURIComponent(decodedText)}&atten_id=${encodeURIComponent("<?= $AttendanceID; ?>")}&fetchStudent=true`

                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log("Fetched Student Data:", data); // Debugging
                            document.getElementById("loading-screen").style.display = "none";
                            if (data.status === "success") {
                                document.getElementById("student-name").textContent = `Student: ${data.student}`;
                                document.getElementById("student-program").textContent = `Program: ${data.program}`;

                                // Then handle image asynchronously
                                if (data.studentProfile) {
                                    const img = new Image();
                                    img.onload = function() {
                                        document.getElementById("student-image").src = this.src;
                                        document.getElementById("student-image").style.display = "block";
                                        document.getElementById("student-image").style.maxWidth = "150px";
                                        document.getElementById("student-image").style.maxHeight = "150px";

                                        // Show confirmation modal AFTER image loads
                                        document.getElementById("confirmation-modal").style.display = "flex";
                                    };
                                    img.src = `data:image/jpeg;base64,${data.studentProfile}`;
                                    // Hide image container while loading
                                    document.getElementById("student-image").style.display = "none";
                                } else {
                                    document.getElementById("student-image").style.display = "none";
                                    // Show confirmation modal immediately if no image
                                    document.getElementById("confirmation-modal").style.display = "flex";
                                }

                                // Show confirmation modal
                                document.getElementById("confirmation-modal").style.display = "flex";

                                // Confirm attendance
                                document.getElementById("confirm-btn").onclick = () => {
                                    document.getElementById("confirmation-modal").style.display = "none";

                                    // Proceed with recording attendance
                                    fetch("", {
                                        method: "POST",
                                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                        body: `qrData=${encodeURIComponent(decodedText)}&atten_id=${encodeURIComponent("<?= $AttendanceID; ?>")}&confirm=true`
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            const studentInfoElement = document.getElementById("student-info");

                                            if (data.status === "success") {
                                                studentInfoElement.innerHTML = `Student: ${data.student} <br> ${data.message}`;
                                                studentInfoElement.style.color = "#4CAF50";
                                            } else {
                                                studentInfoElement.innerHTML = data.message;
                                                studentInfoElement.style.color = "red";
                                            }

                                            // Show "Scan Again" button
                                            document.getElementById("restart-btn").style.display = "block";
                                        })
                                        .catch(error => {
                                            console.error("Error:", error);
                                            document.getElementById("student-info").textContent = "An error occurred: " + error.message;
                                            document.getElementById("student-info").style.color = "red";
                                            document.getElementById("restart-btn").style.display = "block";
                                        });
                                };

                                // Cancel attendance
                                document.getElementById("cancel-btn").onclick = () => {
                                    document.getElementById("confirmation-modal").style.display = "none";
                                    document.getElementById("result").innerHTML = `<p style="color:red;">Attendance recording cancelled.</p>`;

                                    // Show "Scan Again" button
                                    document.getElementById("restart-btn").style.display = "block";
                                };
                            } else {
                                document.getElementById("student-info").textContent = data.message;
                                document.getElementById("student-info").style.color = "red";

                                // Show "Scan Again" button
                                document.getElementById("restart-btn").style.display = "block";
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            document.getElementById("student-info").textContent = "An error occurred: " + error.message;
                            document.getElementById("student-info").style.color = "red";

                            // Show "Scan Again" button
                            document.getElementById("restart-btn").style.display = "block";
                        });

                    html5QrCode.stop();
                }
            );
        }

        // Restart button handler
        document.getElementById("restart-btn").addEventListener("click", () => {
            document.getElementById("restart-btn").style.display = "none";
            location.reload();
        });

        // Flip camera button handler
        document.getElementById("flip-camera-btn").addEventListener("click", () => {
            currentFacingMode.facingMode = currentFacingMode.facingMode === "environment" ? "user" : "environment";
            html5QrCode.stop().then(startScanner).catch(console.error);
        });
    </script>
<?php else: ?>
    <div class="max-w-2xl mx-auto glass-card rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black p-8 text-center">
        <div class="text-red-600 text-6xl mb-4">⚠️</div>
        <h2 class="text-2xl font-bold text-red-600 mb-4">No Ongoing Event</h2>
        <p class="text-gray-600 mb-6">There is no active attendance event available for scanning.</p>
        <a id="back-btn" href="<?php echo ROOT ?>facilitator" class="secondary-btn inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
<?php endif; ?>

<!-- Loading Screen -->
<div id="loading-screen" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.9); z-index:1000; align-items:center; justify-content:center; backdrop-filter: blur(5px);">
    <div class="text-center">
        <div class="loader"></div>
        <p class="text-[#a31d1d] font-semibold text-lg mt-4">Loading student data...</p>
    </div>
</div>

<script>

</script>
</body>
<script>
    //// Track if the user is navigating away or submitting a form
    //let isNavigating = false;
    //
    //// Detect clicks on links or buttons that lead to navigation
    //document.addEventListener('click', function(event) {
    //    const target = event.target.closest('a, button[type="submit"], input[type="submit"]');
    //    if (target) {
    //        isNavigating = true;
    //    }
    //});
    //
    //// Detect form submissions
    //document.addEventListener('submit', function() {
    //    isNavigating = true;
    //});
    //
    //// Use beforeunload to detect when the user is leaving the page
    //window.addEventListener('beforeunload', function(event) {
    //    // If the user is NOT navigating or submitting a form, they are closing the tab/browser
    //    if (!isNavigating) {
    //        navigator.sendBeacon('<?php //echo ROOT; ?>//logout', new URLSearchParams({
    //            action: 'logOutOnClose'
    //        }));
    //    }
    //});

</script>
</html>