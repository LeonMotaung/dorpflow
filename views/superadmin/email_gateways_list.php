<?php
/**
 * DorpFlow ERP - Email SMTP Gateway Logs View
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-envelope me-2"></i>Email SMTP Gateway Control</h4>
                <p class="text-muted mb-0">Monitor citizens and staff password resets, dispatches, and reports sent via SMTP relays.</p>
            </div>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <h5 class="fw-bold mb-4">SMTP Delivery Log Ledger</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Municipality Node</th>
                    <th>Recipient Address</th>
                    <th>Email Subject Header</th>
                    <th>Opens</th>
                    <th>Bounced</th>
                    <th>Dispatch Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No SMTP email logs recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td><strong><?php echo $l['municipality_name']; ?></strong></td>
                            <td><code><?php echo $l['recipient']; ?></code></td>
                            <td><?php echo $l['subject']; ?></td>
                            <td><span class="badge bg-info-subtle text-info"><?php echo $l['open_count']; ?> opens</span></td>
                            <td>
                                <span class="badge <?php 
                                    echo $l['bounced'] ? 'bg-danger text-white' : 'bg-success text-white';
                                ?>"><?php echo $l['bounced'] ? 'bounced' : 'delivered'; ?></span>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($l['created_at'])); ?></td>
                            <td><span class="badge bg-success text-white">sent</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
