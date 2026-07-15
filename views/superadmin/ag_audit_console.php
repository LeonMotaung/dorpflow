<?php
/**
 * DorpFlow ERP - AG Audit Export Console View
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#0a2b4c,#1e4d78);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-file-shield text-white fs-5"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-primary fw-bold">Auditor-General System Audit Console</h4>
                    <p class="mb-0 text-muted small">Export platform-wide operational audit trails for all municipal nodes in CSV or PDF format.</p>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <a href="<?php echo APP_URL; ?>/public/index.php/superadmin/ag-audit/export-csv<?php echo isset($_GET['municipality_id']) ? '?municipality_id=' . (int)$_GET['municipality_id'] : ''; ?>" class="btn btn-outline-success btn-sm">
                        <i class="fa-solid fa-file-csv me-1"></i> Download CSV
                    </a>
                    <a href="<?php echo APP_URL; ?>/public/index.php/superadmin/ag-audit/export-pdf<?php echo isset($_GET['municipality_id']) ? '?municipality_id=' . (int)$_GET['municipality_id'] : ''; ?>" class="btn btn-primary-custom btn-sm" target="_blank">
                        <i class="fa-solid fa-file-pdf me-1"></i> Download PDF Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Municipality Filter -->
<div class="card border border-light shadow-sm bg-white rounded-4 p-3 mb-4">
    <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
        <label class="form-label mb-0 fw-semibold text-primary"><i class="fa-solid fa-filter me-1"></i> Filter by Municipality:</label>
        <select name="municipality_id" class="form-select" style="max-width:280px;" onchange="this.form.submit()">
            <option value="">All Municipalities</option>
            <?php foreach ($municipalities as $m): ?>
                <option value="<?php echo $m['id']; ?>" <?php echo (($_GET['municipality_id'] ?? '') == $m['id']) ? 'selected' : ''; ?>>
                    <?php echo $m['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($_GET['municipality_id'])): ?>
            <a href="<?php echo APP_URL; ?>/public/index.php/superadmin/ag-audit" class="btn btn-outline-secondary btn-sm">Clear Filter</a>
        <?php endif; ?>
        <span class="ms-auto text-muted small"><strong><?php echo count($logs); ?></strong> audit log entries</span>
    </form>
</div>

<!-- Audit Logs Table -->
<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <h5 class="fw-bold mb-4"><i class="fa-solid fa-scroll text-primary me-2"></i>System Audit Trail Ledger</h5>
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User Type</th>
                    <th>Action Description</th>
                    <th>Details / Context</th>
                    <th>IP Address</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-scroll fs-2 d-block mb-2 text-light"></i>
                            No audit logs recorded yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><code><?php echo $log['id']; ?></code></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($log['user_type'] ?? 'System'); ?></span>
                            </td>
                            <td style="max-width:350px;font-size:0.82rem;"><?php echo htmlspecialchars($log['action']); ?></td>
                            <td>
                                <span class="text-muted" style="font-size:0.82rem;"><?php echo htmlspecialchars($log['details'] ?? '—'); ?></span>
                            </td>
                            <td><code style="font-size:0.75rem;"><?php echo $log['ip_address'] ?? '—'; ?></code></td>
                            <td><small class="text-muted"><?php echo date('d M Y H:i:s', strtotime($log['created_at'])); ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
