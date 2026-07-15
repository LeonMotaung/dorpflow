<?php
/**
 * DorpFlow ERP - Employees List View
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-users me-2"></i>Staff & Usernames Directory</h4>
                <p class="text-muted mb-0">Register staff credentials, select structural roles, and assign departments.</p>
            </div>
            <a href="<?php echo APP_URL; ?>/public/index.php/employees/create" class="btn btn-primary-custom py-3 px-4"><i class="fa-solid fa-plus-circle me-1"></i> Register Employee</a>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Mobile Phone</th>
                    <th>Assigned Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($employees)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No staff employees registered yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($employees as $e): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size:0.8rem; font-weight:700;">
                                        <?php echo strtoupper(substr($e['full_name'], 0, 2)); ?>
                                    </div>
                                    <strong><?php echo $e['full_name']; ?></strong>
                                </div>
                            </td>
                            <td><code><?php echo $e['email']; ?></code></td>
                            <td><?php echo $e['phone']; ?></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary"><?php echo $e['role_name']; ?></span>
                            </td>
                            <td>
                                <span class="badge <?php 
                                    echo $e['is_locked'] ? 'bg-danger text-white' : 'bg-success text-white';
                                ?>"><?php echo $e['is_locked'] ? 'locked' : 'active'; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
