/**
 * DorpFlow ERP - Main Javascript Behaviors
 */

document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle hook
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    
    if (sidebar && content) {
        // Toggle action trigger (if burger menu is added)
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'btn btn-sm btn-outline-secondary d-lg-none mb-3';
        toggleBtn.innerHTML = '<i class="fa-solid fa-bars"></i> Menu';
        toggleBtn.style.position = 'fixed';
        toggleBtn.style.top = '15px';
        toggleBtn.style.left = '15px';
        toggleBtn.style.zIndex = '1000';
        
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            content.classList.toggle('active');
        });
        
        document.body.appendChild(toggleBtn);
    }

    // Auto-dismiss alert boxes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
