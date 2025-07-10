<?php
require_once '../app/core/config.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System • Create Attendance</title>
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
    </style>
</head>
<body class="p-4 md:p-6 bg-[#f8f9fa]">

<!-- Header -->
<header class="bg-white/90 backdrop-blur-lg shadow-md rounded-2xl p-6 mb-8 max-w-2xl mx-auto glass-card">
    <div class="flex items-center space-x-3">
        <i class="fas fa-calendar-plus text-[#a31d1d] text-3xl"></i>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] tracking-tight">Create New Attendance</h1>
    </div>
</header>

<div class="max-w-2xl mx-auto">
    <div class="glass-card rounded-2xl p-8 mb-8 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
        <form method="POST" action="<?php echo ROOT?>add_attendance" class="space-y-6">
            <div>
                <label for="eventName" class="block mb-2 text-sm font-medium text-gray-700">Event Name</label>
                <input type="text" name="eventName" id="eventName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" placeholder="Event name" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="program" class="block mb-2 text-sm font-medium text-gray-700">Program</label>
                    <select name="program[]" class="program-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]" required>
                        <option value="">Select program</option>
                        <option value="AllStudents">All Students</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?php echo htmlspecialchars($program['program']); ?>">
                                <?php echo htmlspecialchars($program['program']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="year" class="block mb-2 text-sm font-medium text-gray-700">Year</label>
                    <select name="year[]" class="year-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]">
                        <option value="">Select year</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?php echo htmlspecialchars($year['acad_year']); ?>">
                                <?php echo htmlspecialchars($year['acad_year']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="space-y-1 mt-2">
                <div id="additional-fields"></div>
                <button type="button"
                        onclick="addFieldSet()"
                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Required Attendance Record</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="required_attendance[]" value="time_in" checked required
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Time In (Default)</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="required_attendance[]" value="time_out"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Time Out</span>
                    </label>
                </div>
            </div>
            <div>
                <label for="sanction" class="block mb-2 text-sm font-medium text-gray-700">Sanction (in hours)</label>
                <input type="number" name="sanction" id="sanction"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                       placeholder="Sanction" required>
            </div>
            
            <!-- Geofence Section -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-[#a31d1d] mr-2"></i>
                    Geofence Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="latitude" class="block mb-2 text-sm font-medium text-gray-700">Latitude</label>
                        <input type="number" name="latitude" id="latitude" step="0.000001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                               placeholder="e.g., 7.4474">
                    </div>
                    <div>
                        <label for="longitude" class="block mb-2 text-sm font-medium text-gray-700">Longitude</label>
                        <input type="number" name="longitude" id="longitude" step="0.000001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                               placeholder="e.g., 125.8025">
                    </div>
                    <div>
                        <label for="radius" class="block mb-2 text-sm font-medium text-gray-700">Radius (meters)</label>
                        <input type="number" name="radius" id="radius" min="50" max="5000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]"
                               placeholder="e.g., 500">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" onclick="getCurrentLocation()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        <i class="fas fa-location-arrow mr-2"></i>Use Current Location
                    </button>
                    <button type="button" onclick="openMapSelector()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium ml-2 shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        <i class="fas fa-map mr-2"></i>Select on Map
                    </button>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Leave empty to disable geofence restrictions for this attendance event.
                </div>
                
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
            <div class="flex justify-end gap-4">
                <a href="<?php echo ROOT?>adminHome?page=Attendance"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit"
                        class="bg-[#a31d1d] hover:bg-[#8a1818] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-check"></i> Done
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    // Fetch programs and years from PHP
    let programs = <?php echo json_encode($programs); ?>;
    let years = <?php echo json_encode($years); ?>;
    function addFieldSet() {
        let container = document.getElementById("additional-fields");
        let fieldSet = document.createElement("div");
        fieldSet.className = "relative bg-gray-100 p-4 rounded-lg shadow-md border border-gray-300 mt-2";
        let programDiv = document.createElement("div");
        programDiv.className = "mb-2";
        let programLabel = document.createElement("label");
        programLabel.className = "block mb-2 text-sm font-medium text-gray-700";
        programLabel.textContent = "Program";
        let programSelect = document.createElement("select");
        programSelect.name = "program[]";
        programSelect.className = "program-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]";
        let programOptions = `<option value="">Select program</option>
                              <option value="AllStudents">All Students</option>`;
        programs.forEach(program => {
            programOptions += `<option value="${program.program}">${program.program}</option>`;
        });
        programSelect.innerHTML = programOptions;
        programDiv.appendChild(programLabel);
        programDiv.appendChild(programSelect);
        let yearDiv = document.createElement("div");
        let yearLabel = document.createElement("label");
        yearLabel.className = "block mb-2 text-sm font-medium text-gray-700";
        yearLabel.textContent = "Year";
        let yearSelect = document.createElement("select");
        yearSelect.name = "year[]";
        yearSelect.className = "year-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#a31d1d]";
        let yearOptions = `<option value="">Select year</option>`;
        years.forEach(year => {
            yearOptions += `<option value="${year.acad_year}">${year.acad_year}</option>`;
        });
        yearSelect.innerHTML = yearOptions;
        yearDiv.appendChild(yearLabel);
        yearDiv.appendChild(yearSelect);
        let removeBtn = document.createElement("button");
        removeBtn.className = "absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-lg hover:bg-red-600";
        removeBtn.textContent = "Remove";
        removeBtn.onclick = function () {
            container.removeChild(fieldSet);
        };
        fieldSet.appendChild(removeBtn);
        fieldSet.appendChild(programDiv);
        fieldSet.appendChild(yearDiv);
        container.appendChild(fieldSet);
    }
    
    // Map preview variables
    let previewMap, previewMarker;
    
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
                    <p>Enter coordinates above to see the location preview</p>
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
                    updateMapPreview(); // Update map preview
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
                
                updateMapPreview(); // Update map preview
                
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
        
        if (latInput && lngInput) {
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
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if (isset($_SESSION['success_message'])): ?>
    Swal.fire({
        title: 'Success!',
        text: '<?php echo $_SESSION['success_message']; ?>',
        icon: 'success',
        confirmButtonText: 'OK'
    });
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
</script>
</body>
</html>