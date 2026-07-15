<?php
/**
 * DorpFlow ERP - Municipality Administrator Settings & Profile Page
 */
?>
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        
        <!-- Alerts -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Settings Card -->
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
            <h4 class="mb-2 text-primary fw-bold"><i class="fa-solid fa-sliders text-accent me-2"></i>Municipality Configuration</h4>
            <p class="text-muted mb-4">Manage municipal profile logos, system identities, and REST API integration credentials.</p>

            <form action="<?php echo APP_URL; ?>/public/index.php/admin/settings/update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <!-- Section 1: Logo & Profile Picture -->
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-regular fa-image text-primary me-2"></i>Platform Identity & Logo</h5>
                    <div class="d-flex align-items-center gap-4">
                        <div class="logo-preview-container bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 120px; height: 120px; overflow: hidden;">
                            <img src="<?php echo getMunicipalityLogo(); ?>" id="logoPreview" alt="Muni Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label fw-semibold">Upload New Logo (JPG, PNG, SVG)</label>
                            <input type="file" name="logo" id="logoUpload" class="form-control mb-3" accept=".png, .jpg, .jpeg, .svg" onchange="previewFile()">
                            <small class="text-muted d-block mb-3">Recommended resolution: 250x60px. Will display on citizen portals and console sidebar headers.</small>
                            
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="form-check form-switch d-flex justify-content-between align-items-center ps-0">
                                    <div>
                                        <label class="form-check-label fw-bold text-dark" for="blockOnboardingSwitch">Block Onboarding New Staff</label>
                                        <span class="d-block text-muted small">Enable to block/lock registering new staff or employee accounts on the platform.</span>
                                    </div>
                                    <input class="form-check-input ms-3" type="checkbox" name="block_onboarding" id="blockOnboardingSwitch" value="1" <?php echo $muni['block_onboarding'] ? 'checked' : ''; ?> style="width: 50px; height: 26px; cursor: pointer;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: API Keys and Secrets -->
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-key text-primary me-2"></i>REST API Gateway Integration</h5>
                    <p class="text-muted small">Exposes secure telemetry, smart billings, and CSD registries to verified system devices.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="apiKey">API Access Key</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-id-badge"></i></span>
                            <input type="text" name="api_key" id="apiKey" class="form-control" value="<?php echo htmlspecialchars($muni['api_key']); ?>" placeholder="df_key_..." required>
                            <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('apiKey')"><i class="fa-regular fa-copy"></i></button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="apiSecret">API Access Secret</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="api_secret" id="apiSecret" class="form-control" value="<?php echo htmlspecialchars($muni['api_secret']); ?>" placeholder="df_sec_..." required>
                            <button type="button" class="btn btn-outline-secondary" id="btnToggleSecret" onclick="toggleSecretVisibility()"><i class="fa-solid fa-eye" id="toggleSecretIcon"></i></button>
                            <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('apiSecret')"><i class="fa-regular fa-copy"></i></button>
                        </div>
                        <small class="text-danger d-block mt-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Never share this API secret key. Lock and store in secure environments.</small>
                    </div>
                </div>

                <!-- Form Submit -->
                <div class="pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="<?php echo APP_URL; ?>/public/index.php/dashboard" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px;">Cancel</a>
                    <button type="submit" class="btn btn-primary-custom px-5 py-2"><i class="fa-solid fa-floppy-disk me-1"></i> Save Configurations</button>
                </div>
            </form>

        </div>

    </div>
</div>

<script>
function previewFile() {
    const preview = document.getElementById('logoPreview');
    const file = document.getElementById('logoUpload').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

function toggleSecretVisibility() {
    const field = document.getElementById('apiSecret');
    const icon = document.getElementById('toggleSecretIcon');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function copyToClipboard(elementId) {
    const copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(copyText.value);
    
    alert("Copied API credential key to clipboard!");
}
</script>
