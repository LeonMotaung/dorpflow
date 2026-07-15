<?php
/**
 * DorpFlow ERP - Super Admin System Config View
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h4 class="mb-1 text-primary"><i class="fa-solid fa-sliders me-2"></i>SaaS Platform Configuration</h4>
            <p class="text-muted mb-0">Manage global system cost rates, CSD registration mappings, and network firewalls.</p>
        </div>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-server text-primary me-2"></i>Global Platform Settings</h5>
            
            <form action="#" method="POST" onsubmit="alert('Global system config saved.'); return false;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">CSD Vendor ID mapping</label>
                        <input type="text" class="form-control" value="MAAA0982319" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">VAT Rate (%)</label>
                        <input type="number" class="form-control" value="15" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Base SMS Cost (ZAR per dispatch)</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" step="0.01" class="form-control" value="0.25" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SMS Markup Profit Margin (ZAR)</label>
                        <div class="input-group">
                            <span class="input-group-text">R</span>
                            <input type="number" step="0.01" class="form-control" value="0.10" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Allowed Domains Network Firewall (comma-separated)</label>
                        <textarea class="form-control" rows="3">dorpflow.com, stellenbosch.com, tshwane.com</textarea>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary-custom w-100 py-3"><i class="fa-solid fa-save me-1"></i> Save Platform Configuration</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
