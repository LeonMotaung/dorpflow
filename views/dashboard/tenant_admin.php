<?php
/**
 * DorpFlow ERP - Municipal Admin / Operations Room Dashboard
 */
$db = Database::getConnection();
$totalTickets = $db->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
$pendingReview = $db->query("SELECT COUNT(*) FROM tickets WHERE status = 'Pending Review'")->fetchColumn();
$inProgress = $db->query("SELECT COUNT(*) FROM tickets WHERE status = 'In Progress'")->fetchColumn();
$completed = $db->query("SELECT COUNT(*) FROM tickets WHERE status = 'Completed'")->fetchColumn();

// Fetch ticket coordinates for mapping
$mappedTickets = $db->query("SELECT ticket_number, category, status, lat, lng FROM tickets WHERE lat IS NOT NULL")->fetchAll();
?>

<!-- Operational Metric Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">ALL LOGGED REQUESTS</small>
            <h3 class="mb-0 text-primary"><?php echo $totalTickets; ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">AWAITING REVIEW</small>
            <h3 class="mb-0 text-danger"><?php echo $pendingReview; ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">IN PROGRESS FAULTS</small>
            <h3 class="mb-0 text-warning"><?php echo $inProgress; ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">RESOLVED / COMPLETED</small>
            <h3 class="mb-0 text-success"><?php echo $completed; ?></h3>
        </div>
    </div>
</div>

<!-- GIS Mapping & Recent Activity -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h5 class="mb-3 fw-bold"><i class="fa-solid fa-map-location-dot me-2 text-primary"></i>GIS Service Delivery Hotspot Map</h5>
            <div id="muniDashboardMap" style="height: 350px; border-radius:12px; border: 1px solid var(--border-color);"></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h5 class="mb-3 fw-bold"><i class="fa-solid fa-bolt me-2 text-warning"></i>Quick Operational Actions</h5>
            <div class="d-grid gap-3">
                <a href="<?php echo APP_URL; ?>/public/index.php/tickets/create" class="btn btn-primary-custom py-3 text-start"><i class="fa-solid fa-plus-circle me-2"></i> Log Fault Request</a>
                <a href="<?php echo APP_URL; ?>/public/index.php/tickets" class="btn btn-outline-secondary py-3 text-start text-dark" style="background:#F8FAFC;"><i class="fa-solid fa-list-check me-2"></i> Manage Work Orders</a>
                <a href="<?php echo APP_URL; ?>/public/index.php/assets" class="btn btn-outline-secondary py-3 text-start text-dark" style="background:#F8FAFC;"><i class="fa-solid fa-boxes-stacked me-2"></i> Track Municipal Assets</a>
                <a href="<?php echo APP_URL; ?>/public/index.php/fleet" class="btn btn-outline-secondary py-3 text-start text-dark" style="background:#F8FAFC;"><i class="fa-solid fa-truck-pickup me-2"></i> Audit Response Fleet</a>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        // Initialize Map centered around South African coordinates (resolved dynamically per tenant database coordinates if present, default to Stellenbosch area)
        const map = L.map('muniDashboardMap').setView([-33.9321, 18.8602], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Inject mapped tickets
        const tickets = <?php echo json_encode($mappedTickets); ?>;
        tickets.forEach(function(t) {
            if (t.lat && t.lng) {
                const color = t.status === 'Completed' ? 'green' : (t.status === 'In Progress' ? 'orange' : 'red');
                const marker = L.circleMarker([t.lat, t.lng], {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    fillOpacity: 0.8
                }).addTo(map);
                
                marker.bindPopup(`<strong>${t.ticket_number}</strong><br>Type: ${t.category}<br>Status: ${t.status}`);
            }
        });
    });
</script>
