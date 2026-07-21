// Admin Questions JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Handle reject confirmations
    const rejectButtons = document.querySelectorAll('.question-reject-btn');
    rejectButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to reject this question?')) {
                e.preventDefault();
                return false;
            }
        });
    });
});
