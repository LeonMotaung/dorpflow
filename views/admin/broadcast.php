<?php
/**
 * DorpFlow ERP - Municipality Broadcast Console View
 */
?>
<div class="row g-4">
    <!-- Notifications -->
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

    <!-- Header & Balance -->
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex flex-md-row justify-content-between align-items-md-center flex-column gap-3">
            <div>
                <h4 class="mb-1 text-primary fw-bold"><i class="fa-solid fa-bullhorn text-accent me-2"></i>Muni Communications Console</h4>
                <p class="text-muted mb-0">Broadcast instant SMS alerts, outage updates, and emergency notifications to municipal contacts.</p>
            </div>
            <div class="bg-primary-subtle text-primary p-3 rounded-4 border d-flex align-items-center gap-3">
                <div class="fs-2 text-accent"><i class="fa-solid fa-comments-dollar"></i></div>
                <div>
                    <small class="text-muted d-block fw-semibold uppercase tracking-wider" style="font-size: 0.75rem;">MUNI SMS CREDITS</small>
                    <span class="fs-4 fw-bold text-dark"><?php echo number_format($muni['sms_balance']); ?></span> <span class="small fw-semibold">credits</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recipients Metrics -->
    <div class="col-md-4">
        <div class="card border border-light bg-white rounded-4 p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-address-book text-primary me-2"></i>Recipient Directories</h5>
            
            <div class="d-flex flex-column gap-3 mb-4">
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                    <span class="fw-semibold text-muted"><i class="fa-solid fa-house-user me-2 text-primary"></i>Residents / Citizens</span>
                    <span class="badge bg-primary text-white fs-6"><?php echo $resident_count; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                    <span class="fw-semibold text-muted"><i class="fa-solid fa-users-gear me-2 text-primary"></i>Municipal Staff</span>
                    <span class="badge bg-primary text-white fs-6"><?php echo $staff_count; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                    <span class="fw-semibold text-muted"><i class="fa-solid fa-truck-pickup me-2 text-primary"></i>Field Technicians</span>
                    <span class="badge bg-primary text-white fs-6"><?php echo $tech_count; ?></span>
                </div>
            </div>

            <div class="alert alert-info border-0 shadow-sm rounded-3 py-3 mb-0 small">
                <i class="fa-solid fa-info-circle me-1"></i> Numbers are dynamically resolved from user profile details saved inside the local tenant database register.
            </div>
        </div>
    </div>

    <!-- Message Composer -->
    <div class="col-md-8">
        <div class="card border border-light bg-white rounded-4 p-4 shadow-sm">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-pen-to-square text-accent me-2"></i>Compose Alert Broadcast</h5>
            
            <form action="<?php echo APP_URL; ?>/public/index.php/admin/broadcast/send" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="recipientSelect">Target Directory *</label>
                        <select name="recipient_type" class="form-select" id="recipientSelect" onchange="toggleCustomInput()" required>
                            <option value="all" selected>All Registered Residents (<?php echo $resident_count; ?> recipients)</option>
                            <option value="staff">All Municipal Staff (<?php echo $staff_count; ?> recipients)</option>
                            <option value="technicians">Active Field Technicians (<?php echo $tech_count; ?> recipients)</option>
                            <option value="custom">Single Custom Recipient</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="providerSelect">Delivery Channel *</label>
                        <select name="provider" class="form-select" id="providerSelect" required>
                            <option value="DorpFlow-SMS" selected>SMS Gateway (Cost: 1 credit/recipient)</option>
                            <option value="WhatsApp-API">WhatsApp Gateway (Cost: 1 credit/recipient)</option>
                        </select>
                    </div>

                    <!-- Custom Number Input (Hidden by default) -->
                    <div class="col-12 d-none" id="customNumberContainer">
                        <label class="form-label fw-semibold" for="customNum">Custom Mobile Number *</label>
                        <input type="tel" name="custom_number" class="form-control" id="customNum" placeholder="e.g. 0825551234">
                        <small class="text-muted">Include country code if writing international numbers.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold" for="msgText">Message Body *</label>
                        <textarea name="message_text" class="form-control" id="msgText" rows="4" maxlength="320" placeholder="Type your municipal broadcast here (e.g. Outage notifications, water restrictions, loadshedding changes...)" onkeyup="updateCharCount()" required></textarea>
                        <div class="d-flex justify-content-between mt-1 text-muted small">
                            <span>Avoid sending private personal information (POPIA regulations).</span>
                            <span id="charCountLabel">0 / 320 characters</span>
                        </div>
                    </div>

                    <div class="col-12 pt-2 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary-custom px-5 py-3" style="border-radius: 12px;">
                            <i class="fa-solid fa-paper-plane me-1"></i> Dispatch Broadcast Alert
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Past Transmission Logs -->
    <div class="col-12">
        <div class="card border border-light bg-white rounded-4 p-4 shadow-sm">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Recent Transmission Logs</h5>
            
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th>Recipient Number</th>
                            <th>Gateway Channel</th>
                            <th>Message Content</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Dispatched At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($past_logs)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No broadcasts dispatched yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($past_logs as $log): ?>
                                <tr>
                                    <td><code class="text-dark fw-semibold"><?php echo htmlspecialchars($log['recipient']); ?></code></td>
                                    <td>
                                        <span class="badge <?php 
                                            echo $log['provider'] === 'WhatsApp-API' ? 'bg-success text-white' : 'bg-primary text-white';
                                        ?>">
                                            <?php echo $log['provider']; ?>
                                        </span>
                                    </td>
                                    <td><span class="text-muted d-inline-block text-truncate" style="max-width: 400px;"><?php echo htmlspecialchars($log['message']); ?></span></td>
                                    <td><code class="text-dark fw-bold">1 credit</code></td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-check-double me-1"></i> Sent</span>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('Y-m-d H:i:s', strtotime($log['created_at'])); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomInput() {
    const select = document.getElementById('recipientSelect');
    const container = document.getElementById('customNumberContainer');
    const customNum = document.getElementById('customNum');
    
    if (select.value === 'custom') {
        container.classList.remove('d-none');
        customNum.setAttribute('required', 'true');
    } else {
        container.classList.add('d-none');
        customNum.removeAttribute('required');
    }
}

function updateCharCount() {
    const area = document.getElementById('msgText');
    const label = document.getElementById('charCountLabel');
    label.innerText = area.value.length + " / 320 characters";
}
</script>
