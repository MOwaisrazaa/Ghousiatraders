// Welcome Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Handle image error fallbacks
    const screenshotImages = document.querySelectorAll('.screenshot-img');
    screenshotImages.forEach(img => {
        img.addEventListener('error', function() {
            const screenshotContainer = document.getElementById('screenshot-container');
            const docsCard = document.getElementById('docs-card');
            const docsCardContent = document.getElementById('docs-card-content');
            const background = document.getElementById('background');

            if (screenshotContainer) {
                screenshotContainer.classList.add('!hidden');
            }
            if (docsCard) {
                docsCard.classList.add('!row-span-1');
            }
            if (docsCardContent) {
                docsCardContent.classList.add('!flex-row');
            }
            if (background) {
                background.classList.add('!hidden');
            }
        });
    });
});
