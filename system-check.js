// system-check.js
function checkSystemStatus() {
    fetch('ping.php', {
        method: 'GET',
        cache: 'no-store'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('System down');
        }
        return response.text();
    })
    .then(data => {
        if (data !== 'OK') {
            throw new Error('System down');
        }
        // System is up
    })
    .catch(error => {
        // Show error message
        Swal.fire({
            title: 'System Unavailable',
            text: 'Our system is currently experiencing issues. Please try again later.',
            icon: 'error',
            confirmButtonText: 'Refresh',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    });
}

// Run this check when page loads
document.addEventListener('DOMContentLoaded', checkSystemStatus);

// Optionally check periodically (uncomment if needed)
// setInterval(checkSystemStatus, 60000); // Check every minute