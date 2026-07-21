// Admin Instructor Profiles JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Handle image error fallbacks
    const profileImages = document.querySelectorAll('.instructor-profile-img');
    profileImages.forEach(img => {
        img.addEventListener('error', function() {
            if (this.dataset.fallbackSrc && this.src !== this.dataset.fallbackSrc) {
                this.src = this.dataset.fallbackSrc;
            }
        });
    });

    // Handle delete confirmations
    const deleteForms = document.querySelectorAll('.instructor-delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this instructor?')) {
                e.preventDefault();
                return false;
            }
        });
    });
});
