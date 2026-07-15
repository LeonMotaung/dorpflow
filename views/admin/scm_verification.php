<?php
/**
 * DorpFlow ERP - SCM CSD Supplier Verification Panel
 */
?>
<style>
.csd-badge-compliant { background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.3); }
.csd-badge-noncompliant { background: rgba(239,68,68,0.1); color: #991b1b; border: 1px solid rgba(239,68,68,0.3); }
.csd-badge-restricted { background: rgba(220,38,38,0.1); color: #7f1d1d; border: 1px solid rgba(220,38,38,0.3); }
.bee-bar { height: 6px; border-radius: 4px; background: #e5e7eb; overflow: hidden; }
.bee-bar-fill { height: 100%; background: linear-gradient(90deg, #0a2b4c, #00bfa5); border-radius: 4px; }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0a2b4c,#1e4d78);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-building-columns text-white fs-5"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-primary fw-bold">SCM CSD Supplier Register</h4>
                    <p class="mb-0 text-muted small">National Treasury Central Supplier Database verification for all municipal procurement entities.</p>
                </div>
                <div class="ms-auto">
                    <button class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        <i class="fa-solid fa-plus me-1"></i> Register Supplier
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>
        <?php if ($_GET['success'] === 'added') echo 'Supplier registered and verified successfully.'; ?>
        <?php if ($_GET['success'] === 'deleted') echo 'Supplier removed from the SCM register.'; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Search Bar -->
<div class="card border border-light shadow-sm bg-white rounded-4 p-3 mb-4">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search by CSD number or company name..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
        <button type="submit" class="btn btn-primary-custom px-4"><i class="fa-solid fa-magnifying-glass me-1"></i> Search</button>
        <?php if (!empty($search)): ?>
            <a href="<?php echo APP_URL; ?>/public/index.php/admin/scm" class="btn btn-outline-secondary px-4">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 border border-light shadow-sm bg-white rounded-4 text-center">
            <h3 class="mb-1 fw-bold text-primary"><?php echo $totalSuppliers; ?></h3>
            <small class="text-muted">Total Suppliers</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border border-light shadow-sm bg-white rounded-4 text-center">
            <h3 class="mb-1 fw-bold text-success"><?php echo $compliantCount; ?></h3>
            <small class="text-muted">Tax Compliant</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border border-light shadow-sm bg-white rounded-4 text-center">
            <h3 class="mb-1 fw-bold text-warning"><?php echo $nonCompliantCount; ?></h3>
            <small class="text-muted">Non-Compliant</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border border-light shadow-sm bg-white rounded-4 text-center">
            <h3 class="mb-1 fw-bold text-danger"><?php echo $restrictedCount; ?></h3>
            <small class="text-muted">Restricted / Blacklisted</small>
        </div>
    </div>
</div>

<!-- Supplier Ledger Table -->
<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <h5 class="fw-bold mb-4"><i class="fa-solid fa-table-list text-primary me-2"></i>Supplier CSD Register Ledger</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>CSD Supplier Number</th>
                    <th>Company Name</th>
                    <th>Tax Status</th>
                    <th>B-BBEE Level</th>
                    <th>Procurement Status</th>
                    <th>Last Verified</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($suppliers)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-building-columns fs-2 d-block mb-2 text-light"></i>
                            No suppliers found in the CSD register.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($suppliers as $s): ?>
                        <tr>
                            <td><code class="fw-bold"><?php echo $s['supplier_number']; ?></code></td>
                            <td>
                                <strong><?php echo $s['company_name']; ?></strong>
                                <?php if ($s['restricted']): ?>
                                    <span class="ms-2"><i class="fa-solid fa-ban text-danger" title="Blacklisted - Cannot Award Tender"></i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-1 <?php echo $s['tax_status'] === 'Compliant' ? 'csd-badge-compliant' : 'csd-badge-noncompliant'; ?>">
                                    <?php echo $s['tax_status'] === 'Compliant' ? '<i class="fa-solid fa-check me-1"></i>' : '<i class="fa-solid fa-xmark me-1"></i>'; ?>
                                    <?php echo $s['tax_status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bee-bar" style="width:60px;">
                                        <div class="bee-bar-fill" style="width:<?php echo ((9 - $s['bee_level']) / 8) * 100; ?>%"></div>
                                    </div>
                                    <small>Level <?php echo $s['bee_level']; ?></small>
                                </div>
                            </td>
                            <td>
                                <?php if ($s['restricted']): ?>
                                    <span class="badge csd-badge-restricted rounded-pill px-3 py-1"><i class="fa-solid fa-circle-minus me-1"></i>Restricted</span>
                                <?php elseif ($s['tax_status'] === 'Compliant'): ?>
                                    <span class="badge csd-badge-compliant rounded-pill px-3 py-1"><i class="fa-solid fa-circle-check me-1"></i>Eligible to Tender</span>
                                <?php else: ?>
                                    <span class="badge csd-badge-noncompliant rounded-pill px-3 py-1"><i class="fa-solid fa-circle-exclamation me-1"></i>Blocked</span>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?php echo date('d M Y H:i', strtotime($s['last_verified_at'])); ?></small></td>
                            <td class="text-end">
                                <form action="<?php echo APP_URL; ?>/public/index.php/admin/scm/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove this supplier from the CSD register?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="supplier_id" value="<?php echo $s['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-building-columns me-2"></i>Register New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo APP_URL; ?>/public/index.php/admin/scm/add" method="POST">
                <div class="modal-body px-4 pb-4">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">CSD Supplier Number</label>
                        <input type="text" name="supplier_number" class="form-control" placeholder="e.g. MAAA0182910" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. Vuka Infrastructure Solutions" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">SARS Tax Status</label>
                        <select name="tax_status" class="form-select">
                            <option value="Compliant">Tax Compliant</option>
                            <option value="Non-Compliant">Non-Compliant</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">B-BBEE Level (1=Best, 8=Lowest)</label>
                        <select name="bee_level" class="form-select">
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>">Level <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="restricted" id="restrictedCheck">
                        <label class="form-check-label text-danger fw-semibold" for="restrictedCheck">
                            <i class="fa-solid fa-ban me-1"></i> Mark as Restricted / Blacklisted
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom px-4"><i class="fa-solid fa-shield-check me-1"></i> Verify & Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
