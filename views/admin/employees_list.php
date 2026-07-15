<?php
/**
 * DorpFlow ERP - Employees List View
 */
$coreDb = Database::getCoreConnection();
$subdomain = getActiveTenant();
$stmtBlock = $coreDb->prepare("SELECT block_onboarding FROM municipalities WHERE subdomain = ? LIMIT 1");
$stmtBlock->execute([$subdomain]);
$blockOnboarding = $stmtBlock->fetchColumn() ?: 0;
?>
<div class="row mb-4">
    <div class="col-12">
        <!-- Error Alerts -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-3" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($blockOnboarding): ?>
            <div class="alert alert-warning border-0 shadow-sm rounded-3 py-3 mb-3 d-flex align-items-center justify-content-between" style="background-color: rgba(255, 193, 7, 0.15); color: #856404;">
                <div>
                    <i class="fa-solid fa-ban me-2 text-danger"></i> <strong>Onboarding Blocked:</strong> Adding new employee accounts is currently locked by the municipality system configurations.
                </div>
                <a href="<?php echo APP_URL; ?>/public/index.php/admin/settings" class="btn btn-sm btn-outline-warning text-dark fw-bold"><i class="fa-solid fa-sliders me-1"></i> Configure Settings</a>
            </div>
        <?php endif; ?>

        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-users me-2"></i>Staff & Usernames Directory</h4>
                <p class="text-muted mb-0">Register staff credentials, select structural roles, and assign departments.</p>
            </div>
            <?php if (!$blockOnboarding): ?>
                <a href="<?php echo APP_URL; ?>/public/index.php/employees/create" class="btn btn-primary-custom py-3 px-4"><i class="fa-solid fa-plus-circle me-1"></i> Register Employee</a>
            <?php else: ?>
                <button class="btn btn-secondary py-3 px-4" disabled><i class="fa-solid fa-lock me-1"></i> Onboarding Blocked</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Full Name</th>
                    <th>National ID Number</th>
                    <th>Email Address</th>
                    <th>Mobile Phone</th>
                    <th>Assigned Role</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th style="width: 100px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($employees)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No staff employees registered yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($employees as $e): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size:0.85rem;">
                                        <?php echo strtoupper(substr($e['full_name'], 0, 2)); ?>
                                    </div>
                                    <strong><?php echo htmlspecialchars($e['full_name']); ?></strong>
                                </div>
                            </td>
                            <td><code class="text-dark fw-semibold"><?php echo htmlspecialchars($e['id_number'] ?: 'Not Captured'); ?></code></td>
                            <td><code><?php echo htmlspecialchars($e['email']); ?></code></td>
                            <td><?php echo htmlspecialchars($e['phone']); ?></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary"><?php echo $e['role_name']; ?></span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info fw-semibold"><?php echo htmlspecialchars($e['department_name'] ?: 'General / Unassigned'); ?></span>
                            </td>
                            <td>
                                <span class="badge <?php 
                                    echo $e['is_locked'] ? 'bg-danger text-white' : 'bg-success text-white';
                                ?>"><?php echo $e['is_locked'] ? 'suspended' : 'active'; ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo APP_URL; ?>/public/index.php/employees/edit/<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:6px;"><i class="fa-solid fa-user-gear"></i> Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
