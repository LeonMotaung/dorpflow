<?php
/**
 * DorpFlow ERP - Fleet Inventory View
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-truck-pickup me-2"></i>Municipal Response Fleet Register</h4>
                <p class="text-muted mb-0">Audit vehicle mileage, schedule services, and monitor active dispatches.</p>
            </div>
            <button class="btn btn-primary-custom py-3 px-4"><i class="fa-solid fa-plus-circle me-1"></i> Register Vehicle</button>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Registration No</th>
                    <th>Vehicle Model</th>
                    <th>Assigned Driver / Tech</th>
                    <th>Odometer mileage</th>
                    <th>Next Scheduled Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vehicles)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No fleet vehicles recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($vehicles as $v): ?>
                        <tr>
                            <td><strong><?php echo $v['vehicle_number']; ?></strong></td>
                            <td><?php echo $v['model']; ?></td>
                            <td>
                                <i class="fa-solid fa-user-circle me-1 text-muted"></i>
                                <?php echo $v['driver_name'] ?? '<span class="text-muted">Unassigned</span>'; ?>
                            </td>
                            <td><?php echo number_format($v['mileage']); ?> KM</td>
                            <td>
                                <i class="fa-regular fa-calendar-check me-1 text-muted"></i>
                                <?php echo date('d M Y', strtotime($v['next_service_date'])); ?>
                            </td>
                            <td>
                                <span class="badge <?php 
                                    echo $v['status'] === 'active' ? 'bg-success' : 'bg-warning text-dark';
                                ?>"><?php echo $v['status']; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
