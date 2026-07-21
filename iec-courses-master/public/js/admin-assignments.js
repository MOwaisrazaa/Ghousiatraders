// Admin Assignments JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmations
    const deleteForms = document.querySelectorAll('.assignment-delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to remove this assignment?')) {
                e.preventDefault();
                return false;
            }
        });
    });
});
