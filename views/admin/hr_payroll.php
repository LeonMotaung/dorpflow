<?php
/**
 * DorpFlow ERP - HR & Payroll Dashboard View
 */
?>
<div class="row g-4">
    <!-- Notifications -->
    <div class="col-12">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Payment Gateway Configuration -->
    <div class="col-lg-5">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h4 class="mb-2 text-primary fw-bold"><i class="fa-solid fa-credit-card text-accent me-2"></i>Payment Gateway</h4>
            <p class="text-muted mb-4">Configure the payment gateway integration used by the municipality for both salary payroll disbursements and resident utility billing collections.</p>

            <form action="<?php echo APP_URL; ?>/public/index.php/admin/hr-payroll/gateway" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="gatewaySelect">Active Gateway Channel</label>
                    <select name="payment_gateway" id="gatewaySelect" class="form-select">
                        <option value="Yoco" <?php echo ($muni['payment_gateway'] ?? '') === 'Yoco' ? 'selected' : ''; ?>>Yoco (Card & EFT Gateway)</option>
                        <option value="Peach Payments" <?php echo ($muni['payment_gateway'] ?? '') === 'Peach Payments' ? 'selected' : ''; ?>>Peach Payments</option>
                        <option value="PayFast" <?php echo ($muni['payment_gateway'] ?? '') === 'PayFast' ? 'selected' : ''; ?>>PayFast</option>
                        <option value="Ozow" <?php echo ($muni['payment_gateway'] ?? '') === 'Ozow' ? 'selected' : ''; ?>>Ozow Instant EFT</option>
                        <option value="Standard Bank API" <?php echo ($muni['payment_gateway'] ?? '') === 'Standard Bank API' ? 'selected' : ''; ?>>Standard Bank Business API</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="merchantId">Merchant / Client ID</label>
                    <input type="text" name="gateway_merchant_id" id="merchantId" class="form-control" value="<?php echo htmlspecialchars($muni['gateway_merchant_id'] ?? ''); ?>" placeholder="e.g. MCH_890312">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="apiKey">Gateway secret key / Token</label>
                    <input type="password" name="gateway_api_key" id="apiKey" class="form-control" value="<?php echo htmlspecialchars($muni['gateway_api_key'] ?? ''); ?>" placeholder="••••••••••••••••">
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 mt-3"><i class="fa-solid fa-save me-1"></i> Save Gateway Settings</button>
            </form>
        </div>
    </div>

    <!-- Payroll Disbursement Console -->
    <div class="col-lg-7">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h4 class="mb-2 text-primary fw-bold"><i class="fa-solid fa-hand-holding-dollar text-accent me-2"></i>Pay Run Disbursement</h4>
            <p class="text-muted mb-4">Execute monthly salary pay runs using your configured gateway. Check payroll totals below before initiating batch transfers.</p>
            
            <?php 
            $totalCost = 0;
            foreach ($employees as $e) {
                $totalCost += $e['salary'];
            }
            ?>
            
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3 text-center border">
                        <small class="text-muted d-block mb-1 fw-bold">TOTAL STAFF</small>
                        <h4 class="mb-0 fw-bold text-dark"><?php echo count($employees); ?> Employees</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3 text-center border">
                        <small class="text-muted d-block mb-1 fw-bold">TOTAL RUN COST</small>
                        <h4 class="mb-0 fw-bold text-primary">R<?php echo number_format($totalCost, 2); ?></h4>
                    </div>
                </div>
            </div>

            <form action="<?php echo APP_URL; ?>/public/index.php/admin/hr-payroll/disburse" method="POST" onsubmit="return confirm('Are you sure you want to disburse R<?php echo number_format($totalCost, 2); ?> salaries via <?php echo htmlspecialchars($muni['payment_gateway'] ?? 'Peach Payments'); ?>?');">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="alert alert-warning border-0 small mb-4" style="background-color: rgba(255, 193, 7, 0.1); color: #856404;">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Pay Run Check:</strong> All salaries will be batched and disbursed to linked employee bank accounts via API integration.
                </div>

                <button type="submit" class="btn btn-accent-custom w-100 py-3 fs-5" style="border-radius:12px;" <?php echo $totalCost <= 0 ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-money-bill-transfer me-1"></i> Run Payroll & Disburse via <?php echo htmlspecialchars($muni['payment_gateway'] ?? 'Peach Payments'); ?>
                </button>
            </form>
        </div>
    </div>

    <!-- HR Employee Salaries Table -->
    <div class="col-12 mt-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1 text-primary fw-bold"><i class="fa-solid fa-users-line text-accent me-2"></i>HR Salary Register</h4>
                    <p class="text-muted mb-0 small">Set the monthly base salary for municipal employees.</p>
                </div>
                <a href="<?php echo APP_URL; ?>/public/index.php/employees/create" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-plus me-1"></i> Onboard New Staff</a>
            </div>

            <form action="<?php echo APP_URL; ?>/public/index.php/admin/hr-payroll/salaries" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="table-responsive">
                    <table class="table align-middle border-light">
                        <thead class="table-light text-muted small" style="font-weight: 600;">
                            <tr>
                                <th>Staff Name</th>
                                <th>Role / Rank</th>
                                <th>Contact Email</th>
                                <th>Contact Phone</th>
                                <th style="width: 200px;">Monthly Base Salary (ZAR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($employees)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No employees registered on the tenant system.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($employees as $e): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                                    <?php echo strtoupper(substr($e['full_name'], 0, 2)); ?>
                                                </div>
                                                <div>
                                                    <strong class="text-dark d-block"><?php echo htmlspecialchars($e['full_name']); ?></strong>
                                                    <small class="text-muted">ID: #<?php echo $e['id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary-subtle text-secondary"><?php echo $e['role_name']; ?></span></td>
                                        <td><code><?php echo htmlspecialchars($e['email']); ?></code></td>
                                        <td><?php echo htmlspecialchars($e['phone']); ?></td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">R</span>
                                                <input type="number" step="0.01" min="0" name="salaries[<?php echo $e['id']; ?>]" class="form-control fw-semibold" value="<?php echo number_format($e['salary'], 2, '.', ''); ?>" style="text-align: right;">
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary-custom px-5 py-2" <?php echo empty($employees) ? 'disabled' : ''; ?>><i class="fa-solid fa-floppy-disk me-1"></i> Update Base Salaries</button>
                </div>
            </form>
        </div>
    </div>
</div>
