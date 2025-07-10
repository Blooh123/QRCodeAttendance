    <?php
    global $imageSource, $buttonLabel, $buttonAction, $buttonClass, $year, $programList, $requiredAttendees, $attendanceDetails,$activityListLog;
    require "../app/core/imageConfig.php";

    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Attendance System • Edit Attendance</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <link rel="icon" type="image/x-icon" href="<?php echo ROOT?>assets/images/LOGO_QRCODE_v2.png">
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
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            
            /* Map preview styles */
            #map-preview {
                height: 250px;
                width: 100%;
                border-radius: 8px;
                border: 2px solid #d1d5db;
            }
        </style>
    </head>
    <body class="p-4 md:p-6 bg-[#f8f9fa]">

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-4xl mx-auto glass-card">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-edit text-[#a31d1d] text-3xl"></i>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Edit Attendance</h1>
            </div>
            <a href="<?php echo ROOT?>adminHome?page=Attendance" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
    </header>

    <div class="max-w-4xl mx-auto">
        <div class="glass-card rounded-2xl p-8 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                
                <form method="POST" class="space-y-6" action="<?php echo ROOT?>update_attendance" id="attendanceForm">
                    <div class="grid gap-6 grid-cols-1 lg:grid-cols-2">
                        <!-- Left Column - Basic Info -->
                        <div class="space-y-6">
                            <div>
                            <label for="eventName" class="block mb-2 text-sm font-medium text-gray-700">Event Name</label>
                                <input type="text" name="eventName" id="eventName" value="<?php echo htmlspecialchars($attendanceDetails['event_name']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" required>
                            </div>
                            
                            <div>
                            <label for="atten_id" class="block mb-2 text-sm font-medium text-gray-700">Attendance Status</label>
                                <input type="hidden" name="atten_id" id="atten_id" value="<?php echo htmlspecialchars($attendanceDetails['atten_id']); ?>" required>
                            <input type="hidden" name="atten_status" id="atten_status" value="<?php echo $attendanceDetails['atten_status']?>">
                                <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                                    <span class="font-medium text-gray-700"><?php echo $attendanceDetails['atten_status']?></span>
                                </div>
                        </div>

                            <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Required Attendees</label>
                                <ul class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 max-h-32 overflow-y-auto">
                                <?php
                                    // Check if required attendees data exists
                                    if (!empty($requiredAttendees) && is_array($requiredAttendees)):
                                    foreach ($requiredAttendees as $index => $program):
                                            $year = isset($acad_year[$index]) ? $acad_year[$index] : '';

                                            // Display "All years" if year is empty, null, or empty string
                                            $yearDisplay = (!empty($year) && $year !== '' && $year !== null) ? htmlspecialchars($year) : 'All years';
                                        ?>
                                            <li class="flex justify-between items-center p-2 border-b border-gray-200 last:border-b-0">
                                                <span class="font-medium text-gray-700"><?php echo htmlspecialchars($program); ?></span>
                                                <span class="text-gray-600">(<?php echo $yearDisplay; ?>)</span>
                                        </li>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <li class="text-gray-500 p-2">No required attendees listed</li>
                                <?php endif; ?>
                            </ul>
                        </div>

                            <div>
                                <label for="sanction" class="block mb-2 text-sm font-medium text-gray-700">Sanction (in hours)</label>
                                <input type="number" name="sanction" id="sanction" value="<?php echo htmlspecialchars($attendanceDetails['sanction']); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" required>
                            </div>
                        </div>

                        <!-- Right Column - Geofence Settings -->
                        <div class="space-y-6">
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-map-marker-alt text-[#a31d1d] mr-2"></i>
                                    Geofence Settings (Optional)
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="latitude" class="block mb-2 text-sm font-medium text-gray-700">Latitude</label>
                                        <input type="number" name="latitude" id="latitude" step="0.000001"
                                               value="<?php echo htmlspecialchars($attendanceDetails['latitude'] ?? ''); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                                               placeholder="e.g., 7.4474">
                                    </div>
                                    <div>
                                        <label for="longitude" class="block mb-2 text-sm font-medium text-gray-700">Longitude</label>
                                        <input type="number" name="longitude" id="longitude" step="0.000001"
                                               value="<?php echo htmlspecialchars($attendanceDetails['longitude'] ?? ''); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                                               placeholder="e.g., 125.8025">
                                    </div>
                                    <div>
                                        <label for="radius" class="block mb-2 text-sm font-medium text-gray-700">Radius (meters)</label>
                                        <input type="number" name="radius" id="radius" min="50" max="5000"
                                               value="<?php echo htmlspecialchars($attendanceDetails['radius'] ?? ''); ?>"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                                               placeholder="e.g., 500">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <button type="button" onclick="getCurrentLocation()" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black mr-2">
                                        <i class="fas fa-location-arrow mr-2"></i>Use Current Location
                                    </button>
                                    <button type="button" onclick="openMapSelector()" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black mr-2">
                                        <i class="fas fa-map mr-2"></i>Select on Map
                                    </button>
                                    <button type="button" onclick="checkAndFixCoordinateSwapping()" 
                                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black mt-4">
                                        <i class="fas fa-exchange-alt mr-2"></i>Fix Coordinates
                                    </button>
                                </div>
                                
                                <div class="text-sm text-gray-600 mb-4">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Leave empty to disable geofence restrictions for this attendance event.
                                </div>
                                
                                <!-- Debug info for coordinates -->
                                <!-- <div class="text-xs text-gray-500 mb-4 p-2 bg-gray-100 rounded">
                                    <strong>Debug Info:</strong><br>
                                    Raw Latitude: <?php echo var_export($attendanceDetails['latitude'] ?? 'NULL', true); ?><br>
                                    Raw Longitude: <?php echo var_export($attendanceDetails['longitude'] ?? 'NULL', true); ?><br>
                                    Raw Radius: <?php echo var_export($attendanceDetails['radius'] ?? 'NULL', true); ?>
                                </div> -->
                                
                                <!-- Map Preview Section -->
                                <div class="mt-6">
                                    <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-map text-blue-500 mr-2"></i>
                                        Location Preview
                                    </h4>
                                    <div id="map-preview" class="w-full h-64 rounded-lg border-2 border-gray-300 bg-gray-100 flex items-center justify-center">
                                        <div class="text-center text-gray-500">
                                            <i class="fas fa-map-marker-alt text-3xl mb-2"></i>
                                            <p>Enter coordinates above to see the location preview</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        This preview shows the geofence center location. The actual radius will be applied during attendance.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input to differentiate actions -->
                    <input type="hidden" name="action" id="action" value="">

                    <!-- Button Container for Better Layout -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t">
                        <!-- Save Changes Button -->
                        <button type="submit" onclick="setAction('save changes of',event)"
                                class="w-full sm:w-auto text-white inline-flex items-center bg-[#a31d1d] hover:bg-[#8a1818] font-medium rounded-xl px-6 py-2.5 text-center shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
                            <i class="fas fa-check mr-2"></i>
                            Save Changes
                        </button>

                        <!-- Start/Stop Attendance Button -->
                        <button type="submit"
                                onclick="setAction('<?php echo $buttonAction ?>', event)"
                                class="w-full sm:w-auto text-white inline-flex items-center <?php echo $buttonClass; ?> font-medium rounded-xl px-6 py-2.5 text-center shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200"
                            <?php echo ($buttonClass === 'hidden') ? 'hidden disabled style="pointer-events: none;"' : ''; ?>>
                            <i class="fas <?php echo ($buttonAction === 'start') ? 'fa-play' : 'fa-stop'; ?> mr-2"></i>
                            <?php echo $buttonLabel; ?>
                        </button>

                        <!-- Finished Attendance Button -->
                        <button type="submit" onclick="setAction('finished',event)"
                                class="w-full sm:w-auto text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 font-medium rounded-xl px-6 py-2.5 text-center shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200"
                                <?php echo ($buttonClass === 'hidden') ? 'hidden disabled style="pointer-events: none;"' : ''; ?>>
                            <i class="fas fa-flag-checkered mr-2"></i>
                            Finished Attendance
                        </button>
                    </div>
                </form>

                <!-- Activity Log Section -->
                <div class="glass-card rounded-2xl p-6 mt-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <!-- Toggle -->
                    <button onclick="toggleLogs()" class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 mb-4">
                        <i class="fas fa-clock"></i> View Activity Log
                    </button>

                    <!-- Log List -->
                    <div id="activity-log" class="mt-4">
                        <h3 class="text-xl font-bold mb-4 text-[#a31d1d] flex items-center">
                            <i class="fas fa-history mr-2"></i> Activity Log
                        </h3>

                        <!-- Search -->
                        <div class="flex flex-col md:flex-row md:items-center gap-2 mb-4">
                            <input type="text" id="search-input"
                                   placeholder="Search activities..."
                                   class="w-full md:w-80 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">

                            <button type="button" id="search-btn"
                                    class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-4 py-2 rounded-lg font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>

                        <!-- Scrollable container -->
                        <div class="h-60 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50 hide-scrollbar">
                            <ul class="space-y-3" id="activity-log-list">
                                <!-- Logs will be rendered here by JS -->
                            </ul>
                        </div>
                    </div>
                </div>

                <script src="<?php echo ROOT?>assets/js/editingAttendance.js"></script>
                        </div>
                    </div>
                </div>

                <script>
        // Map preview variables
        let previewMap, previewMarker, previewCircle;
        
        // Initialize map preview
        function initMapPreview() {
            const mapContainer = document.getElementById('map-preview');
            if (!mapContainer) return;
            
            // Clear existing content
            mapContainer.innerHTML = '';
            
            // Initialize map with default center
            const defaultCenter = [7.4474, 125.8025];
            previewMap = L.map('map-preview').setView(defaultCenter, 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(previewMap);
            
            // Add default marker
            previewMarker = L.marker(defaultCenter).addTo(previewMap);
            previewMarker.bindPopup("Geofence Center").openPopup();
        }
        
        // Update map preview with new coordinates
        function updateMapPreview() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            
            if (isNaN(lat) || isNaN(lng)) {
                // Show placeholder if coordinates are invalid
                const mapContainer = document.getElementById('map-preview');
                mapContainer.innerHTML = `
                    <div class="text-center text-gray-500">
                        <i class="fas fa-map-marker-alt text-3xl mb-2"></i>
                        <p class="text-sm">Enter coordinates above to see the location preview</p>
                    </div>
                `;
                return;
            }
            
            // Initialize map if not already done
            if (!previewMap) {
                initMapPreview();
            }
            
            const newCenter = [lat, lng];
            previewMap.setView(newCenter, 15);
            
            // Update marker
            if (previewMarker) {
                previewMap.removeLayer(previewMarker);
            }
            previewMarker = L.marker(newCenter).addTo(previewMap);
            previewMarker.bindPopup("Geofence Center").openPopup();
            
            // Add radius circle if radius is set
            const radius = parseFloat(document.getElementById('radius').value);
            if (!isNaN(radius) && radius > 0) {
                // Remove existing circle if any
                if (previewMap.hasLayer && previewMap.hasLayer(previewCircle)) {
                    previewMap.removeLayer(previewCircle);
                }
                
                // Add new circle
                previewCircle = L.circle(newCenter, {
                    radius: radius,
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.2,
                    weight: 2
                }).addTo(previewMap);
                previewCircle.bindPopup(`Geofence Area<br>Radius: ${radius}m`);
            }
        }
        
        // Geofence location functions
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                        document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                        if (!document.getElementById('radius').value) {
                            document.getElementById('radius').value = '500';
                        }
                        updateMapPreview();
                        Swal.fire({
                            title: 'Location Set!',
                            text: 'Current location has been set as geofence center.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    function(error) {
                        Swal.fire({
                            title: 'Location Error',
                            text: 'Unable to get your current location. Please enter coordinates manually.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                Swal.fire({
                    title: 'Not Supported',
                    text: 'Geolocation is not supported by your browser.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
        
        function openMapSelector() {
            // Open the map page in a new window/tab
            const mapUrl = '<?php echo ROOT?>map';
            const mapWindow = window.open(mapUrl, 'mapSelector', 'width=800,height=600,scrollbars=yes,resizable=yes');
            
            // Listen for messages from the map window
            window.addEventListener('message', function(event) {
                if (event.origin !== window.location.origin) return;
                
                if (event.data.type === 'geofenceData') {
                    const data = event.data.geofence;
                    document.getElementById('latitude').value = data.center.latitude.toFixed(6);
                    document.getElementById('longitude').value = data.center.longitude.toFixed(6);
                    document.getElementById('radius').value = data.radius;
                    
                    updateMapPreview();
                    
                    Swal.fire({
                        title: 'Location Set!',
                        text: 'Geofence location has been set from the map.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    
                    // Close the map window
                    if (mapWindow && !mapWindow.closed) {
                        mapWindow.close();
                    }
                }
            });
        }
        
                // Add event listeners for coordinate inputs
        document.addEventListener('DOMContentLoaded', function() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const radiusInput = document.getElementById('radius');
            
            if (latInput && lngInput) {
                // Check for coordinate swapping on page load
                checkAndFixCoordinateSwapping();
                
                // Update map preview when coordinates are entered
                latInput.addEventListener('input', function() {
                    if (latInput.value && lngInput.value) {
                        updateMapPreview();
                    }
                });
                
                lngInput.addEventListener('input', function() {
                    if (latInput.value && lngInput.value) {
                        updateMapPreview();
                    }
                });
                
                // Update map preview when radius changes
                if (radiusInput) {
                    radiusInput.addEventListener('input', function() {
                        if (latInput.value && lngInput.value) {
                            updateMapPreview();
                        }
                    });
                }
                
                // Update map preview when coordinates lose focus (for better UX)
                latInput.addEventListener('blur', function() {
                    if (latInput.value && lngInput.value) {
                        updateMapPreview();
                    }
                });
                
                lngInput.addEventListener('blur', function() {
                    if (latInput.value && lngInput.value) {
                        updateMapPreview();
                    }
                });
                
                // Initialize map preview if coordinates are already set
                if (latInput.value && lngInput.value) {
                    setTimeout(updateMapPreview, 100);
                } else {
                    // Initialize empty map preview
                    initMapPreview();
                }
            }
        });
        
        // Function to check and fix coordinate swapping
        function checkAndFixCoordinateSwapping() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            
            if (!latInput || !lngInput) return;
            
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            
            if (isNaN(lat) || isNaN(lng)) return;
            
            // Check if coordinates appear to be swapped
            // Latitude should be between -90 and 90, longitude between -180 and 180
            // If latitude > 90 or longitude < 90, they might be swapped
            if (lat > 90 || lng < 90) {
                console.log('Detected coordinate swapping in edit form, correcting...');
                console.log('Original coordinates:', { lat: lat, lng: lng });
                
                // Swap the coordinates
                const tempLat = lat;
                const tempLng = lng;
                
                latInput.value = tempLng.toFixed(6);
                lngInput.value = tempLat.toFixed(6);
                
                console.log('Corrected coordinates:', { 
                    lat: tempLng.toFixed(6), 
                    lng: tempLat.toFixed(6) 
                });
                
                // Show notification to user
                Swal.fire({
                    title: 'Coordinates Corrected',
                    text: 'The latitude and longitude values were swapped and have been automatically corrected.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
                
                // Update map preview with corrected coordinates
                setTimeout(updateMapPreview, 100);
            }
        }

        // Pass PHP array to JS
        const fullActivityLog = <?php echo json_encode($activityListLog); ?>;

                    // Render logs
                    function renderLogs(logs) {
                        const list = document.getElementById("activity-log-list");
                        list.innerHTML = ''; // Clear
                        if (logs.length === 0) {
                list.innerHTML = '<li class="text-gray-500 text-sm text-center py-4">No logs found.</li>';
                        } else {
                            logs.forEach(log => {
                                const item = document.createElement("li");
                    item.className = "border border-gray-200 rounded-lg p-4 text-gray-700 bg-white hover:shadow-md transition-shadow duration-200";
                                item.innerHTML = `
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <span class="font-semibold text-gray-800">${log.activity}</span>
                                <div class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>${log.time_created}
                                </div>
                            </div>
                        </div>
                `;
                                list.appendChild(item);
                            });
                        }
                    }

                    // Search functionality
                    document.getElementById("search-btn").addEventListener("click", () => {
                        const keyword = document.getElementById("search-input").value.toLowerCase();
                        const filtered = fullActivityLog.filter(log =>
                            log.activity.toLowerCase().includes(keyword) ||
                            log.time_created.toLowerCase().includes(keyword)
                        );
                        renderLogs(filtered);
                    });

                    // Optional: Enter key triggers search
                    document.getElementById("search-input").addEventListener("keypress", function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            document.getElementById("search-btn").click();
                        }
                    });

        // Toggle logs visibility
                    function toggleLogs() {
                        const logDiv = document.getElementById("activity-log");
                        logDiv.classList.toggle("hidden");
                        if (!logDiv.classList.contains("hidden")) {
                            renderLogs(fullActivityLog);
                        }
                    }

        // Render logs by default on page load
                    document.addEventListener("DOMContentLoaded", () => {
                        renderLogs(fullActivityLog);
                    });
                </script>

    </body>
    </html>