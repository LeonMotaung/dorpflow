<?php
/**
 * DorpFlow ERP - WhatsApp Fault-Reporting Bot Simulator
 */
?>
<style>
.wa-chat-container {
    max-width: 600px;
    margin: 0 auto;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    font-family: 'Inter', sans-serif;
}
.wa-header {
    background: linear-gradient(135deg, #075e54, #128c7e);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
}
.wa-header .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
}
.wa-body {
    background: #ece5dd url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9b99a' fill-opacity='0.18'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    padding: 20px;
    min-height: 350px;
    max-height: 350px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.wa-msg {
    display: flex;
    gap: 8px;
}
.wa-msg.from-user { justify-content: flex-end; }
.wa-bubble {
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 0.875rem;
    line-height: 1.5;
    position: relative;
    box-shadow: 0 1px 2px rgba(0,0,0,0.15);
}
.wa-bubble.bot {
    background: white;
    border-top-left-radius: 4px;
}
.wa-bubble.user {
    background: #dcf8c6;
    border-top-right-radius: 4px;
    text-align: right;
}
.wa-bubble .wa-time {
    font-size: 0.65rem;
    color: #999;
    margin-top: 4px;
}
.wa-footer {
    background: #f0f0f0;
    padding: 12px 16px;
    display: flex;
    gap: 10px;
    align-items: center;
}
.wa-input {
    flex: 1;
    border: none;
    background: white;
    padding: 10px 16px;
    border-radius: 24px;
    font-size: 0.875rem;
    outline: none;
}
.wa-send-btn {
    background: #075e54;
    border: none;
    color: white;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.wa-send-btn:hover { background: #128c7e; }
.ticket-preview {
    background: rgba(7, 94, 84, 0.08);
    border-left: 4px solid #075e54;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 0.8rem;
    margin-top: 8px;
}
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="card p-4 border border-light shadow-sm bg-white rounded-4">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#25d366,#075e54);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-brands fa-whatsapp text-white fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 text-primary fw-bold">WhatsApp Fault-Reporting Bot Simulator</h4>
                    <p class="mb-0 text-muted small">Simulate citizen messages reporting municipal faults. The bot parses them and auto-generates tickets.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: CHAT SIMULATOR -->
    <div class="col-lg-5">
        <div class="wa-chat-container">
            <div class="wa-header">
                <div class="avatar"><i class="fa-solid fa-robot"></i></div>
                <div>
                    <div class="fw-bold" style="font-size:0.95rem;">DorpFlow Municipal Bot</div>
                    <div style="font-size:0.75rem;opacity:0.8;">Online · Fault Reporting Service</div>
                </div>
            </div>

            <div class="wa-body" id="wa-chat-body">
                <div class="wa-msg">
                    <div class="wa-bubble bot">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> <strong>Welcome to DorpFlow Municipal Services!</strong><br><br>
                        Send your fault report below. Include:<br>
                        • Type (Water / Electricity / Roads)<br>
                        • Location (street/area)<br>
                        • Brief description<br><br>
                        <em>Example: "Water leak on Church St near Park, flooding the pavement."</em>
                        <div class="wa-time">10:02 AM ✓✓</div>
                    </div>
                </div>

                <?php if (!empty($conversation)): ?>
                    <?php foreach ($conversation as $msg): ?>
                        <div class="wa-msg <?php echo $msg['side']; ?>">
                            <div class="wa-bubble <?php echo ($msg['side'] === 'from-user') ? 'user' : 'bot'; ?>">
                                <?php echo nl2br(htmlspecialchars($msg['text'])); ?>
                                <div class="wa-time"><?php echo $msg['time']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="wa-footer">
                <form action="<?php echo APP_URL; ?>/public/index.php/admin/whatsapp-bot" method="POST" style="display:contents;" id="wa-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="text" name="citizen_message" class="wa-input" id="wa-input" placeholder="Report a municipal fault..." autocomplete="off" required>
                    <button type="submit" class="wa-send-btn"><i class="fa-solid fa-paper-plane" style="font-size:0.85rem;"></i></button>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="<?php echo APP_URL; ?>/public/index.php/admin/whatsapp-bot/reset" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-rotate-left me-1"></i> Reset Conversation
            </a>
        </div>
    </div>

    <!-- RIGHT: GENERATED TICKETS LEDGER -->
    <div class="col-lg-7">
        <div class="card border border-light shadow-sm bg-white rounded-4 p-4 h-100">
            <h5 class="fw-bold mb-4">
                <i class="fa-solid fa-ticket text-primary me-2"></i>Bot-Generated Tickets
                <span class="badge bg-success ms-2"><?php echo count($botTickets); ?> created this session</span>
            </h5>
            <?php if (empty($botTickets)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fa-brands fa-whatsapp fs-1 mb-3 d-block" style="color:#25d366;"></i>
                    <p>Send a fault report in the chat simulator to auto-generate operational tickets here.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket #</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($botTickets) as $bt): ?>
                                <tr>
                                    <td><code><?php echo $bt['ticket_number']; ?></code></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary"><?php echo $bt['category']; ?></span>
                                    </td>
                                    <td style="max-width:200px;font-size:0.82rem;"><?php echo $bt['description']; ?></td>
                                    <td><span class="badge bg-warning text-dark">Pending Review</span></td>
                                    <td><small class="text-muted"><?php echo date('H:i:s', strtotime($bt['created_at'])); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Auto-scroll chat to bottom
document.addEventListener('DOMContentLoaded', function() {
    const body = document.getElementById('wa-chat-body');
    if (body) body.scrollTop = body.scrollHeight;
    const input = document.getElementById('wa-input');
    if (input) input.focus();
});
</script>
