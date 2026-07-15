<?php
/**
 * DorpFlow ERP - Super Admin Dashboard View
 */
?>
<div class="row g-4 mb-4">
    <!-- Stat 1 -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">SUBSCRIBED COUNCILS</small>
            <h3 class="mb-0 text-primary"><?php echo count($municipalities); ?> Active</h3>
        </div>
    </div>
    <!-- Stat 2 -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">GLOBAL SUBSCRIPTION ARR</small>
            <h3 class="mb-0 text-success">R<?php 
                $arr = array_sum(array_column($municipalities, 'sub_price')) * 12;
                echo number_format($arr, 2);
            ?></h3>
        </div>
    </div>
    <!-- Stat 3 -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">PLATFORM SMS DISPATCHED</small>
            <h3 class="mb-0 text-info"><?php echo number_format($total_sms); ?> SMSs</h3>
        </div>
    </div>
    <!-- Stat 4 -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border border-light bg-white rounded-3">
            <small class="text-muted d-block fw-bold" style="font-size:0.75rem;">GATEWAY MARGIN / PROFIT</small>
            <h3 class="mb-0 text-warning">R<?php echo number_format($total_profit, 2); ?></h3>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-building-shield me-2 text-primary"></i>Platform Municipal Tenancy Ledger</h5>
        <button class="btn btn-sm btn-primary-custom"><i class="fa-solid fa-circle-plus me-1"></i> Provision Municipality</button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Municipality Name</th>
                    <th>Tenant Subdomain</th>
                    <th>Billing Plan</th>
                    <th>Monthly Price</th>
                    <th>Storage Allocation</th>
                    <th>Gateway Usage</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($municipalities as $m): ?>
                    <tr>
                        <td><strong><?php echo $m['name']; ?></strong></td>
                        <td><code><?php echo $m['subdomain']; ?>.dorpflow.com</code></td>
                        <td><span class="badge bg-primary-subtle text-primary"><?php echo $m['plan']; ?></span></td>
                        <td><strong>R<?php echo number_format($m['sub_price'], 2); ?></strong></td>
                        <td>
                            <small class="text-muted d-block"><?php echo $m['storage_used_mb']; ?>MB of <?php echo $m['storage_limit_mb']; ?>MB</small>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: <?php echo ($m['storage_used_mb'] / $m['storage_limit_mb']) * 100; ?>%;"></div>
                            </div>
                        </td>
                        <td>
                            <small class="d-block text-muted"><i class="fa-solid fa-mobile-screen me-1"></i> SMS: <?php echo $m['sms_sent']; ?></small>
                            <small class="d-block text-muted"><i class="fa-regular fa-envelope me-1"></i> Email: <?php echo $m['emails_sent']; ?></small>
                        </td>
                        <td><span class="badge bg-success text-white">Active</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
