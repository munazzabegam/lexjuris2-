// Common function to handle alerts
function showAlert(message, type = 'success', duration = 3000) {
    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    const alert = $(`<div class="alert alert-${type} alert-dismissible fade show mt-3" role="alert">
        <i class="fas fa-${icon} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`);
    
    $('.d-flex.justify-content-between.align-items-center.mb-4').after(alert);
    
    if (duration > 0) {
        setTimeout(() => {
            alert.alert('close');
        }, duration);
    }
}

// Auto-dismiss existing alerts on page load
$(document).ready(function() {
    $('.alert').each(function() {
        const alert = $(this);
        setTimeout(() => {
            alert.alert('close');
        }, 3000);
    });
}); 