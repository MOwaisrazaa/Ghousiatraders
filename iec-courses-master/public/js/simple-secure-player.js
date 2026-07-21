/**
 * Simple Secure Video Player - Clean Implementation
 * Hides YouTube URLs from DOM inspection while maintaining functionality
 */

(function() {
    'use strict';

    class SimpleSecureVideoPlayer {
        constructor() {
            this.player = null;
            this.initialized = false;
            this.debug = true; // Set to false in production
        }

        log(message) {
            if (this.debug) {
                console.log('🎥 SecurePlayer: ' + message);
            }
        }

        error(message) {
            console.error('❌ SecurePlayer: ' + message);
        }

        // Simple obfuscation (in production, use more complex encryption)
        obfuscateId(videoId) {
            return btoa(videoId);
        }

        deobfuscateId(obfuscatedId) {
            try {
                return atob(obfuscatedId);
            } catch (e) {
                this.error('Failed to deobfuscate video ID');
                return null;
            }
        }

        // Get video data from server (secure method)
        async getSecureVideoData(lectureId, courseId) {
            try {
                this.log('Fetching secure video data...');
                
                const response = await fetch(`/my-course/${courseId}/lecture-content/${lectureId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch video data');
                }

                const data = await response.json();
                this.log('Video data received');

                if (data.success && data.youtubeData && data.youtubeData.encrypted_id) {
                    const videoId = this.deobfuscateId(data.youtubeData.encrypted_id);
                    return videoId;
                }

                return null;
            } catch (error) {
                this.error('Failed to get video data: ' + error.message);
                return null;
            }
        }

        async initializePlayer(lectureId, courseId) {
            if (this.initialized) {
                this.log('Player already initialized');
                return;
            }

            try {
                this.log('Starting player initialization...');

                // Find player element
                const playerElement = document.querySelector('.video-player[data-lecture-id]');
                if (!playerElement) {
                    throw new Error('Video player element not found');
                }
                this.log('Player element found');

                // Check if Plyr is available
                if (typeof Plyr === 'undefined') {
                    throw new Error('Plyr library not loaded');
                }
                this.log('Plyr library available');

                // Get secure video data
                const videoId = await this.getSecureVideoData(lectureId, courseId);
                if (!videoId) {
                    throw new Error('No valid video ID received');
                }
                this.log('Video ID obtained securely');

                // Clear loading content
                playerElement.innerHTML = '';

                // Initialize Plyr
                this.player = new Plyr(playerElement, {
                    controls: [
                        'play-large', 'play', 'progress', 'current-time', 'duration',
                        'mute', 'volume', 'fullscreen'
                    ],
                    youtube: {
                        noCookie: true,
                        rel: 0,
                        showinfo: 0,
                        iv_load_policy: 3,
                        modestbranding: 1
                    },
                    ratio: '16:9'
                });

                // Set video source
                this.player.source = {
                    type: 'video',
                    sources: [{
                        src: videoId,
                        provider: 'youtube'
                    }]
                };

                // Add security measures
                this.addSecurityMeasures();

                this.initialized = true;
                this.log('Player initialized successfully!');

                return this.player;

            } catch (error) {
                this.error('Player initialization failed: ' + error.message);
                this.showErrorMessage(error.message);
                return null;
            }
        }

        addSecurityMeasures() {
            if (!this.player) return;

            // Wait for Plyr to create its elements
            setTimeout(() => {
                const plyrContainer = document.querySelector('.plyr');
                if (plyrContainer) {
                    // Prevent right-click
                    plyrContainer.addEventListener('contextmenu', (e) => {
                        e.preventDefault();
                        return false;
                    });

                    // Prevent text selection
                    plyrContainer.style.userSelect = 'none';
                    plyrContainer.style.webkitUserSelect = 'none';

                    this.log('Security measures applied');
                }
            }, 1000);
        }

        showErrorMessage(message) {
            const container = document.querySelector('.video-container');
            if (container) {
                container.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center bg-light h-100">
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                            <p class="text-muted">Unable to load video</p>
                            <small class="text-muted">${message}</small>
                            <br>
                            <button class="btn btn-primary btn-sm mt-2" onclick="location.reload()">Retry</button>
                        </div>
                    </div>
                `;
            }
        }

        destroy() {
            if (this.player) {
                this.player.destroy();
                this.player = null;
            }
            this.initialized = false;
        }
    }

    // Make it globally available
    window.SimpleSecureVideoPlayer = SimpleSecureVideoPlayer;

    // Auto-initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Create global instance
        window.simpleSecurePlayer = new SimpleSecureVideoPlayer();
    });

    // Basic anti-debugging (optional)
    document.addEventListener('keydown', function(e) {
        if (e.keyCode === 123 || // F12
            (e.ctrlKey && e.shiftKey && e.keyCode === 73) || // Ctrl+Shift+I
            (e.ctrlKey && e.shiftKey && e.keyCode === 74) || // Ctrl+Shift+J
            (e.ctrlKey && e.keyCode === 85)) { // Ctrl+U
            e.preventDefault();
            return false;
        }
    });

})();
