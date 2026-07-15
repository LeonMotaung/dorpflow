<?php
/**
 * DorpFlow ERP - Public Outages and Outage Status Board View (Unauthenticated)
 */
?>
<!-- Custom Navigation for Public Status Page -->
<?php if (!Auth::user()): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?php echo APP_URL; ?>/dorpflow.png" alt="Logo" style="max-height:30px;" class="bg-white p-1 rounded me-2">
            <span class="fw-bold"><?php echo ucfirst($tenant); ?> Public Status</span>
        </a>
        <a href="<?php echo APP_URL; ?>/public/index.php/login?tenant=<?php echo $tenant; ?>" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-right-to-bracket me-1"></i> Staff Login</a>
    </div>
</nav>
<?php endif; ?>

<div class="container pb-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
                <h4 class="mb-1 text-primary fw-bold"><i class="fa-solid fa-tower-broadcast text-accent me-2"></i>Service Outage Status Board</h4>
                <p class="text-muted mb-0">Real-time public tracking of municipal utility repairs, service disruptions, and scheduled maintenances.</p>
            </div>
        </div>
    </div>

    <!-- Active Outages Summary -->
    <div class="row g-4 mb-4 text-center">
        <div class="col-6 col-md-3">
            <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
                <i class="fa-solid fa-plug-circle-bolt text-danger fs-3 mb-2"></i>
                <h3 class="mb-0 fw-bold"><?php echo $power_outages; ?></h3>
                <small class="text-muted">Power Outages</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
                <i class="fa-solid fa-droplet-slash text-info fs-3 mb-2"></i>
                <h3 class="mb-0 fw-bold"><?php echo $water_outages; ?></h3>
                <small class="text-muted">Water Disruption</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
                <i class="fa-solid fa-network-wired text-success fs-3 mb-2"></i>
                <h3 class="mb-0 fw-bold text-success">OPERATIONAL</h3>
                <small class="text-muted">System Grid</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
                <i class="fa-solid fa-clipboard-check text-success fs-3 mb-2"></i>
                <h3 class="mb-0 fw-bold">100%</h3>
                <small class="text-muted">Audit SLA</small>
            </div>
        </div>
    </div>

    <!-- Active Tickets Board -->
    <div class="card border border-light shadow-sm bg-white rounded-4 p-4">
        <h5 class="fw-bold mb-4"><i class="fa-solid fa-circle-exclamation text-primary me-2"></i>Current Outages & Resolutions Log</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Department Category</th>
                        <th>Outage Details</th>
                        <th>Report Date</th>
                        <th>Priority Rating</th>
                        <th>Resolution Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No active outages logged. Everything is running operational!</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $t): ?>
                            <tr>
                                <td><code><?php echo $t['ticket_number']; ?></code></td>
                                <td>
                                    <span class="badge <?php 
                                        echo ($t['category'] === 'Electricity') ? 'bg-warning text-dark' : 'bg-info text-white'; 
                                    ?> px-2.5 py-1.5"><?php echo $t['category']; ?></span>
                                </td>
                                <td><span class="text-dark fw-medium"><?php echo $t['description']; ?></span></td>
                                <td><small class="text-muted"><?php echo date('d M Y H:i', strtotime($t['created_at'])); ?></small></td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary"><?php echo $t['priority']; ?></span>
                                </td>
                                <td>
                                    <span class="badge <?php 
                                        echo ($t['status'] === 'In Progress') ? 'bg-primary text-white' : 'bg-warning text-dark'; 
                                    ?>"><?php echo $t['status']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
