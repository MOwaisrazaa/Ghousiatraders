<div class="answer-form mt-4" wire:key="answer-form-{{ $questionId }}" id="answer-form-{{ $questionId }}">
    <hr>
    <h6 class="mb-3"><i class="fas fa-reply me-1"></i> Your Answer</h6>

    <!-- Debug info - can be removed after testing -->
    <small class="text-muted mb-2 d-block">Question ID: {{ $questionId }} | Status: {{ $recordingStatus }}</small>

    <form wire:submit.prevent="submitAnswer">
        <div class="form-group mb-3">
            <textarea class="form-control" wire:model="content" rows="4" placeholder="Type your answer here..." required></textarea>
            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Attachment options -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Add Image</label>
                    <input type="file" class="form-control" wire:model="image" accept="image/*">
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror

                    @if($image)
                    <div class="mt-2">
                        <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail img-thumbnail-preview">
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Add PDF</label>
                    <input type="file" class="form-control" wire:model="pdf" accept="application/pdf">
                    @error('pdf') <span class="text-danger">{{ $message }}</span> @enderror

                    @if($pdf)
                    <div class="mt-2">
                        <span class="badge bg-danger">
                            <i class="fas fa-file-pdf me-1"></i> {{ $pdf->getClientOriginalName() }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label d-block">Voice Recording</label>
                    <div class="d-flex gap-2">
                        @if($recordingStatus === 'idle')
                            <button type="button" class="btn btn-outline-primary" id="start-recording-btn-{{ $questionId }}"
                                    onclick="console.log('Record button clicked for question {{ $questionId }}'); window.startRecording{{ $questionId }}();">
                                <i class="fas fa-microphone"></i> Record
                            </button>
                        @elseif($recordingStatus === 'recording')
                            <button type="button" class="btn btn-outline-danger" id="stop-recording-btn-{{ $questionId }}"
                                    onclick="console.log('=== STOP BUTTON CLICKED for question {{ $questionId }} ==='); window.stopRecording{{ $questionId }}();">
                                <i class="fas fa-stop-circle"></i> Stop
                            </button>
                        @elseif($recordingStatus === 'recorded')
                            <button type="button" class="btn btn-outline-secondary" id="reset-recording-btn-{{ $questionId }}"
                                    onclick="console.log('Reset button clicked for question {{ $questionId }}'); window.resetRecording{{ $questionId }}();">
                                <i class="fas fa-redo"></i> Record Again
                            </button>
                        @endif
                    </div>

                    @if($recordingStatus === 'recording')
                    <div class="mt-2">
                        <div class="d-flex align-items-center p-2 border rounded bg-danger-subtle">
                            <i class="fas fa-circle-notch fa-spin text-danger me-2"></i>
                            <span>Recording in progress...</span>
                        </div>
                    </div>
                    @endif

                    @if($recordingStatus === 'recorded')
                    <div class="mt-2">
                        <div class="d-flex flex-column align-items-start p-2 border rounded">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-microphone text-success me-2"></i>
                                <span>Voice recording saved</span>
                            </div>
                            <div id="audio-preview-{{ $questionId }}" class="w-100">
                                <!-- Audio preview will be inserted here by JavaScript -->
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Hidden field to store the voice data -->
                    <input type="hidden" id="voice-data-{{ $questionId }}" name="voiceData" wire:model="voiceData">
                </div>
            </div>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_pinned{{ $questionId }}" wire:model="isPinned">
            <label class="form-check-label" for="is_pinned{{ $questionId }}">
                Pin this answer (featured at the top)
            </label>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-1"></i> Submit Answer
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Initialize recording for question {{ $questionId }}
    (function() {
        const questionId = {{ $questionId }};
        console.log('=== ANSWER FORM SCRIPT LOADED FOR QUESTION:', questionId, '===');

        function initializeRecording() {
            console.log('=== INITIALIZING RECORDING FOR QUESTION:', questionId, '===');

            // Button references
            const startBtn = document.getElementById(`start-recording-btn-${questionId}`);
            const stopBtn = document.getElementById(`stop-recording-btn-${questionId}`);
            const resetBtn = document.getElementById(`reset-recording-btn-${questionId}`);
            const hiddenInput = document.getElementById(`voice-data-${questionId}`);
            const previewContainer = document.getElementById(`audio-preview-${questionId}`);

            console.log('=== BUTTON ELEMENTS FOUND ===');
            console.log('Start button:', startBtn);
            console.log('Stop button:', stopBtn);
            console.log('Reset button:', resetBtn);
            console.log('Hidden input:', hiddenInput);
            console.log('Preview container:', previewContainer);

            // Find Livewire component - compatible with both v2 and v3
            function getLivewireComponent() {
                const el = document.getElementById(`answer-form-${questionId}`);
                if (!el) {
                    console.log('Element not found:', `answer-form-${questionId}`);
                    return null;
                }

                // Try different methods to get the Livewire component
                try {
                    // Livewire v3 method
                    if (window.Livewire && window.Livewire.find) {
                        const wireId = el.getAttribute('wire:id');
                        if (wireId) {
                            return window.Livewire.find(wireId);
                        }
                    }

                    // Livewire v2 method
                    if (window.livewire && window.livewire.find) {
                        const wireId = el.getAttribute('wire:id');
                        if (wireId) {
                            return window.livewire.find(wireId);
                        }
                    }

                    // Alternative method - look for component on element
                    if (el.__livewire) {
                        return el.__livewire;
                    }

                    console.log('Could not find Livewire component for question:', questionId);
                    return null;
                } catch (error) {
                    console.error('Error finding Livewire component:', error);
                    return null;
                }
            }

            // Recording state - NEW APPROACH
            let recordingSession = null;

            // Global cleanup function to destroy ALL recording sessions
            window.destroyAllRecordingSessions = function() {
                console.log('=== DESTROYING ALL RECORDING SESSIONS ===');
                if (recordingSession) {
                    recordingSession.forceCleanup();
                    recordingSession = null;
                }

                // Also destroy any global recording sessions
                if (window.globalRecordingSession) {
                    window.globalRecordingSession.forceCleanup();
                    window.globalRecordingSession = null;
                }

                // Stop all media tracks globally
                navigator.mediaDevices.getUserMedia({audio: true}).then(stream => {
                    stream.getTracks().forEach(track => {
                        track.stop();
                        console.log('Stopped global media track');
                    });
                }).catch(() => {
                    // Ignore errors - just trying to cleanup
                });

                console.log('=== ALL RECORDING SESSIONS DESTROYED ===');
            };

            // Recording Session Class with AGGRESSIVE cleanup
            class RecordingSession {
                constructor() {
                    this.mediaRecorder = null;
                    this.audioStream = null;
                    this.audioChunks = [];
                    this.isActive = false;
                    this.sessionId = Date.now() + Math.random();
                    this.cleanupTimeout = null;
                    this.isDestroyed = false;
                    console.log(`NEW RECORDING SESSION CREATED: ${this.sessionId}`);
                }

                async start() {
                    if (this.isActive) {
                        console.log('Session already active, destroying first...');
                        this.destroy();
                    }

                    console.log(`STARTING SESSION: ${this.sessionId}`);
                    this.isActive = true;
                    this.audioChunks = [];

                    try {
                        // Get fresh audio stream
                        this.audioStream = await navigator.mediaDevices.getUserMedia({
                            audio: {
                                echoCancellation: true,
                                noiseSuppression: true,
                                autoGainControl: true
                            }
                        });

                        console.log(`Audio stream obtained for session: ${this.sessionId}`);

                        // Create MediaRecorder with timeout protection
                        const options = { mimeType: 'audio/webm' };
                        this.mediaRecorder = new MediaRecorder(this.audioStream, options);

                        // Data handler with destruction check
                        this.mediaRecorder.ondataavailable = (event) => {
                            if (this.isDestroyed) {
                                console.log(`Session ${this.sessionId}: IGNORING chunk - session DESTROYED`);
                                return;
                            }
                            if (this.isActive && event.data.size > 0) {
                                this.audioChunks.push(event.data);
                                console.log(`Session ${this.sessionId}: chunk ${event.data.size} bytes`);
                            } else if (!this.isActive) {
                                console.log(`Session ${this.sessionId}: IGNORING chunk - session inactive`);
                            }
                        };

                        // Start recording
                        this.mediaRecorder.start(100);
                        console.log(`MediaRecorder started for session: ${this.sessionId}`);

                        return true;
                    } catch (error) {
                        console.error(`Error starting session ${this.sessionId}:`, error);
                        this.destroy();
                        throw error;
                    }
                }

                async stop() {
                    if (!this.isActive) {
                        console.log(`Session ${this.sessionId}: Already stopped`);
                        return null;
                    }

                    console.log(`STOPPING SESSION: ${this.sessionId}`);
                    this.isActive = false;

                    // Clear any existing cleanup timeout
                    if (this.cleanupTimeout) {
                        clearTimeout(this.cleanupTimeout);
                        this.cleanupTimeout = null;
                    }

                    return new Promise((resolve) => {
                        if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                            this.mediaRecorder.onstop = () => {
                                console.log(`Session ${this.sessionId}: MediaRecorder stopped`);
                                const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                                console.log(`Session ${this.sessionId}: Final blob size: ${audioBlob.size} bytes`);

                                // Immediate cleanup
                                this.forceCleanup();

                                resolve(audioBlob);
                            };

                            this.mediaRecorder.stop();

                            // Force cleanup after 500ms if MediaRecorder doesn't stop properly
                            this.cleanupTimeout = setTimeout(() => {
                                console.log(`Session ${this.sessionId}: FORCE CLEANUP AFTER TIMEOUT`);
                                this.forceCleanup();
                                resolve(null);
                            }, 500);
                        } else {
                            console.log(`Session ${this.sessionId}: No active MediaRecorder to stop`);
                            this.forceCleanup();
                            resolve(null);
                        }
                    });
                }

                forceCleanup() {
                    console.log(`FORCE CLEANUP SESSION: ${this.sessionId}`);
                    this.isActive = false;
                    this.isDestroyed = true; // Mark as destroyed to prevent any further processing

                    // Clear any cleanup timeout
                    if (this.cleanupTimeout) {
                        clearTimeout(this.cleanupTimeout);
                        this.cleanupTimeout = null;
                    }

                    // Stop all audio tracks FIRST and IMMEDIATELY
                    if (this.audioStream) {
                        this.audioStream.getTracks().forEach((track, i) => {
                            console.log(`FORCE STOPPING track ${i} for session ${this.sessionId}`);
                            try {
                                track.stop();
                                track.enabled = false;
                            } catch (e) {
                                console.log(`Error stopping track ${i}:`, e);
                            }
                        });
                        this.audioStream = null;
                    }

                    // AGGRESSIVELY destroy MediaRecorder
                    if (this.mediaRecorder) {
                        try {
                            // Remove ALL event handlers FIRST
                            this.mediaRecorder.ondataavailable = null;
                            this.mediaRecorder.onstop = null;
                            this.mediaRecorder.onerror = null;
                            this.mediaRecorder.onstart = null;
                            this.mediaRecorder.onpause = null;
                            this.mediaRecorder.onresume = null;

                            // Force stop if recording
                            if (this.mediaRecorder.state === 'recording' || this.mediaRecorder.state === 'paused') {
                                this.mediaRecorder.stop();
                            }
                        } catch (e) {
                            console.log(`Error destroying MediaRecorder for session ${this.sessionId}:`, e);
                        }
                        this.mediaRecorder = null;
                    }

                    // Clear chunks
                    this.audioChunks = [];

                    console.log(`SESSION AGGRESSIVELY DESTROYED: ${this.sessionId}`);
                }

                destroy() {
                    this.forceCleanup();
                }
            }

            // Setup event listeners if buttons exist
            if (startBtn && !startBtn.hasAttribute('data-recording-initialized')) {
                startBtn.addEventListener('click', startRecording);
                startBtn.setAttribute('data-recording-initialized', 'true');
                console.log('=== START BUTTON INITIALIZED FOR QUESTION:', questionId, '===');
            } else if (startBtn) {
                console.log('=== START BUTTON ALREADY INITIALIZED FOR QUESTION:', questionId, '===');
            } else {
                console.log('=== START BUTTON NOT FOUND FOR QUESTION:', questionId, '===');
            }

            if (stopBtn && !stopBtn.hasAttribute('data-recording-initialized')) {
                stopBtn.addEventListener('click', stopRecording);
                stopBtn.setAttribute('data-recording-initialized', 'true');
                console.log('=== STOP BUTTON INITIALIZED FOR QUESTION:', questionId, '===');
            } else if (stopBtn) {
                console.log('=== STOP BUTTON ALREADY INITIALIZED FOR QUESTION:', questionId, '===');
            } else {
                console.log('=== STOP BUTTON NOT FOUND FOR QUESTION:', questionId, '===');
            }

            if (resetBtn && !resetBtn.hasAttribute('data-recording-initialized')) {
                resetBtn.addEventListener('click', resetRecording);
                resetBtn.setAttribute('data-recording-initialized', 'true');
                console.log('Reset button initialized for question:', questionId);
            }

            // Helper function to update recording status
            function updateRecordingStatus(status) {
                console.log(`Updating recording status to: ${status} for question: ${questionId}`);

                // Try to update via Livewire component
                const component = getLivewireComponent();
                if (component && component.set) {
                    try {
                        component.set('recordingStatus', status);
                        console.log('Status updated via Livewire component');
                        return true;
                    } catch (error) {
                        console.error('Error updating via Livewire component:', error);
                    }
                }

                // Fallback: trigger a Livewire call directly
                try {
                    const el = document.getElementById(`answer-form-${questionId}`);
                    if (el && window.Livewire) {
                        // Dispatch a custom event that Livewire can listen to
                        el.dispatchEvent(new CustomEvent('recording-status-changed', {
                            detail: { status: status, questionId: questionId }
                        }));
                        console.log('Status update event dispatched');
                        return true;
                    }
                } catch (error) {
                    console.error('Error dispatching status update event:', error);
                }

                console.log('Could not update recording status via Livewire');
                return false;
            }

            // Start recording function - NEW APPROACH
            async function startRecording() {
                console.log('=== STARTING NEW RECORDING SESSION ===');
                console.log('Question ID:', questionId);

                // Destroy any existing session first
                if (recordingSession) {
                    console.log('Destroying existing session before starting new one...');
                    recordingSession.forceCleanup();
                    recordingSession = null;
                }

                // Update UI first
                updateRecordingStatus('recording');

                try {
                    // Create new recording session
                    recordingSession = new RecordingSession();
                    await recordingSession.start();

                    console.log('=== RECORDING SESSION STARTED SUCCESSFULLY ===');
                } catch (error) {
                    console.error('Error starting recording session:', error);
                    alert('Error accessing microphone: ' + error.message);

                    // Clean up and reset UI state
                    if (recordingSession) {
                        recordingSession.forceCleanup();
                        recordingSession = null;
                    }
                    updateRecordingStatus('idle');
                }
            }

        // Stop recording function - NEW APPROACH
        async function stopRecording() {
            console.log('=== STOP RECORDING CALLED - NEW APPROACH ===');

            if (!recordingSession) {
                console.log('No active recording session to stop');
                resetToIdle();
                return;
            }

            try {
                // Stop the session and get the audio blob (now async)
                const audioBlob = await recordingSession.stop();

                // Clear the session reference
                recordingSession = null;

                console.log('Recording session stopped and destroyed');

                // Process the audio if we got data
                if (audioBlob && audioBlob.size > 0) {
                    console.log(`Processing audio blob: ${audioBlob.size} bytes`);
                    processRecordingBlob(audioBlob);
                } else {
                    console.log('No audio data captured, resetting to idle');
                    resetToIdle();
                }

            } catch (error) {
                console.error('Error stopping recording session:', error);

                // Force cleanup
                if (recordingSession) {
                    recordingSession.forceCleanup();
                    recordingSession = null;
                }

                resetToIdle();
            }

            console.log('=== STOP RECORDING COMPLETED ===');
        }

        // Process recording blob
        function processRecordingBlob(audioBlob) {
            console.log('Processing audio blob...');

            // Create audio element for preview
            if (previewContainer) {
                previewContainer.innerHTML = '';
                const audio = document.createElement('audio');
                audio.controls = true;
                audio.className = 'w-100';
                const source = document.createElement('source');
                source.src = URL.createObjectURL(audioBlob);
                source.type = 'audio/webm';
                audio.appendChild(source);
                previewContainer.appendChild(audio);
                console.log('Audio preview element created');
            }

            // Convert to base64 and save
            const reader = new FileReader();
            reader.readAsDataURL(audioBlob);
            reader.onloadend = () => {
                const base64data = reader.result;
                console.log(`Base64 data length: ${base64data.length} chars`);

                if (hiddenInput) {
                    hiddenInput.value = base64data;
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                }

                const component = getLivewireComponent();
                if (component && component.set) {
                    try {
                        component.set('voiceData', base64data);
                        component.set('recordingStatus', 'recorded');
                        console.log('Livewire component updated with voice data');
                    } catch (error) {
                        console.error('Error updating Livewire component:', error);
                        updateRecordingStatus('recorded');
                    }
                } else {
                    updateRecordingStatus('recorded');
                }
            };
        }

        // Reset to idle state
        function resetToIdle() {
            console.log('Resetting to idle state...');
            updateRecordingStatus('idle');
            if (previewContainer) {
                previewContainer.innerHTML = '';
            }
            if (hiddenInput) {
                hiddenInput.value = '';
            }
        }



        // Reset recording function - NEW APPROACH
        function resetRecording() {
            console.log('=== RESETTING RECORDING - NEW APPROACH ===');

            // Destroy any active recording session
            if (recordingSession) {
                console.log('Destroying active recording session...');
                recordingSession.forceCleanup();
                recordingSession = null;
            }

            // Clear UI elements
            if (hiddenInput) {
                hiddenInput.value = '';
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            if (previewContainer) {
                previewContainer.innerHTML = '';
            }

            // Update Livewire component
            const component = getLivewireComponent();
            if (component && component.set) {
                try {
                    component.set('voiceData', null);
                    component.set('recordingStatus', 'idle');
                    console.log('Recording reset via Livewire component');
                } catch (error) {
                    console.error('Error resetting via Livewire component:', error);
                    updateRecordingStatus('idle');
                }
            } else {
                updateRecordingStatus('idle');
            }

            console.log('=== RECORDING RESET COMPLETED ===');
        }

            // Expose functions globally for onclick handlers
            window[`startRecording${questionId}`] = startRecording;
            window[`stopRecording${questionId}`] = async function() {
                console.log(`=== GLOBAL STOP FUNCTION CALLED for question ${questionId} ===`);
                await stopRecording();
            };
            window[`resetRecording${questionId}`] = resetRecording;

            // Add cleanup on page unload - NEW APPROACH
            window.addEventListener('beforeunload', function() {
                if (recordingSession) {
                    console.log('Cleaning up recording session on page unload');
                    recordingSession.forceCleanup();
                    recordingSession = null;
                }
            });

            // Add cleanup when component is removed - NEW APPROACH
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.removedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && node.id === `answer-form-${questionId}`) {
                            if (recordingSession) {
                                console.log('Cleaning up recording session on component removal');
                                recordingSession.forceCleanup();
                                recordingSession = null;
                            }
                        }
                    });
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });

            // Add modal cleanup listeners
            document.addEventListener('DOMContentLoaded', function() {
                // Listen for modal close events
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    modal.addEventListener('hidden.bs.modal', function() {
                        console.log('Modal closed - destroying all recording sessions');
                        if (window.destroyAllRecordingSessions) {
                            window.destroyAllRecordingSessions();
                        }
                    });
                });
            });

            // Also listen for modal close on current modal if it exists
            const currentModal = document.querySelector(`#answerModal${questionId}`);
            if (currentModal) {
                currentModal.addEventListener('hidden.bs.modal', function() {
                    console.log(`Modal ${questionId} closed - destroying recording session`);
                    if (recordingSession) {
                        recordingSession.forceCleanup();
                        recordingSession = null;
                    }
                });
            }

            console.log(`Recording functions exposed globally for question ${questionId}`);

            // Add periodic cleanup to catch any lingering sessions
            setInterval(() => {
                if (recordingSession && recordingSession.isDestroyed) {
                    console.log('Cleaning up destroyed session reference');
                    recordingSession = null;
                }
            }, 2000);
        }

        // Initialize immediately if DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeRecording);
        } else {
            initializeRecording();
        }

        // Also initialize after Livewire updates
        document.addEventListener('livewire:navigated', initializeRecording);

        // For Livewire v3 compatibility
        if (window.Livewire) {
            window.Livewire.hook('morph.updated', () => {
                setTimeout(initializeRecording, 100);
            });
        }
    })();
</script>
@endpush
