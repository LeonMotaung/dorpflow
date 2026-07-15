<?php
/**
 * DorpFlow ERP - Super Admin Municipalities List & Onboarding View
 */
?>
<div class="row mb-4">
    <div class="col-12 col-lg-8">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h4 class="mb-1 text-primary"><i class="fa-solid fa-building-flag me-2"></i>Municipal Tenancy registry</h4>
            <p class="text-muted mb-0">Monitor active municipal nodes, check server storage quotas, and view security API keys.</p>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="col-12 col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100 d-flex justify-content-center">
            <small class="text-muted fw-bold" style="font-size:0.75rem;">GLOBAL SAAS TENANTS</small>
            <h3 class="mb-0 text-success"><?php echo count($municipalities); ?> Active Nodes</h3>
        </div>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> Municipality provisioned successfully! Database and credentials generated.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- LEFT COLUMN: PROVISION FORM -->
    <div class="col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-plus-circle me-1 text-primary"></i> Provision New Tenant</h5>
            <form action="<?php echo APP_URL; ?>/public/index.php/superadmin/municipalities/create" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Municipality Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Mafube Local Municipality" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subdomain Prefix *</label>
                    <div class="input-group">
                        <input type="text" name="subdomain" class="form-control text-lowercase" placeholder="mafube" required>
                        <span class="input-group-text">.dorpflow.gov.za</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subscription Tier *</label>
                    <select name="plan" class="form-select">
                        <option>Starter</option>
                        <option selected>Standard Enterprise</option>
                        <option>Mega Metro Tier</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Monthly Billing Price (ZAR) *</label>
                    <input type="number" name="price" class="form-control" value="15000.00" step="0.01" required>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3"><i class="fa-solid fa-server me-1"></i> Provision Database & Tenant</button>
            </form>
        </div>
    </div>

    <!-- RIGHT COLUMN: REGISTER LIST -->
    <div class="col-lg-8">
        <div class="card border border-light shadow-sm bg-white rounded-4 p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-list me-1 text-primary"></i> Active Tenant Node Log</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Municipality</th>
                            <th>Subdomain Prefix</th>
                            <th>API Credentials (X-DorpFlow-Key / Secret)</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($municipalities as $m): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $m['name']; ?></strong>
                                    <small class="d-block text-muted" style="font-size:0.75rem;">Database: <code><?php echo $m['db_name']; ?></code></small>
                                </td>
                                <td><span class="badge bg-secondary-subtle text-secondary"><?php echo $m['subdomain']; ?></span></td>
                                <td>
                                    <div class="bg-light p-2 rounded border" style="font-size:0.7rem;">
                                        <div class="mb-1 d-flex align-items-center justify-content-between">
                                            <span><strong>Key:</strong> <code><?php echo $m['api_key']; ?></code></span>
                                            <button class="btn btn-link btn-xs p-0 text-muted ms-2" onclick="copyText('<?php echo $m['api_key']; ?>', this)" title="Copy API Key"><i class="fa-regular fa-copy"></i></button>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span><strong>Secret:</strong> <code><?php echo substr($m['api_secret'], 0, 10); ?>...</code></span>
                                            <button class="btn btn-link btn-xs p-0 text-muted ms-2" onclick="copyText('<?php echo $m['api_secret']; ?>', this)" title="Copy API Secret"><i class="fa-regular fa-copy"></i></button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="d-block text-muted"><i class="fa-solid fa-mobile-screen me-1"></i> SMS: <?php echo $m['sms_balance']; ?></small>
                                    <small class="d-block text-muted"><i class="fa-regular fa-envelope me-1"></i> Mail: <?php echo $m['email_balance']; ?></small>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo APP_URL; ?>/public/index.php/superadmin/municipalities/invoice?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-primary" title="Generate Monthly Bill & Invoice" target="_blank">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="openDeleteModal(<?php echo $m['id']; ?>)" title="Delete Municipality">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Custom Flat-CSS Delete Confirmation Modal -->
<div class="df-modal-backdrop" id="deleteConfirmModal" style="display: none !important;">
    <div class="df-modal-card">
        <div class="df-modal-header text-danger">
            <h5 class="text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Confirm Tenant Deletion</h5>
            <button type="button" class="df-modal-close-btn" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="df-modal-body">
            <p class="mb-0 text-muted">Are you absolutely sure you want to delete this municipality? This will permanently <strong>DROP</strong> its database, clearing all citizen tickets, assets, and users!</p>
        </div>
        <div class="df-modal-footer">
            <button type="button" class="btn btn-sm btn-light border px-3" onclick="closeDeleteModal()">Cancel</button>
            <form id="deleteModalForm" action="<?php echo APP_URL; ?>/public/index.php/superadmin/municipalities/delete" method="POST" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="id" id="deleteMuniId" value="">
                <button type="submit" class="btn btn-sm btn-danger px-3">Drop Tenant Node</button>
            </form>
        </div>
    </div>
</div>

<style>
/* Embedded Flat CSS Modal Styles */
.df-modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(10, 43, 76, 0.6) !important;
    backdrop-filter: blur(6px) !important;
    -webkit-backdrop-filter: blur(6px) !important;
    z-index: 99999 !important;
    align-items: center !important;
    justify-content: center !important;
}

.df-modal-card {
    background: #ffffff !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 16px !important;
    max-width: 460px !important;
    width: 90% !important;
    box-shadow: 0 20px 50px rgba(10, 43, 76, 0.25) !important;
    overflow: hidden !important;
    animation: dfModalFadeIn 0.2s ease-out !important;
}

.df-modal-header {
    padding: 18px 24px !important;
    border-bottom: 1px solid #E2E8F0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}

.df-modal-header h5 {
    margin: 0 !important;
    font-family: 'Outfit', sans-serif !important;
    font-weight: 700 !important;
    font-size: 1.15rem !important;
}

.df-modal-close-btn {
    background: none !important;
    border: none !important;
    font-size: 1.5rem !important;
    color: #94A3B8 !important;
    cursor: pointer !important;
    line-height: 1 !important;
    padding: 0 !important;
}

.df-modal-close-btn:hover {
    color: #1E293B !important;
}

.df-modal-body {
    padding: 24px !important;
    font-size: 0.95rem !important;
    color: #475569 !important;
    line-height: 1.6 !important;
}

.df-modal-footer {
    padding: 16px 24px !important;
    background: #F8FAFC !important;
    border-top: 1px solid #E2E8F0 !important;
    display: flex !important;
    justify-content: flex-end !important;
    gap: 12px !important;
}

@keyframes dfModalFadeIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
</style>

<script>
function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        const icon = btn.querySelector('i');
        icon.className = 'fa-solid fa-check text-success';
        setTimeout(function() {
            icon.className = 'fa-regular fa-copy';
        }, 1500);
    });
}

function openDeleteModal(muniId) {
    document.getElementById('deleteMuniId').value = muniId;
    document.getElementById('deleteConfirmModal').style.setProperty('display', 'flex', 'important');
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').style.setProperty('display', 'none', 'important');
}
</script>
