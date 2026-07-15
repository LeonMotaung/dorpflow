<?php
/**
 * DorpFlow ERP - Super Admin Subscription Registry View
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-credit-card me-2"></i>SaaS Subscriptions Ledger</h4>
                <p class="text-muted mb-0">Monitor ARR contract valuations, plan packages, and municipal renewal timelines.</p>
            </div>
            <div class="bg-primary text-white rounded px-3 py-2 fw-semibold" style="font-size:0.9rem;">
                Total ARR: R<?php 
                    $arr = array_sum(array_column($subscriptions, 'price')) * 12;
                    echo number_format($arr, 2);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Municipality Node</th>
                    <th>Plan Tier</th>
                    <th>Monthly License Cost</th>
                    <th>Billing Frequency</th>
                    <th>Next Scheduled Renewal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subscriptions)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No subscriptions recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subscriptions as $s): ?>
                        <tr>
                            <td><strong><?php echo $s['municipality_name']; ?></strong></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?php echo $s['plan']; ?></span></td>
                            <td><strong>R<?php echo number_format($s['price'], 2); ?></strong></td>
                            <td><span class="text-muted"><i class="fa-solid fa-arrows-spin me-1"></i> <?php echo ucfirst($s['billing_cycle']); ?></span></td>
                            <td><i class="fa-regular fa-calendar-check me-1 text-muted"></i> <?php echo date('d M Y', strtotime($s['next_billing_date'])); ?></td>
                            <td><span class="badge bg-success text-white">active</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
