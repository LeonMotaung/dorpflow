<?php
/**
 * DorpFlow ERP - Resident Citizen Portal Hub
 */
$db = Database::getConnection();
$stmt = $db->prepare("
    SELECT t.*, d.name as department_name 
    FROM tickets t
    LEFT JOIN departments d ON d.id = t.department_id
    WHERE t.reporter_id = ?
    ORDER BY t.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$myTickets = $stmt->fetchAll();
?>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-house-user me-2"></i>My Citizen Hub</h4>
                <p class="text-muted mb-0">File requests, upload photo proof, and track municipal repair milestones.</p>
            </div>
            <a href="<?php echo APP_URL; ?>/public/index.php/tickets/create" class="btn btn-accent-custom py-3 px-4"><i class="fa-solid fa-circle-plus me-1"></i> File New Ticket</a>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <h5 class="mb-4 fw-bold">My Reported Faults History</h5>
    
    <?php if (empty($myTickets)): ?>
        <div class="text-center py-5">
            <i class="fa-regular fa-folder-open fs-1 text-muted mb-3 d-block"></i>
            <p class="text-muted">You have not logged any service delivery requests yet.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ticket Reference</th>
                        <th>Category</th>
                        <th>Assigned Department</th>
                        <th>Priority</th>
                        <th>Reported Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myTickets as $t): ?>
                        <tr>
                            <td><strong><?php echo $t['ticket_number']; ?></strong></td>
                            <td><?php echo $t['category']; ?></td>
                            <td><?php echo $t['department_name'] ?? '<span class="text-muted">Pending Assignment</span>'; ?></td>
                            <td>
                                <span class="badge <?php 
                                    echo $t['priority'] === 'Critical' ? 'bg-danger' : ($t['priority'] === 'High' ? 'bg-warning text-dark' : 'bg-secondary');
                                ?>"><?php echo $t['priority']; ?></span>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($t['created_at'])); ?></td>
                            <td>
                                <span class="badge <?php 
                                    echo $t['status'] === 'Completed' ? 'bg-success' : ($t['status'] === 'In Progress' ? 'bg-warning text-dark' : 'bg-primary');
                                ?>"><?php echo $t['status']; ?></span>
                            </td>
                            <td>
                                <a href="<?php echo APP_URL; ?>/public/index.php/tickets/view/<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye me-1"></i> Track Progress</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
