<?php
/**
 * DorpFlow ERP - Super Admin API Usage & Metrics Console
 */
?>
<div class="row mb-4">
    <div class="col-12 col-lg-8">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h4 class="mb-1 text-primary"><i class="fa-solid fa-chart-line me-2"></i>API Usage Metrics Console</h4>
            <p class="text-muted mb-0">Monitor integrations usage, API request volumes, and active endpoints across all municipal nodes.</p>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100 d-flex justify-content-center">
            <small class="text-muted fw-bold" style="font-size:0.75rem;">GLOBAL API HITS REGISTERED</small>
            <h3 class="mb-0 text-primary">
                <?php 
                    $totalHits = array_sum(array_column($summary, 'total_hits'));
                    echo number_format($totalHits);
                ?> Hits
            </h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Summary by Municipality -->
    <div class="col-lg-4">
        <div class="card border border-light shadow-sm bg-white rounded-4 p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-bar text-primary me-2"></i>Usage by Tenant</h5>
            <ul class="list-group list-group-flush">
                <?php foreach ($summary as $s): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-light">
                        <div>
                            <strong><?php echo $s['name']; ?></strong>
                            <small class="d-block text-muted"><code><?php echo $s['subdomain']; ?>.dorpflow.gov.za</code></small>
                        </div>
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2" style="font-size:0.85rem; font-weight:600;">
                            <?php echo $s['total_hits']; ?> Hits
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Recent Logs List -->
    <div class="col-lg-8">
        <div class="card border border-light shadow-sm bg-white rounded-4 p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-list text-primary me-2"></i>Recent API Gateways Hits</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tenant</th>
                            <th>Endpoint</th>
                            <th>Method</th>
                            <th>IP Address</th>
                            <th>Status</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No API request logs recorded yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $l): ?>
                                <tr>
                                    <td><strong><?php echo $l['municipality_name']; ?></strong></td>
                                    <td><code><?php echo $l['endpoint']; ?></code></td>
                                    <td><span class="badge bg-secondary-subtle text-secondary"><?php echo $l['method']; ?></span></td>
                                    <td><small class="text-muted"><?php echo $l['ip_address']; ?></small></td>
                                    <td><span class="badge bg-success text-white"><?php echo $l['response_code']; ?></span></td>
                                    <td><?php echo date('d M Y H:i:s', strtotime($l['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
