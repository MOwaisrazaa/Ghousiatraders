<div class="simple-answer-form">
    <form wire:submit.prevent="submitAnswer">
        <!-- Answer Content -->
        <div class="form-group mb-3">
            <label for="content" class="form-label">Your Answer</label>
            <textarea class="form-control" wire:model="content" rows="4" placeholder="Type your answer here..." required></textarea>
            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Pin Answer Option -->
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" wire:model="isPinned" id="isPinned{{ $questionId }}">
            <label class="form-check-label" for="isPinned{{ $questionId }}">
                <i class="fas fa-thumbtack me-1"></i> Pin this answer (mark as important)
            </label>
        </div>

        <!-- File Attachments -->
        <div class="row mb-3">
            <!-- Image Upload -->
            <div class="col-md-6">
                <label for="image" class="form-label">Add Image</label>
                <input type="file" class="form-control" wire:model="image" accept="image/*">
                @error('image') <span class="text-danger">{{ $message }}</span> @enderror

                @if ($image)
                    <div class="mt-2">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="img-thumbnail img-thumbnail-preview">
                    </div>
                @endif
            </div>

            <!-- PDF Upload -->
            <div class="col-md-6">
                <label for="pdf" class="form-label">Add PDF</label>
                <input type="file" class="form-control" wire:model="pdf" accept=".pdf">
                @error('pdf') <span class="text-danger">{{ $message }}</span> @enderror

                @if ($pdf)
                    <div class="mt-2">
                        <i class="fas fa-file-pdf text-danger"></i> {{ $pdf->getClientOriginalName() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Voice Recording Section -->
        <div class="voice-recording-section mb-3">
            <label class="form-label">Voice Recording</label>
            <div class="recording-controls">
                <button type="button" id="startRecord{{ $questionId }}" class="btn btn-success btn-sm me-2">
                    <i class="fas fa-microphone"></i> Start Recording
                </button>
                <button type="button" id="stopRecord{{ $questionId }}" class="btn btn-danger btn-sm me-2" disabled>
                    <i class="fas fa-stop"></i> Stop Recording
                </button>
                <button type="button" id="resetRecord{{ $questionId }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-trash"></i> Clear
                </button>
            </div>

            <div id="recordingStatus{{ $questionId }}" class="mt-2 text-muted small">Ready to record</div>
            <div id="audioPreview{{ $questionId }}" class="mt-2"></div>

            <!-- Hidden input for voice data -->
            <input type="hidden" id="voiceData{{ $questionId }}" wire:model="voiceData">
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <i class="fas fa-paper-plane me-1"></i> Submit Answer
                </span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin me-1"></i> Submitting...
                </span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionId = {{ $questionId }};

    // Simple recording implementation
    let mediaRecorder = null;
    let audioChunks = [];
    let audioStream = null;
    let isRecording = false;

    const startBtn = document.getElementById(`startRecord${questionId}`);
    const stopBtn = document.getElementById(`stopRecord${questionId}`);
    const resetBtn = document.getElementById(`resetRecord${questionId}`);
    const statusDiv = document.getElementById(`recordingStatus${questionId}`);
    const previewDiv = document.getElementById(`audioPreview${questionId}`);
    const hiddenInput = document.getElementById(`voiceData${questionId}`);

    // Start recording
    startBtn?.addEventListener('click', async function() {
        try {
            // Clean up any existing recording first
            cleanupRecording();

            console.log('Starting new recording...');

            // Request audio permission with specific constraints
            const constraints = {
                audio: {
                    echoCancellation: true,
                    noiseSuppression: true,
                    autoGainControl: true
                }
            };

            audioStream = await navigator.mediaDevices.getUserMedia(constraints);
            console.log('Audio stream obtained successfully');

            // Initialize media recorder with specific settings
            const options = { mimeType: 'audio/webm' };
            try {
                mediaRecorder = new MediaRecorder(audioStream, options);
                console.log('MediaRecorder created with webm format');
            } catch (e) {
                // Fallback if preferred format not supported
                console.log('Using default MediaRecorder format');
                mediaRecorder = new MediaRecorder(audioStream);
            }

            audioChunks = [];
            isRecording = true;

            mediaRecorder.ondataavailable = function(event) {
                if (event.data.size > 0 && isRecording) {
                    audioChunks.push(event.data);
                    console.log('Audio chunk received:', event.data.size);
                }
            };

            mediaRecorder.onstop = function() {
                console.log('MediaRecorder stopped, processing audio...');
                statusDiv.textContent = 'Processing...';
                statusDiv.className = 'mt-2 text-info small';

                if (audioChunks.length > 0) {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    console.log('Created audio blob:', audioBlob.size, 'bytes');

                    // Convert to base64 first
                    const reader = new FileReader();
                    reader.readAsDataURL(audioBlob);
                    reader.onloadend = () => {
                        const base64data = reader.result;
                        console.log(`Base64 data length: ${base64data.length} chars`);

                        // Save to hidden input
                        hiddenInput.value = base64data;
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));

                        // Create audio preview using base64 data
                        const audio = document.createElement('audio');
                        audio.controls = true;
                        audio.className = 'w-100 mt-2';
                        audio.style.maxWidth = '100%';
                        audio.preload = 'metadata';

                        // Add error handling for audio element
                        audio.onerror = function(e) {
                            console.error('Audio playback error:', e);
                            console.error('Audio error details:', audio.error);
                        };

                        audio.onloadeddata = function() {
                            console.log('Audio data loaded successfully');
                        };

                        // Use base64 data directly for audio source
                        audio.src = base64data;

                        // Clear preview div and add audio
                        previewDiv.innerHTML = '';
                        previewDiv.appendChild(audio);

                        console.log('Audio preview created with base64 data');

                        // Update status to show recording is ready
                        statusDiv.textContent = 'Recording ready - you can listen to it above';
                        statusDiv.className = 'mt-2 text-success small';

                        console.log('Audio data saved to hidden input');
                    };

                } else {
                    console.log('No audio chunks available');
                    statusDiv.textContent = 'No audio recorded';
                    statusDiv.className = 'mt-2 text-warning small';
                }

                // Clean up recording resources (but keep the preview)
                cleanupRecordingResources();
            };

            // Set a timeout of 10ms before starting recording
            // This helps avoid some browser bugs
            setTimeout(() => {
                // Start recording with 10ms timeslice for regular chunks
                mediaRecorder.start(10);
                console.log('MediaRecorder started', mediaRecorder.state);
            }, 10);

            // Update UI
            startBtn.disabled = true;
            stopBtn.disabled = false;
            statusDiv.textContent = 'Recording...';
            statusDiv.className = 'mt-2 text-danger small';

        } catch (error) {
            console.error('Error starting recording:', error);
            alert('Error accessing microphone: ' + error.message);
            cleanupRecording();
        }
    });

    // Stop recording
    stopBtn?.addEventListener('click', function() {
        console.log('Stop button clicked');
        if (mediaRecorder && isRecording) {
            isRecording = false;
            mediaRecorder.stop();

            // Update UI immediately
            startBtn.disabled = false;
            stopBtn.disabled = true;
            statusDiv.textContent = 'Processing...';
            statusDiv.className = 'mt-2 text-info small';
        }
    });

    // Reset recording
    resetBtn?.addEventListener('click', function() {
        console.log('Reset button clicked');
        cleanupRecording();
        previewDiv.innerHTML = '';
        hiddenInput.value = '';
        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        statusDiv.textContent = 'Ready to record';
        statusDiv.className = 'mt-2 text-muted small';
    });

    // Clean up recording resources (but keep preview)
    function cleanupRecordingResources() {
        console.log('Cleaning up recording resources...');
        isRecording = false;

        if (audioStream) {
            audioStream.getTracks().forEach(track => {
                track.stop();
                console.log('Stopped audio track');
            });
            audioStream = null;
        }

        if (mediaRecorder) {
            try {
                if (mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                }
            } catch (e) {
                console.log('Error stopping mediaRecorder:', e);
            }
            mediaRecorder = null;
        }

        // Reset UI
        if (startBtn) startBtn.disabled = false;
        if (stopBtn) stopBtn.disabled = true;

        console.log('Recording resources cleanup complete');
    }

    // Full cleanup function (including preview)
    function cleanupRecording() {
        console.log('Full cleanup including preview...');
        cleanupRecordingResources();

        // Also clear preview and form data
        if (previewDiv) previewDiv.innerHTML = '';
        if (hiddenInput) {
            hiddenInput.value = '';
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (statusDiv) {
            statusDiv.textContent = 'Ready to record';
            statusDiv.className = 'mt-2 text-muted small';
        }

        console.log('Full cleanup complete');
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', cleanupRecording);

    // Cleanup when modal closes
    const modal = document.querySelector(`#answerModal${questionId}`);
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            console.log('Modal closed - cleaning up recording');
            cleanupRecording();
        });
    }
});
</script>
