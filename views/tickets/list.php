<?php
/**
 * DorpFlow ERP - Ticket List View (Control Room)
 */
?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 d-flex justify-content-between flex-row align-items-center">
            <div>
                <h4 class="mb-1 text-primary"><i class="fa-solid fa-ticket me-2"></i>Service Delivery Requests Register</h4>
                <p class="text-muted mb-0">Track, prioritize, and assign logged service faults to technicians.</p>
            </div>
            <a href="<?php echo APP_URL; ?>/public/index.php/tickets/create" class="btn btn-primary-custom py-3 px-4"><i class="fa-solid fa-plus-circle me-1"></i> Log New Ticket</a>
        </div>
    </div>
</div>

<div class="card border border-light shadow-sm bg-white rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Ref ID</th>
                    <th>Reporter Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Assigned Technician</th>
                    <th>Status</th>
                    <th>Logged Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tickets)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">No service tickets recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $t): ?>
                        <tr>
                            <td><strong><?php echo $t['ticket_number']; ?></strong></td>
                            <td><?php echo $t['reporter_name'] ?? '<span class="text-muted">Unauthenticated</span>'; ?></td>
                            <td><?php echo $t['category']; ?></td>
                            <td>
                                <small class="text-muted" style="display:inline-block; max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    <?php echo $t['description']; ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge <?php 
                                    echo $t['priority'] === 'Critical' ? 'bg-danger' : ($t['priority'] === 'High' ? 'bg-warning text-dark' : 'bg-secondary');
                                ?>"><?php echo $t['priority']; ?></span>
                            </td>
                            <td>
                                <i class="fa-solid fa-user-gear me-1 text-muted"></i>
                                <?php echo $t['technician_name'] ?? '<span class="text-danger">Unassigned</span>'; ?>
                            </td>
                            <td>
                                <span class="badge <?php 
                                    echo $t['status'] === 'Completed' ? 'bg-success' : ($t['status'] === 'In Progress' ? 'bg-warning text-dark' : 'bg-primary');
                                ?>"><?php echo $t['status']; ?></span>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($t['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo APP_URL; ?>/public/index.php/tickets/view/<?php echo $t['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-folder-open"></i> View File</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
