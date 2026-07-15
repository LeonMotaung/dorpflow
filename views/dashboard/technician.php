<?php
/**
 * DorpFlow ERP - Field Technician Dashboard
 */
$db = Database::getConnection();
$stmt = $db->prepare("
    SELECT t.*, w.ward_number 
    FROM tickets t
    LEFT JOIN Wards w ON w.id = t.ward_id
    WHERE t.technician_id = ? AND t.status IN ('Assigned', 'In Progress')
    ORDER BY t.priority DESC, t.created_at ASC
");
$stmt->execute([$_SESSION['user_id']]);
$jobs = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h4 class="mb-1 text-primary"><i class="fa-solid fa-list-check me-2"></i>Today's Assigned Jobs</h4>
            <p class="text-muted mb-0">Follow dispatches, update work orders, and upload completion photo reports.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($jobs)): ?>
        <div class="col-12">
            <div class="alert alert-success text-center py-4 rounded-3" role="alert">
                <i class="fa-solid fa-circle-check fs-2 mb-2 d-block"></i>
                No active dispatches. All assigned tasks resolved successfully!
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($jobs as $j): ?>
            <div class="col-md-6">
                <div class="card border border-light shadow-sm rounded-3 overflow-hidden bg-white">
                    <div class="card-header border-0 py-3 bg-light d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary text-white"><?php echo $j['ticket_number']; ?></span>
                        <span class="badge bg-danger"><?php echo $j['priority']; ?></span>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold text-dark"><?php echo $j['category']; ?> Repair</h5>
                        <p class="card-text text-muted mb-3" style="font-size:0.9rem; line-height: 1.6;"><?php echo $j['description']; ?></p>
                        
                        <div class="d-flex align-items-center gap-3 mb-4" style="font-size:0.8rem; font-weight:600;">
                            <span class="text-secondary"><i class="fa-solid fa-location-dot me-1"></i> Ward <?php echo $j['ward_number']; ?></span>
                            <span class="text-muted"><i class="fa-solid fa-clock me-1"></i> Logged: <?php echo date('d M H:i', strtotime($j['created_at'])); ?></span>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="<?php echo APP_URL; ?>/public/index.php/tickets/view/<?php echo $j['id']; ?>" class="btn btn-sm btn-primary-custom flex-grow-1"><i class="fa-regular fa-folder-open me-1"></i> Open Task File</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
