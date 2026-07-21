// Purchased Lecture Detail JavaScript

// Global variables
let currentManualTime = 0;
let manualDuration = 300;
let lectureId = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add meta tag to disable Game Bar
    const gameBarMetaTag = document.createElement('meta');
    gameBarMetaTag.name = 'xbox-game-bar-allowed';
    gameBarMetaTag.content = 'false';
    document.head.appendChild(gameBarMetaTag);

    // Initialize lecture ID from data attribute
    const lectureElement = document.querySelector('[data-lecture-id]');
    if (lectureElement) {
        lectureId = lectureElement.getAttribute('data-lecture-id');
    }

    // Initialize all protection methods
    initializeVideoProtection();
    initializeAttachmentHandlers();
    initializeProgressTracking();
});

// Video protection functions
function initializeVideoProtection() {
    const videoContainers = document.querySelectorAll('.video-protected');
    
    videoContainers.forEach(container => {
        // Create copy protection notice
        const notice = document.createElement('div');
        notice.className = 'copy-protected-notice';
        notice.textContent = 'Content is protected. Recording is prohibited.';
        container.appendChild(notice);

        // Create "Recording Detected" overlay
        const recordingDetectionOverlay = document.createElement('div');
        recordingDetectionOverlay.id = 'recording-detection-overlay';
        recordingDetectionOverlay.className = 'recording-detection-overlay';
        recordingDetectionOverlay.style.display = 'none';
        
        recordingDetectionOverlay.innerHTML = `
            <div>
                <i class="fas fa-exclamation-triangle recording-detection-icon"></i>
                <h2>Screen Recording Detected</h2>
                <p>Recording or capturing course content is prohibited.</p>
                <p>User ID: ${getUserId()}</p>
                <p>This violation has been logged and reported.</p>
                <button onclick="location.reload()" class="recording-warning-refresh-btn">
                    Refresh Page
                </button>
            </div>
        `;
        
        document.body.appendChild(recordingDetectionOverlay);
    });
}

// Attachment handling functions
function initializeAttachmentHandlers() {
    // Handle image attachments
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', handleImageAttachment);
    });

    // Handle PDF attachments
    const pdfInputs = document.querySelectorAll('input[type="file"][accept*="pdf"]');
    pdfInputs.forEach(input => {
        input.addEventListener('change', handlePdfAttachment);
    });

    // Handle voice attachments
    const voiceInputs = document.querySelectorAll('input[type="file"][accept*="audio"]');
    voiceInputs.forEach(input => {
        input.addEventListener('change', handleVoiceAttachment);
    });
}

function handleImageAttachment(event) {
    const files = event.target.files;
    const previewContainer = event.target.closest('.form-group').querySelector('.attachment-preview-container');
    
    if (!previewContainer) return;

    Array.from(files).forEach((image, index) => {
        const imgPreview = document.createElement('div');
        imgPreview.className = 'attachment-preview';
        imgPreview.innerHTML = `
            <div class="card attachment-card">
                <img src="${URL.createObjectURL(image)}" class="card-img-top attachment-img">
                <div class="card-footer p-1">
                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="image" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        previewContainer.appendChild(imgPreview);
    });
}

function handlePdfAttachment(event) {
    const files = event.target.files;
    const previewContainer = event.target.closest('.form-group').querySelector('.attachment-preview-container');
    
    if (!previewContainer) return;

    Array.from(files).forEach((pdf, index) => {
        const pdfPreview = document.createElement('div');
        pdfPreview.className = 'attachment-preview';
        pdfPreview.innerHTML = `
            <div class="card attachment-card">
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
        previewContainer.appendChild(pdfPreview);
    });
}

function handleVoiceAttachment(event) {
    const files = event.target.files;
    const previewContainer = event.target.closest('.form-group').querySelector('.attachment-preview-container');
    
    if (!previewContainer) return;

    Array.from(files).forEach((voice, index) => {
        const voicePreview = document.createElement('div');
        voicePreview.className = 'attachment-preview';
        voicePreview.innerHTML = `
            <div class="card attachment-card-wide">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-microphone text-primary"></i>
                        <div class="flex-grow-1">
                            <p class="mb-0 small text-truncate">${voice.name}</p>
                            <audio controls class="w-100 mt-1">
                                <source src="${URL.createObjectURL(voice)}" type="${voice.type}">
                            </audio>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-1">
                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="voice" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        previewContainer.appendChild(voicePreview);
    });
}

// Progress tracking functions
function initializeProgressTracking() {
    // Initialize manual progress tester if in development mode
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('127.0.0.1')) {
        createManualProgressTester();
    }
}

function createManualProgressTester() {
    if (!lectureId) return;

    const tester = document.createElement('div');
    tester.id = 'manual-progress-tester';
    tester.className = 'manual-progress-tester';
    tester.innerHTML = `
        <div class="manual-tester-title">📊 Manual Progress Tester</div>
        <div>Lecture ID: ${lectureId}</div>
        <div>Current Time: <span id="manual-time">0</span>s</div>
        <div>Duration: <span id="manual-duration">300</span>s</div>
        <div>Progress: <span id="manual-progress">0</span>%</div>
        <div class="manual-tester-buttons">
            <button class="manual-test-btn" onclick="window.manualProgressTest(30)">+30s</button>
            <button class="manual-test-btn" onclick="window.manualProgressTest(60)">+60s</button>
            <button class="manual-test-btn" onclick="window.manualProgressTest(120)">+2min</button>
        </div>
        <div class="manual-tester-buttons">
            <button class="manual-save-btn" onclick="window.manualProgressSave()">💾 Save Progress</button>
            <button class="manual-reset-btn" onclick="window.manualProgressReset()">🔄 Reset</button>
        </div>
        <div class="manual-tester-buttons">
            <button class="manual-hide-btn" onclick="document.getElementById('manual-progress-tester').style.display='none'">❌ Hide</button>
        </div>
    `;
    
    document.body.appendChild(tester);
}

// Manual progress test functions
window.manualProgressTest = function(seconds) {
    currentManualTime += seconds;
    if (currentManualTime > manualDuration) {
        currentManualTime = manualDuration;
    }
    
    const progress = (currentManualTime / manualDuration) * 100;
    
    document.getElementById('manual-time').textContent = currentManualTime;
    document.getElementById('manual-progress').textContent = progress.toFixed(1);
};

window.manualProgressSave = function() {
    if (!lectureId) return;
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) return;
    
    fetch('/lecture-progress/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            lecture_id: lectureId,
            current_time: currentManualTime,
            duration: manualDuration
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage('✅ Progress Saved Successfully!');
        }
    })
    .catch(error => {
        console.error('Error saving progress:', error);
    });
};

window.manualProgressReset = function() {
    currentManualTime = 0;
    document.getElementById('manual-time').textContent = '0';
    document.getElementById('manual-progress').textContent = '0';
};

// Utility functions
function getUserId() {
    // This should be populated from the server-side
    return document.querySelector('meta[name="user-id"]')?.getAttribute('content') || 'Unknown';
}

function showSuccessMessage(message) {
    const successMsg = document.createElement('div');
    successMsg.className = 'progress-success-message';
    successMsg.textContent = message;
    document.body.appendChild(successMsg);
    setTimeout(() => successMsg.remove(), 2000);
}

// Security warning functions
function showSecurityWarning(message) {
    const warning = document.createElement('div');
    warning.className = 'security-warning-overlay';

    warning.innerHTML = `
        <div class="security-warning-container">
            <span class="security-warning-icon">🛡️</span>
            <div>
                <div class="security-warning-title">Security Protection</div>
                <div class="security-warning-message">${message}</div>
            </div>
        </div>
    `;

    document.body.appendChild(warning);
    setTimeout(() => warning.remove(), 5000);
}

function showRecordingWarning() {
    const warning = document.createElement('div');
    warning.id = 'recording-warning';
    warning.className = 'recording-warning-overlay';
    
    warning.innerHTML = `
        <div class="recording-warning-icon">⚠️</div>
        <h2 class="recording-warning-title">Content Protection Notice</h2>
        <p class="recording-warning-text">
            Our system detected unusual activity that may indicate screen recording.
        </p>
        <p class="recording-warning-subtext">
            If you're not recording, this might be a false positive. Please refresh to continue watching.
        </p>
        <div class="recording-warning-buttons">
            <button onclick="location.reload()" class="recording-warning-refresh-btn">
                🔄 Refresh Page
            </button>
            <button onclick="dismissRecordingWarning()" class="recording-warning-continue-btn">
                Continue Anyway
            </button>
        </div>
        <p class="recording-warning-note">
            Note: Recording copyrighted content may violate terms of service
        </p>
    `;

    document.body.appendChild(warning);
}

function dismissRecordingWarning() {
    const warning = document.getElementById('recording-warning');
    if (warning) {
        warning.remove();
    }

    const videoContainer = document.querySelector('.ratio-16x9');
    if (videoContainer) {
        videoContainer.style.filter = 'none';
        videoContainer.style.pointerEvents = 'auto';
    }
}

// Resume dialog functions
function showResumeDialog(savedTime, formattedTime) {
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

    document.body.appendChild(resumeDialog);

    // Handle resume dialog buttons
    resumeDialog.querySelector('.resume-yes').addEventListener('click', function() {
        // Resume from saved time
        if (window.player && typeof window.player.currentTime !== 'undefined') {
            window.player.currentTime = savedTime;
        }
        resumeDialog.remove();
    });

    resumeDialog.querySelector('.resume-no').addEventListener('click', function() {
        // Start from beginning
        if (window.player && typeof window.player.currentTime !== 'undefined') {
            window.player.currentTime = 0;
        }
        resumeDialog.remove();
    });
}

// Question and answer attachment handling
function handleQuestionAttachments(questionId, attachments) {
    let attachmentsHtml = '';

    if (attachments && attachments.length > 0) {
        attachments.forEach(attachment => {
            if (attachment.type === 'image') {
                attachmentsHtml += `
                    <div class="mb-2">
                        <a href="${attachment.url}" target="_blank">
                            <img src="${attachment.url}" class="img-thumbnail attachment-thumbnail">
                        </a>
                    </div>`;
            } else if (attachment.type === 'pdf') {
                attachmentsHtml += `
                    <div class="mb-2">
                        <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i> ${attachment.name}
                        </a>
                    </div>`;
            } else if (attachment.type === 'voice') {
                attachmentsHtml += `
                    <div class="mb-2">
                        <audio controls class="w-100">
                            <source src="${attachment.url}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>`;
            }
        });
    }

    return attachmentsHtml;
}

function handleAnswerAttachments(answerId, attachments) {
    let attachmentsHtml = '';

    if (attachments && attachments.length > 0) {
        attachments.forEach(attachment => {
            if (attachment.type === 'image') {
                attachmentsHtml += `
                    <div class="mb-2">
                        <a href="${attachment.url}" target="_blank">
                            <img src="${attachment.url}" class="img-thumbnail attachment-thumbnail">
                        </a>
                    </div>`;
            } else if (attachment.type === 'pdf') {
                attachmentsHtml += `
                    <div class="mb-2">
                        <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf me-1"></i> ${attachment.name}
                        </a>
                    </div>`;
            } else if (attachment.type === 'voice') {
                attachmentsHtml += `
                    <div class="mb-2">
                        <audio controls class="w-100">
                            <source src="${attachment.url}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>`;
            }
        });
    }

    return attachmentsHtml;
}

// Security and protection functions
function detectScreenRecording() {
    // Screen recording detection logic
    let isRecording = false;

    // Monitor for screen recording APIs
    if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
        const originalGetDisplayMedia = navigator.mediaDevices.getDisplayMedia;
        navigator.mediaDevices.getDisplayMedia = function() {
            isRecording = true;
            showRecordingWarning();
            return originalGetDisplayMedia.apply(this, arguments);
        };
    }

    // Monitor for suspicious activity
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            showSecurityWarning('Tab switched - monitoring for recording activity');
        }
    });

    // Monitor for developer tools
    let devtools = {
        open: false,
        orientation: null
    };

    setInterval(function() {
        if (window.outerHeight - window.innerHeight > 200 || window.outerWidth - window.innerWidth > 200) {
            if (!devtools.open) {
                devtools.open = true;
                showSecurityWarning('Developer tools detected');
            }
        } else {
            devtools.open = false;
        }
    }, 500);
}

// Initialize security measures
function initializeSecurity() {
    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable text selection
    document.addEventListener('selectstart', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable drag and drop
    document.addEventListener('dragstart', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+Shift+C
        if (e.keyCode === 123 ||
            (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) ||
            (e.ctrlKey && e.keyCode === 85)) {
            e.preventDefault();
            showSecurityWarning('Keyboard shortcut blocked');
            return false;
        }
    });

    // Initialize screen recording detection
    detectScreenRecording();
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeSecurity();
});
