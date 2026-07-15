<?php
/**
 * DorpFlow ERP - Create Employee Form
 */
?>
<div class="row g-4 justify-content-center">
    <div class="col-lg-6">
        
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
            <h4 class="mb-2 text-primary"><i class="fa-solid fa-circle-plus me-2"></i>Register New Staff Account</h4>
            <p class="text-muted mb-4">Add a new municipal staff account, set permissions, and define login credentials.</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/public/index.php/employees/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="mb-3">
                    <label class="form-label" for="empName">Full Name *</label>
                    <input type="text" name="full_name" class="form-control" id="empName" placeholder="e.g. Sipho Cele" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="empEmail">Official Email *</label>
                    <input type="email" name="email" class="form-control" id="empEmail" placeholder="e.g. s.cele@stellenbosch.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="empPhone">Mobile Phone Number *</label>
                    <input type="tel" name="phone" class="form-control" id="empPhone" placeholder="e.g. 0825559876" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="empIdNumber">National ID Number *</label>
                    <input type="text" name="id_number" class="form-control" id="empIdNumber" placeholder="e.g. 9607155089083" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="empRole">Assigned System Role *</label>
                    <select name="role_id" class="form-select" id="empRole" required>
                        <option value="" disabled selected>Select Role</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="empPass">Set Initial Password *</label>
                    <input type="password" name="password" class="form-control" id="empPass" placeholder="••••••••" required minlength="6">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-custom flex-grow-1 py-3"><i class="fa-solid fa-save me-1"></i> Register Employee</button>
                    <a href="<?php echo APP_URL; ?>/public/index.php/employees" class="btn btn-outline-secondary py-3 px-4" style="background:#F8FAFC; border-color:#E2E8F0;">Cancel</a>
                </div>
            </form>

        </div>

    </div>
</div>
