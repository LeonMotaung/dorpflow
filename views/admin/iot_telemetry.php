<?php
/**
 * DorpFlow ERP - IoT Smart Telemetry Panel View
 */
?>
<div class="row mb-4">
    <div class="col-12 col-lg-8">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h4 class="mb-1 text-primary"><i class="fa-solid fa-gauge-high me-2"></i>IoT Smart Metering & Telemetry</h4>
            <p class="text-muted mb-0">Track digital water and power meter counts across municipal properties and auto-generate monthly bills.</p>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100 d-flex justify-content-center">
            <small class="text-muted fw-bold" style="font-size:0.75rem;">TELEMETRY READING LOGS</small>
            <h3 class="mb-0 text-success"><?php echo count($logs); ?> Devices Online</h3>
        </div>
    </div>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] === 'billing_generated'): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> Utility bills generated successfully! Notifications dispatched to all residents.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- LEFT PANEL: GENERATOR -->
    <div class="col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-circle-play me-1 text-primary"></i> Billing Cycles Dispatch</h5>
            <p class="text-muted small">Tally all unbilled meter logs and compile them into monthly consumer invoices. Citizens will automatically receive billing statements in their portals and SMS alerts.</p>
            
            <form action="<?php echo APP_URL; ?>/public/index.php/admin/iot-telemetry/generate-bills" method="POST" onsubmit="return confirm('Do you want to process billing cycles for all unbilled smart meter logs?')">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <button type="submit" class="btn btn-primary-custom w-100 py-3"><i class="fa-solid fa-receipt me-1"></i> Trigger Billing Cycle</button>
            </form>
        </div>
    </div>

    <!-- RIGHT PANEL: METER LOGS -->
    <div class="col-lg-8">
        <div class="card border border-light shadow-sm bg-white rounded-4 p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-network-wired text-primary me-2"></i>Live Smart Meter Readings</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Device Meter</th>
                            <th>Meter Type</th>
                            <th>Current Reading</th>
                            <th>Accrued Billing Cost</th>
                            <th>Tally Status</th>
                            <th>Log Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No telemetry logs registered.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $l): ?>
                                <tr>
                                    <td><strong><?php echo $l['meter_number']; ?></strong></td>
                                    <td>
                                        <span class="badge <?php 
                                            echo ($l['type'] === 'electricity') ? 'bg-warning text-dark' : 'bg-info text-white'; 
                                        ?> px-2 py-1"><?php echo ucfirst($l['type']); ?></span>
                                    </td>
                                    <td><?php echo $l['reading']; ?> kL/kWh</td>
                                    <td><strong>R<?php echo number_format($l['cost'], 2); ?></strong></td>
                                    <td>
                                        <span class="badge <?php 
                                            if ($l['status'] === 'paid') echo 'bg-success text-white';
                                            elseif ($l['status'] === 'billing_sent') echo 'bg-primary text-white';
                                            else echo 'bg-warning text-dark';
                                        ?>"><?php echo ucfirst($l['status']); ?></span>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('d M Y H:i:s', strtotime($l['created_at'])); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
