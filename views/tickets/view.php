<?php
/**
 * DorpFlow ERP - Ticket Detail, Timeline, Comments, and Dispatch panel
 */
$user = Auth::user();
?>
<div class="row g-4 mb-4">
    <!-- LEFT PANEL: TICKET DETAILS, COMMENTS, AND MAP -->
    <div class="col-lg-8">
        
        <!-- Details Card -->
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 text-primary">Ticket <?php echo $ticket['ticket_number']; ?></h4>
                <span class="badge bg-primary px-3 py-2" style="font-size:0.85rem;"><?php echo $ticket['status']; ?></span>
            </div>
            
            <div class="row g-3" style="font-size:0.9rem;">
                <div class="col-6 col-md-3">
                    <span class="text-muted d-block">CATEGORY</span>
                    <strong><?php echo $ticket['category']; ?></strong>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted d-block">PRIORITY</span>
                    <span class="badge bg-danger"><?php echo $ticket['priority']; ?></span>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted d-block">WARD MAPPING</span>
                    <strong>Ward <?php echo $ticket['ward_number'] ?? 'Not Mapped'; ?></strong>
                </div>
                <div class="col-6 col-md-3">
                    <span class="text-muted d-block">COUNCILOR</span>
                    <strong><?php echo $ticket['councilor_name'] ?? 'Not Mapped'; ?></strong>
                </div>
                <div class="col-12 mt-4 border-top pt-3">
                    <span class="text-muted d-block mb-1">FAULT DESCRIPTION</span>
                    <p class="mb-0 text-dark" style="line-height:1.6;"><?php echo $ticket['description']; ?></p>
                </div>
            </div>
        </div>

        <!-- GIS GPS map coordinates -->
        <?php if ($ticket['lat'] && $ticket['lng']): ?>
            <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
                <h5 class="mb-3 fw-bold"><i class="fa-solid fa-map-pin text-danger me-2"></i>GIS Coordinates Map</h5>
                <div id="ticketMap" style="height: 250px; border-radius:10px; border:1px solid var(--border-color);"></div>
            </div>
        <?php endif; ?>

        <!-- Ticket Comments segment -->
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h5 class="mb-3 fw-bold"><i class="fa-regular fa-comments text-accent me-2"></i>Communication Board</h5>
            
            <form action="<?php echo APP_URL; ?>/public/index.php/tickets/comment" method="POST" class="mb-4">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                
                <div class="mb-3">
                    <textarea class="form-control" name="message" rows="3" placeholder="Post a status update or internal note..." required></textarea>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <?php if (in_array($user['role'], ['Municipality Administrator', 'Department Manager', 'Supervisor', 'Technician'])): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_internal" id="internalCheck" value="1">
                            <label class="form-check-label text-danger" style="font-size:0.8rem; font-weight:600;" for="internalCheck">
                                <i class="fa-solid fa-lock me-1"></i> Flag as Internal Note (Staff Only)
                            </label>
                        </div>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-sm btn-primary-custom px-4"><i class="fa-solid fa-paper-plane me-1"></i> Post</button>
                </div>
            </form>

            <div class="d-flex flex-column gap-3">
                <?php foreach ($comments as $c): ?>
                    <div class="p-3 rounded-3 <?php echo $c['is_internal'] ? 'bg-danger-subtle border border-danger-subtle' : 'bg-light'; ?>">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong style="font-size: 0.85rem;" class="text-dark">
                                <?php echo $c['full_name']; ?> 
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;"><?php echo $c['role_name']; ?></span>
                            </strong>
                            <small class="text-muted" style="font-size:0.7rem;"><?php echo date('d M H:i', strtotime($c['created_at'])); ?></small>
                        </div>
                        <p class="mb-0 text-muted" style="font-size:0.85rem;"><?php echo $c['message']; ?></p>
                        <?php if ($c['is_internal']): ?>
                            <span class="badge bg-danger text-white mt-2" style="font-size:0.6rem;"><i class="fa-solid fa-lock me-1"></i> Internal Staff Record</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- RIGHT PANEL: DISPATCH, ASSIGNMENT, AND HISTORY -->
    <div class="col-lg-4">
        
        <!-- Dispatch Box (Supervisor/Manager Only) -->
        <?php if (in_array($user['role'], ['Municipality Administrator', 'Department Manager', 'Supervisor'])): ?>
            <div class="card p-4 border border-light shadow-sm bg-white rounded-4 mb-4">
                <h5 class="mb-3 fw-bold"><i class="fa-solid fa-truck-pickup me-2 text-primary"></i>Technician Dispatch</h5>
                <form action="<?php echo APP_URL; ?>/public/index.php/tickets/assign" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Select Technician</label>
                        <select name="technician_id" class="form-select">
                            <option value="">-- Unassigned --</option>
                            <?php foreach ($technicians as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo $ticket['technician_id'] == $t['id'] ? 'selected' : ''; ?>>
                                    <?php echo $t['full_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Workflow Status</label>
                        <select name="status" class="form-select">
                            <option value="Pending Review" <?php echo $ticket['status'] === 'Pending Review' ? 'selected' : ''; ?>>Pending Review</option>
                            <option value="Assigned" <?php echo $ticket['status'] === 'Assigned' ? 'selected' : ''; ?>>Assigned</option>
                            <option value="In Progress" <?php echo $ticket['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo $ticket['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3"><i class="fa-solid fa-truck-ramp-box me-1"></i> Update Work Order</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- History Timeline -->
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <h5 class="mb-4 fw-bold"><i class="fa-solid fa-clock-rotate-left text-muted me-2"></i>Action History Timeline</h5>
            <ul class="list-unstyled mb-0" style="position:relative; padding-left: 20px;">
                <div style="position:absolute; top:5px; bottom:5px; left:5px; width:2px; background:var(--border-color);"></div>
                <?php foreach ($timeline as $th): ?>
                    <li class="mb-4" style="position:relative;">
                        <div style="position:absolute; left:-20px; top:4px; width:12px; height:12px; border-radius:50%; background:var(--accent); border:2px solid #fff;"></div>
                        <span class="text-muted d-block" style="font-size:0.7rem;"><?php echo date('d M Y H:i', strtotime($th['created_at'])); ?></span>
                        <strong style="font-size:0.8rem;" class="text-dark d-block"><?php echo $th['action']; ?></strong>
                        <small class="text-muted">By: <?php echo $th['full_name']; ?> (<?php echo $th['role_name']; ?>)</small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</div>

<?php if ($ticket['lat'] && $ticket['lng']): ?>
<script>
    window.addEventListener('load', function() {
        const ticketLat = <?php echo $ticket['lat']; ?>;
        const ticketLng = <?php echo $ticket['lng']; ?>;
        const tMap = L.map('ticketMap').setView([ticketLat, ticketLng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(tMap);

        L.marker([ticketLat, ticketLng]).addTo(tMap)
            .bindPopup("<strong><?php echo $ticket['ticket_number']; ?></strong><br>Location coordinates mapped.")
            .openPopup();
    });
</script>
<?php endif; ?>
