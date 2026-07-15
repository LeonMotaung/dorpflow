<?php
/**
 * DorpFlow ERP - Careers & Skills Database Upload View for Residents
 */
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        
        <!-- Notifications -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Application Form -->
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
            <div class="text-center mb-4 pb-3 border-bottom">
                <h3 class="mb-2 text-primary fw-bold"><i class="fa-solid fa-graduation-cap text-accent me-2"></i>Careers & Skills Database</h3>
                <p class="text-muted mb-0">Upload your CV and qualifications to be registered on the local municipal recruitment registry.</p>
            </div>

            <form action="<?php echo APP_URL; ?>/public/index.php/resident/careers/apply" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="appFullName">Full Name *</label>
                        <input type="text" name="full_name" id="appFullName" class="form-control" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="appIdNumber">National ID Number *</label>
                        <input type="text" name="id_number" id="appIdNumber" class="form-control" value="<?php echo htmlspecialchars($user['id_number'] ?? ''); ?>" placeholder="e.g. 9607155089083" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="appEmail">Email Address *</label>
                        <input type="email" name="email" id="appEmail" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="appPhone">Mobile Phone Number *</label>
                        <input type="tel" name="phone" id="appPhone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="p-3 border rounded-3 bg-light text-center h-100 d-flex flex-column justify-content-center align-items-center">
                            <label class="form-label fw-bold text-dark mb-2" for="appCv"><i class="fa-solid fa-file-pdf text-danger fs-3 d-block mb-2"></i> Upload Curriculum Vitae (CV) *</label>
                            <input type="file" name="cv" id="appCv" class="form-control form-control-sm" accept=".pdf, .doc, .docx" required>
                            <small class="text-muted d-block mt-2">Accepted formats: PDF or Word. Max size: 5MB.</small>
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="p-3 border rounded-3 bg-light text-center h-100 d-flex flex-column justify-content-center align-items-center">
                            <label class="form-label fw-bold text-dark mb-2" for="appQual"><i class="fa-solid fa-award text-success fs-3 d-block mb-2"></i> Upload Qualifications *</label>
                            <input type="file" name="qualifications" id="appQual" class="form-control form-control-sm" accept=".pdf, .doc, .docx" required>
                            <small class="text-muted d-block mt-2">Accepted formats: PDF or Word. Max size: 5MB.</small>
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary-custom w-100 py-3 fs-5" style="border-radius:12px;"><i class="fa-solid fa-cloud-arrow-up me-1"></i> Register Profile & Upload Documents</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
