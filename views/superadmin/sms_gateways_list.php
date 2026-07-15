<?php
/**
 * DorpFlow ERP - SMS Gateway Logs View
 */
?>
<div class="row mb-4">
    <div class="col-12 col-lg-8">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100">
            <h4 class="mb-1 text-primary"><i class="fa-solid fa-mobile-screen me-2"></i>SMS Gateway Control Log</h4>
            <p class="text-muted mb-0">Track citizens notifications and alerts sent across the network via global SMS providers.</p>
        </div>
    </div>
    
    <!-- Profit Counter -->
    <div class="col-12 col-lg-4">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 h-100 d-flex justify-content-center">
            <small class="text-muted fw-bold" style="font-size:0.75rem;">GLOBAL MARGIN PROFIT</small>
            <h3 class="mb-0 text-success">R<?php 
                $profit = array_sum(array_column($logs, 'profit'));
                echo number_format($profit, 2);
            ?></h3>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <h5 class="fw-bold mb-4">Recent SMS Dispatches</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Municipality Node</th>
                    <th>Recipient Number</th>
                    <th>Message Snippet</th>
                    <th>Provider Used</th>
                    <th>License Cost</th>
                    <th>Profit Margin</th>
                    <th>Dispatch Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No SMS transactions recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td><strong><?php echo $l['municipality_name']; ?></strong></td>
                            <td><code><?php echo $l['recipient']; ?></code></td>
                            <td>
                                <small class="text-muted" style="display:inline-block; max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    <?php echo $l['message']; ?>
                                </small>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary"><?php echo $l['provider']; ?></span></td>
                            <td>R<?php echo number_format($l['cost'], 2); ?></td>
                            <td class="text-success fw-semibold">+R<?php echo number_format($l['profit'], 2); ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($l['created_at'])); ?></td>
                            <td><span class="badge bg-success text-white">delivered</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
