// Mark notifications as read when bell is clicked
document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notificationBell');
    if(notificationBell) {
        notificationBell.addEventListener('click', function(e) {
            e.preventDefault();
            // AJAX call to mark all as read
            fetch('../mark_notifications_read.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Remove badge and mark notifications as read visually
                    const badge = document.querySelector('.badge');
                    if(badge) badge.remove();
                    document.querySelectorAll('.notif-item.unread').forEach(item => {
                        item.classList.remove('unread');
                    });
                }
            });
        });
    }

    // Confirmation for delete buttons
    const deleteBtns = document.querySelectorAll('.delete');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if(!confirm('Are you sure?')) {
                e.preventDefault();
            }
        });
    });
});

// Client-side form validation for registration
function validateRegisterForm() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    if(password.length < 6) {
        alert('Password must be at least 6 characters.');
        return false;
    }
    if(password !== confirm) {
        alert('Passwords do not match.');
        return false;
    }
    return true;
}