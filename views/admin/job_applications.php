<?php
/**
 * DorpFlow ERP - Admin Job Applications Directory View
 */
?>
<div class="row g-4">
    <!-- Alert Notifications -->
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

    <!-- Header Card -->
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h4 class="mb-1 text-primary fw-bold"><i class="fa-solid fa-file-invoice text-accent me-2"></i>Citizen CV & Qualifications Database</h4>
            <p class="text-muted mb-0">Search through citizen CV profiles and onboard candidates directly into the municipal staff directory.</p>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <div class="table-responsive">
                <table class="table align-middle border-light">
                    <thead class="table-light text-muted small" style="font-weight: 600;">
                        <tr>
                            <th>Applicant details</th>
                            <th>National ID</th>
                            <th>CV File</th>
                            <th>Qualifications</th>
                            <th>Applied Date</th>
                            <th>Status</th>
                            <th style="width: 320px; text-align: center;">Onboarding Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No citizen skills profiles uploaded to the database yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td>
                                        <strong class="text-dark d-block"><?php echo htmlspecialchars($app['full_name']); ?></strong>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($app['email']); ?></small>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($app['phone']); ?></small>
                                    </td>
                                    <td><code class="text-dark fw-semibold"><?php echo htmlspecialchars($app['id_number']); ?></code></td>
                                    <td>
                                        <?php if ($app['cv_path']): ?>
                                            <a href="<?php echo APP_URL . $app['cv_path']; ?>" target="_blank" class="btn btn-sm btn-outline-danger" style="border-radius:6px;">
                                                <i class="fa-solid fa-file-pdf me-1"></i> Download CV
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">No file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($app['qualifications_path']): ?>
                                            <a href="<?php echo APP_URL . $app['qualifications_path']; ?>" target="_blank" class="btn btn-sm btn-outline-success" style="border-radius:6px;">
                                                <i class="fa-solid fa-award me-1"></i> Download File
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">No file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($app['created_at'])); ?></small></td>
                                    <td>
                                        <?php if ($app['status'] === 'Onboarded'): ?>
                                            <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-user-check me-1"></i> Onboarded</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning"><i class="fa-regular fa-clock me-1"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="bg-light-subtle">
                                        <?php if ($app['status'] === 'Pending'): ?>
                                            <form action="<?php echo APP_URL; ?>/public/index.php/admin/hr-payroll/applications/onboard" method="POST" class="d-flex align-items-center gap-2 justify-content-center">
                                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                                <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                                
                                                <select name="role_id" class="form-select form-select-sm" style="width: 150px;" required>
                                                    <option value="" disabled selected>Assign Role</option>
                                                    <?php foreach ($roles as $r): ?>
                                                        <option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                
                                                <div class="input-group input-group-sm" style="width: 100px;">
                                                    <span class="input-group-text">R</span>
                                                    <input type="number" step="1" name="salary" class="form-control" placeholder="Salary" value="15000">
                                                </div>
                                                
                                                <button type="submit" class="btn btn-sm btn-accent-custom"><i class="fa-solid fa-plus"></i> Hire</button>
                                            </form>
                                        <?php else: ?>
                                            <div class="text-center text-muted small"><i class="fa-solid fa-lock me-1"></i> Hired & Onboarded</div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
