<?php
/**
 * DorpFlow ERP - Create Department Form
 */
?>
<div class="row g-4 justify-content-center">
    <div class="col-lg-6">
        
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
            <h4 class="mb-2 text-primary"><i class="fa-solid fa-circle-plus me-2"></i>Provision New Department</h4>
            <p class="text-muted mb-4">Create a new municipal operations domain and assign its head manager.</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/public/index.php/departments/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="mb-3">
                    <label class="form-label" for="deptName">Department Name *</label>
                    <input type="text" name="name" class="form-control" id="deptName" placeholder="e.g. Roads & Infrastructure" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="deptMgr">Assign Department Head (Manager)</label>
                    <select name="manager_id" class="form-select" id="deptMgr">
                        <option value="">-- Leave Unassigned --</option>
                        <?php foreach ($managers as $m): ?>
                            <option value="<?php echo $m['id']; ?>"><?php echo $m['full_name']; ?> (<?php echo $m['email']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-muted d-block mt-1" style="font-size:0.75rem;">Only users with the role of <strong>Department Manager</strong> are eligible for selection.</span>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="deptBudget">Allocated Budget (ZAR) *</label>
                    <div class="input-group">
                        <span class="input-group-text">R</span>
                        <input type="number" step="0.01" name="budget" class="form-control" id="deptBudget" placeholder="e.g. 5000000.00" required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom flex-grow-1 py-3"><i class="fa-solid fa-save me-1"></i> Save Department</button>
                    <a href="<?php echo APP_URL; ?>/public/index.php/departments" class="btn btn-outline-secondary py-3 px-4" style="background:#F8FAFC; border-color:#E2E8F0;">Cancel</a>
                </div>
            </form>

        </div>

    </div>
</div>
