<?php
/**
 * DorpFlow ERP - Create Ticket Form
 */
?>
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
            <h4 class="mb-2 text-primary"><i class="fa-solid fa-circle-plus me-2"></i>Report Service Fault</h4>
            <p class="text-muted mb-4">Provide details on the infrastructural breakdown and select the location on the map.</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/public/index.php/tickets/create" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="ticketCategory">Fault Category *</label>
                        <select class="form-select" name="category" id="ticketCategory" required>
                            <option value="" disabled selected>Select Category</option>
                            <option>Water Leak / Burst Pipe</option>
                            <option>Electricity Outage / Cable Theft</option>
                            <option>Pothole / Damaged Road</option>
                            <option>Refuse Collection Missed</option>
                            <option>Street Light Outage</option>
                            <option>Public Safety / Traffic Hazard</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="ticketPriority">Priority Level *</label>
                        <select class="form-select" name="priority" id="ticketPriority">
                            <option>Low</option>
                            <option selected>Medium</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="ticketDesc">Description & Details *</label>
                        <textarea class="form-control" name="description" id="ticketDesc" rows="4" placeholder="Detail the fault, landmarks, and approximate severity..." required></textarea>
                    </div>

                    <!-- GIS Map Selector -->
                    <div class="col-12 mt-4">
                        <label class="form-label d-block"><i class="fa-solid fa-map-pin text-danger me-1"></i>Select Location coordinates on Map *</label>
                        <span class="text-muted d-block mb-2" style="font-size:0.75rem;">Click on the map grid below to mark the coordinates automatically.</span>
                        <div id="coordinatePickerMap" style="height: 250px; border-radius:10px; border:1px solid var(--border-color);" class="mb-3"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="useCurrentLocation()">
                            <i class="fa-solid fa-location-crosshairs me-1"></i> Capture My Current Location
                        </button>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label" for="latVal">Latitude</label>
                                <input type="text" class="form-control form-control-sm" name="lat" id="latVal" readonly placeholder="-33.9321">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="lngVal">Longitude</label>
                                <input type="text" class="form-control form-control-sm" name="lng" id="lngVal" readonly placeholder="18.8602">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary-custom w-100 py-3"><i class="fa-solid fa-paper-plane me-1"></i> Submit Report to Control Room</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>

<script>
    let marker = null;
    let map = null;

    window.addEventListener('load', function() {
        // Default center at Stellenbosch coordinates
        map = L.map('coordinatePickerMap').setView([-33.9321, 18.8602], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });
    });

    function updateMarker(lat, lng) {
        document.getElementById('latVal').value = lat.toFixed(6);
        document.getElementById('lngVal').value = lng.toFixed(6);

        if (marker !== null) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map)
            .bindPopup("Selected coordinate location.").openPopup();
        map.setView([lat, lng], 16);
    }

    function useCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                updateMarker(lat, lng);
            }, function(error) {
                alert("Geolocation failed: " + error.message);
            });
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    }

    function validateForm() {
        const lat = document.getElementById('latVal').value;
        const lng = document.getElementById('lngVal').value;
        if (!lat || !lng) {
            alert('Please select the fault location on the map or capture your location before submitting.');
            return false;
        }
        return true;
    }
</script>
