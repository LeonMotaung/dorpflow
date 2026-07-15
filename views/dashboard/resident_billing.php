<?php
/**
 * DorpFlow ERP - Resident Portal Billing & checkout gates
 */
?>
<div class="row mb-4">
    <div class="col-12 col-lg-8">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h4 class="mb-1 text-primary"><i class="fa-solid fa-receipt me-2"></i>My Municipal Utility Invoices</h4>
            <p class="text-muted mb-0">View monthly water & electricity telemetry billing invoices and process payments online.</p>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100 d-flex justify-content-center">
            <small class="text-muted fw-bold" style="font-size:0.75rem;">OUTSTANDING ACCOUNT BALANCE</small>
            <h3 class="mb-0 <?php echo $unpaid_total > 0 ? 'text-danger' : 'text-success'; ?> fw-bold">
                R<?php echo number_format($unpaid_total, 2); ?>
            </h3>
        </div>
    </div>
</div>

<?php if (isset($_GET['success']) && $_GET['success'] === 'payment_completed'): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> Payment completed successfully! Thank you for paying your municipal account. Receipt sent via SMS.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- LEFT PANEL: PEACH PAYMENTS CHECKOUT GATE -->
    <div class="col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-credit-card me-1 text-primary"></i> Secure Payments Portal</h5>
            <div class="text-center py-2 mb-3 bg-light rounded">
                <small class="text-muted d-block">PROCESSED VIA</small>
                <strong style="color: var(--primary); font-size: 1.1rem;"><i class="fa-solid fa-shield-halved text-accent"></i> Peach Payments</strong>
            </div>

            <?php if ($unpaid_total > 0): ?>
                <form action="<?php echo APP_URL; ?>/public/index.php/resident/billing/checkout" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="amount" value="<?php echo $unpaid_total; ?>">

                    <div class="mb-3">
                        <label class="form-label">Payment Amount (ZAR)</label>
                        <input type="text" class="form-control fw-bold" value="R<?php echo number_format($unpaid_total, 2); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Card Holder Name</label>
                        <input type="text" name="card_holder" class="form-control" placeholder="e.g. S Miller" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-credit-card"></i></span>
                            <input type="text" name="card_number" class="form-control" placeholder="4000 1234 5678 9010" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-7">
                            <label class="form-label">Expiry Date</label>
                            <input type="text" class="form-control text-center" placeholder="MM / YY" required>
                        </div>
                        <div class="col-5">
                            <label class="form-label">CVV</label>
                            <input type="password" class="form-control text-center" placeholder="123" maxlength="3" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3"><i class="fa-solid fa-lock me-1"></i> Pay R<?php echo number_format($unpaid_total, 2); ?></button>
                </form>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fa-solid fa-circle-check text-success fs-1 mb-3"></i>
                    <p class="mb-0 fw-semibold">Your account is fully paid! No outstanding payments required.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT PANEL: BILLING HISTORY -->
    <div class="col-lg-8">
        <div class="card border border-light shadow-sm bg-white rounded-4 p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-list text-primary me-2"></i>Utility Invoices Ledger</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Bill Reference</th>
                            <th>Billing Type</th>
                            <th>Meter Reading</th>
                            <th>Billing cost</th>
                            <th>Status</th>
                            <th>Generated Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bills)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No bill statements compiled yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bills as $b): ?>
                                <tr>
                                    <td><code>INV-<?php echo str_pad($b['id'], 6, '0', STR_PAD_LEFT); ?></code></td>
                                    <td>
                                        <span class="badge <?php 
                                            echo ($b['type'] === 'electricity') ? 'bg-warning text-dark' : 'bg-info text-white'; 
                                        ?> px-2.5 py-1.5"><?php echo ucfirst($b['type']); ?></span>
                                    </td>
                                    <td><?php echo $b['reading']; ?> kL/kWh</td>
                                    <td><strong>R<?php echo number_format($b['cost'], 2); ?></strong></td>
                                    <td>
                                        <span class="badge <?php 
                                            if ($b['status'] === 'paid') echo 'bg-success text-white';
                                            else echo 'bg-warning text-dark';
                                        ?>"><?php echo $b['status'] === 'paid' ? 'Paid' : 'Unpaid'; ?></span>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('d M Y H:i', strtotime($b['created_at'])); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
