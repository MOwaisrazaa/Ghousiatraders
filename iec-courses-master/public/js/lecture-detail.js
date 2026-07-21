// Lecture Detail Page JavaScript Functions

// Global variables
let currentLectureId = null;
let currentCourseId = null;
let attachments = {
    images: [],
    pdfs: [],
    voice: null
};
let mediaRecorder = null;
let audioChunks = [];

// Initialize lecture detail functionality
function initializeLectureDetail() {
    // Initialize video protection
    initializeVideoProtection();

    // Initialize star rating
    initializeStarRating();

    // Initialize attachment handling
    initializeAttachmentHandling();

    // Initialize progress tracking
    initializeProgressTracking();

    // Initialize security features
    initializeSecurityFeatures();
}

// Initialize video protection
function initializeVideoProtection() {
    // Add meta tag to disable Game Bar
    const gameBarMetaTag = document.createElement('meta');
    gameBarMetaTag.name = 'xbox-game-bar-allowed';
    gameBarMetaTag.content = 'false';
    document.head.appendChild(gameBarMetaTag);

    // Apply video protection to all video containers
    const videoContainers = document.querySelectorAll('.ratio-16x9, .video-container');
    videoContainers.forEach(container => {
        container.classList.add('video-protected');

        // Disable right-click
        container.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            showSecurityWarning('Right-click is disabled on video content');
            return false;
        });

        // Disable drag
        container.addEventListener('dragstart', function(e) {
            e.preventDefault();
            showSecurityWarning('Dragging video content is not allowed');
            return false;
        });
    });
}

// Initialize star rating functionality
function initializeStarRating() {
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingValue = document.getElementById('rating-value');
    const submitButton = document.getElementById('submit-rating');

    if (ratingStars.length === 0) return;

    let selectedRating = 0;

    ratingStars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });

        star.addEventListener('mouseleave', function() {
            highlightStars(selectedRating);
        });

        star.addEventListener('click', function() {
            selectedRating = index + 1;
            if (ratingValue) ratingValue.value = selectedRating;
            if (submitButton) submitButton.disabled = false;
            highlightStars(selectedRating);
        });
    });

    function highlightStars(rating) {
        ratingStars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
}

// Initialize attachment handling
function initializeAttachmentHandling() {
    // Image upload handling
    const imageUpload = document.getElementById('image-upload');
    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            handleImageUpload(e);
        });
    }

    // PDF upload handling
    const pdfUpload = document.getElementById('pdf-upload');
    if (pdfUpload) {
        pdfUpload.addEventListener('change', function(e) {
            handlePdfUpload(e);
        });
    }

    // Voice recording buttons
    const startRecordingBtn = document.getElementById('start-recording-btn');
    const stopRecordingBtn = document.getElementById('stop-recording-btn');

    if (startRecordingBtn) {
        startRecordingBtn.addEventListener('click', startVoiceRecording);
    }

    if (stopRecordingBtn) {
        stopRecordingBtn.addEventListener('click', stopVoiceRecording);
    }
}

// Handle image upload
function handleImageUpload(event) {
    if (event.target.files.length > 0) {
        const file = event.target.files[0];
        if (file.size > 5 * 1024 * 1024) { // 5MB limit
            alert('Image file is too large. Maximum allowed size is 5MB.');
            return;
        }

        attachments.images.push(file);
        updateAttachmentPreview();
    }
}

// Handle PDF upload
function handlePdfUpload(event) {
    if (event.target.files.length > 0) {
        const file = event.target.files[0];
        if (file.size > 10 * 1024 * 1024) { // 10MB limit
            alert('PDF file is too large. Maximum allowed size is 10MB.');
            return;
        }

        attachments.pdfs.push(file);
        updateAttachmentPreview();
    }
}

// Start voice recording
function startVoiceRecording() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Your browser does not support audio recording.');
        return;
    }

    const constraints = {
        audio: {
            echoCancellation: true,
            noiseSuppression: true,
            autoGainControl: true
        }
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            const startBtn = document.getElementById('start-recording-btn');
            const stopBtn = document.getElementById('stop-recording-btn');

            if (startBtn) startBtn.classList.add('d-none');
            if (stopBtn) stopBtn.classList.remove('d-none');

            try {
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.addEventListener('dataavailable', event => {
                    if (event.data.size > 0) {
                        audioChunks.push(event.data);
                    }
                });

                mediaRecorder.addEventListener('stop', () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    attachments.voice = new File([audioBlob], 'voice-note.webm', {
                        type: 'audio/webm'
                    });

                    updateAttachmentPreview();
                    stream.getTracks().forEach(track => track.stop());
                });

                mediaRecorder.start(10);
            } catch (err) {
                console.error('Error initializing media recorder:', err);
                alert('Error initializing audio recording. Please try again.');

                if (startBtn) startBtn.classList.remove('d-none');
                if (stopBtn) stopBtn.classList.add('d-none');
                stream.getTracks().forEach(track => track.stop());
            }
        })
        .catch(error => {
            console.error('Error accessing microphone:', error);
            alert('Failed to access microphone. Please ensure you have given permission.');
        });
}

// Stop voice recording
function stopVoiceRecording() {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        try {
            mediaRecorder.stop();
        } catch (err) {
            console.error('Error stopping recording:', err);
        }

        const startBtn = document.getElementById('start-recording-btn');
        const stopBtn = document.getElementById('stop-recording-btn');

        if (stopBtn) stopBtn.classList.add('d-none');
        if (startBtn) startBtn.classList.remove('d-none');
    }
}

// Update attachment preview
function updateAttachmentPreview() {
    const previewArea = document.getElementById('attachments-preview');
    const attachmentList = document.getElementById('attachment-list');

    if (!previewArea || !attachmentList) return;

    // Clear previous preview
    attachmentList.innerHTML = '';

    // Check if we have any attachments
    const hasAttachments = attachments.images.length > 0 ||
                          attachments.pdfs.length > 0 ||
                          attachments.voice !== null;

    if (hasAttachments) {
        previewArea.classList.remove('d-none');

        // Add image previews
        attachments.images.forEach((image, index) => {
            const imgPreview = document.createElement('div');
            imgPreview.className = 'attachment-preview';
            imgPreview.innerHTML = `
                <div class="card card-preview-small">
                    <img src="${URL.createObjectURL(image)}" class="card-img-top card-img-height">
                    <div class="card-footer p-1">
                        <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="image" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            attachmentList.appendChild(imgPreview);
        });

        // Add PDF previews
        attachments.pdfs.forEach((pdf, index) => {
            const pdfPreview = document.createElement('div');
            pdfPreview.className = 'attachment-preview';
            pdfPreview.innerHTML = `
                <div class="card card-preview-small">
                    <div class="card-body p-2 text-center">
                        <i class="fas fa-file-pdf fa-2x text-danger"></i>
                        <p class="mb-0 small text-truncate">${pdf.name}</p>
                    </div>
                    <div class="card-footer p-1">
                        <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="pdf" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            attachmentList.appendChild(pdfPreview);
        });

        // Add voice preview
        if (attachments.voice) {
            const voicePreview = document.createElement('div');
            voicePreview.className = 'attachment-preview';
            voicePreview.innerHTML = `
                <div class="card card-preview-medium">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-microphone text-primary"></i>
                            <audio controls class="audio-controls">
                                <source src="${URL.createObjectURL(attachments.voice)}" type="${attachments.voice.type}">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                    <div class="card-footer p-1">
                        <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="voice">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            attachmentList.appendChild(voicePreview);
        }

        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-attachment').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                if (type === 'image') {
                    attachments.images.splice(parseInt(this.dataset.index), 1);
                } else if (type === 'pdf') {
                    attachments.pdfs.splice(parseInt(this.dataset.index), 1);
                } else if (type === 'voice') {
                    attachments.voice = null;
                }
                updateAttachmentPreview();
            });
        });

    } else {
        previewArea.classList.add('d-none');
    }
}

// Initialize progress tracking
function initializeProgressTracking() {
    // Handle dynamic progress bar widths
    const progressBars = document.querySelectorAll('.progress-bar[data-width]');
    progressBars.forEach(bar => {
        const width = bar.getAttribute('data-width');
        if (width) {
            bar.style.width = width + '%';
        }
    });
}

// Initialize security features
function initializeSecurityFeatures() {
    // Disable common keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Prevent common screenshot/recording shortcuts
        if (
            e.key === 'PrintScreen' ||
            (e.ctrlKey && e.key === 'p') || // Print
            (e.ctrlKey && e.key === 's') || // Save
            (e.ctrlKey && e.shiftKey && e.key === 'i') || // Developer tools
            (e.ctrlKey && e.shiftKey && e.key === 'c') || // Element inspect
            e.key === 'F12' // Developer tools
        ) {
            e.preventDefault();
            showSecurityWarning('This action is not allowed while viewing course content');
            return false;
        }
    });
}

// Show security warning
function showSecurityWarning(message) {
    // Create warning element
    const warning = document.createElement('div');
    warning.className = 'alert alert-warning position-fixed';
    warning.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 300px;
        animation: slideIn 0.3s ease-out;
    `;

    warning.innerHTML = `
        <div class="security-protection-flex">
            <span class="security-protection-icon">🛡️</span>
            <div>
                <div class="security-protection-title">Security Protection</div>
                <div class="security-protection-message">${message}</div>
            </div>
        </div>
    `;

    document.body.appendChild(warning);

    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (warning.parentNode) {
            warning.parentNode.removeChild(warning);
        }
    }, 3000);
}

// Create resume dialog
function createResumeDialog(formattedTime, resumeCallback, startOverCallback) {
    const resumeDialog = document.createElement('div');
    resumeDialog.className = 'resume-dialog';
    resumeDialog.innerHTML = `
        <div class="resume-dialog-content bg-light p-3 rounded shadow-sm position-absolute top-50 start-50 translate-middle">
            <p>You were at <strong>${formattedTime}</strong>. Would you like to resume from where you left off?</p>
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-sm btn-outline-secondary resume-no">No, Start Over</button>
                <button class="btn btn-sm btn-primary resume-yes">Yes, Resume</button>
            </div>
        </div>
    `;

    // Add event listeners
    const resumeYes = resumeDialog.querySelector('.resume-yes');
    const resumeNo = resumeDialog.querySelector('.resume-no');

    resumeYes.addEventListener('click', function() {
        resumeCallback();
        document.body.removeChild(resumeDialog);
    });

    resumeNo.addEventListener('click', function() {
        startOverCallback();
        document.body.removeChild(resumeDialog);
    });

    document.body.appendChild(resumeDialog);
}

// Replace inline styles in JavaScript-generated content
function replaceInlineStyles() {
    // Replace inline styles in attachment previews
    const attachmentPreviews = document.querySelectorAll('.attachment-preview');
    attachmentPreviews.forEach(preview => {
        const cards = preview.querySelectorAll('[style*="width: 100px"]');
        cards.forEach(card => {
            card.removeAttribute('style');
            card.classList.add('card-preview-small');
        });

        const mediumCards = preview.querySelectorAll('[style*="width: 180px"]');
        mediumCards.forEach(card => {
            card.removeAttribute('style');
            card.classList.add('card-preview-medium');
        });

        const cardImages = preview.querySelectorAll('[style*="height: 80px"]');
        cardImages.forEach(img => {
            img.removeAttribute('style');
            img.classList.add('card-img-height');
        });
    });

    // Replace inline styles in security warnings
    const securityIcons = document.querySelectorAll('[style*="font-size: 48px; color: red"]');
    securityIcons.forEach(icon => {
        icon.removeAttribute('style');
        icon.classList.add('security-warning-icon');
    });

    // Replace inline styles in recording warnings
    const recordingWarnings = document.querySelectorAll('#recording-warning');
    recordingWarnings.forEach(warning => {
        warning.classList.add('recording-warning-overlay');

        // Replace child element styles
        const title = warning.querySelector('[style*="font-size: 48px"]');
        if (title) {
            title.removeAttribute('style');
            title.classList.add('recording-warning-title');
        }

        const heading = warning.querySelector('h2[style*="margin-bottom: 15px"]');
        if (heading) {
            heading.removeAttribute('style');
            heading.classList.add('recording-warning-heading');
        }

        const buttons = warning.querySelectorAll('button[style]');
        buttons.forEach(button => {
            if (button.textContent.includes('Refresh')) {
                button.removeAttribute('style');
                button.classList.add('recording-warning-refresh');
            } else if (button.textContent.includes('Continue')) {
                button.removeAttribute('style');
                button.classList.add('recording-warning-dismiss');
            }
        });
    });

    // Replace manual progress tester styles
    const progressTester = document.getElementById('manual-progress-tester');
    if (progressTester) {
        progressTester.removeAttribute('style');
        progressTester.classList.add('manual-progress-tester');

        const buttons = progressTester.querySelectorAll('button[style]');
        buttons.forEach(button => {
            const style = button.getAttribute('style');
            button.removeAttribute('style');

            if (style.includes('#007bff')) {
                button.classList.add('manual-time-button');
            } else if (style.includes('#28a745')) {
                button.classList.add('manual-save-button');
            } else if (style.includes('#dc3545')) {
                button.classList.add('manual-reset-button');
            } else if (style.includes('#6c757d')) {
                button.classList.add('manual-hide-button');
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeLectureDetail();

    // Set up mutation observer to replace inline styles in dynamically generated content
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Check if the added node or its children have inline styles
                        if (node.hasAttribute && node.hasAttribute('style')) {
                            replaceInlineStyles();
                        }

                        const styledElements = node.querySelectorAll ? node.querySelectorAll('[style]') : [];
                        if (styledElements.length > 0) {
                            replaceInlineStyles();
                        }
                    }
                });
            }
        });
    });

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
