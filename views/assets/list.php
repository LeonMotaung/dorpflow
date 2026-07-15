<?php
/**
 * DorpFlow ERP - Infrastructure Asset Register View
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-boxes-stacked me-2"></i>Infrastructure Asset Management</h4>
                <p class="text-muted mb-0">Monitor electrical grids, water lines, transformers, and municipal assets.</p>
            </div>
            <button class="btn btn-primary-custom py-3 px-4"><i class="fa-solid fa-plus-circle me-1"></i> Register New Asset</button>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>QR Identifier</th>
                    <th>Asset Description</th>
                    <th>Substance Type</th>
                    <th>Active Department</th>
                    <th>Maintenance Period</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($assets)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No infrastructure assets logged.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($assets as $a): ?>
                        <tr>
                            <td><code><?php echo $a['qr_code']; ?></code></td>
                            <td><strong><?php echo $a['name']; ?></strong></td>
                            <td><span class="badge bg-secondary-subtle text-secondary"><?php echo $a['type']; ?></span></td>
                            <td><?php echo $a['department_name']; ?></td>
                            <td><i class="fa-regular fa-clock me-1 text-muted"></i> <?php echo ucfirst($a['maintenance_schedule']); ?></td>
                            <td>
                                <span class="badge <?php 
                                    echo $a['status'] === 'operational' ? 'bg-success' : 'bg-danger';
                                ?>"><?php echo $a['status']; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
