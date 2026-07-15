<?php
/**
 * DorpFlow ERP - Edit Employee Form
 */
?>
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
            <h4 class="mb-2 text-primary fw-bold"><i class="fa-solid fa-user-pen text-accent me-2"></i>Edit Employee Profile</h4>
            <p class="text-muted mb-4">Update employee credentials, ID details, base salaries, and administrative console lock status.</p>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/public/index.php/employees/edit" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="empName">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" id="empName" value="<?php echo htmlspecialchars($employee['full_name']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="empIdNum">National ID Number *</label>
                        <input type="text" name="id_number" class="form-control" id="empIdNum" value="<?php echo htmlspecialchars($employee['id_number']); ?>" placeholder="e.g. 9607155089083" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="empEmail">Official Email *</label>
                        <input type="email" name="email" class="form-control" id="empEmail" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="empPhone">Mobile Phone Number *</label>
                        <input type="tel" name="phone" class="form-control" id="empPhone" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="empRole">Assigned Console Role *</label>
                        <select class="form-select" name="role_id" id="empRole" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?php echo $r['id']; ?>" <?php echo $employee['role_id'] == $r['id'] ? 'selected' : ''; ?>><?php echo $r['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="empDept">Assigned Department</label>
                        <select class="form-select" name="department_id" id="empDept">
                            <option value="">-- No Department / Unassigned --</option>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?php echo $d['id']; ?>" <?php echo $employee['department_id'] == $d['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="empSalary">Base Salary (ZAR)</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" step="0.01" min="0" name="salary" class="form-control fw-semibold text-end" id="empSalary" value="<?php echo number_format($employee['salary'], 2, '.', ''); ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold" for="empPass">Password (Leave blank to keep unchanged)</label>
                        <input type="password" name="password" class="form-control" id="empPass" placeholder="••••••••">
                    </div>

                    <!-- Account Lock Status Toggle -->
                    <div class="col-12 mt-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                                <div>
                                    <label class="form-check-label fw-bold text-dark" for="lockSwitch">Block/Suspend Staff Account</label>
                                    <span class="d-block text-muted small">Suspended employees are blocked from logging into the console or completing jobs.</span>
                                </div>
                                <input class="form-check-input ms-3" type="checkbox" name="is_locked" id="lockSwitch" value="1" <?php echo $employee['is_locked'] ? 'checked' : ''; ?> style="width: 50px; height: 26px; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="<?php echo APP_URL; ?>/public/index.php/employees" class="btn btn-outline-secondary px-4 py-2" style="border-radius:10px;">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-5 py-2"><i class="fa-solid fa-save me-1"></i> Save Employee Updates</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>
