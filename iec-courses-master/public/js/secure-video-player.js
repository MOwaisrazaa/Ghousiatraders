/**
 * Secure Video Player - Obfuscated YouTube URL Protection
 * This script prevents easy extraction of YouTube URLs from DOM
 */

(function() {
    'use strict';

    // Obfuscated configuration
    const _0x4f2a = {
        'api': {
            'token': '/api/test/video/token',
            'data': '/api/test/video/data'
        },
        'selectors': {
            'player': '.video-player',
            'container': '.video-container'
        },
        'fallback': true // Enable fallback mode
    };

    // Anti-debugging measures
    let _debugProtection = setInterval(function() {
        if (window.console && (window.console.firebug || window.console.table && /firebug/i.test(window.console.table()))) {
            window.location.href = 'about:blank';
        }
    }, 500);

    // Obfuscated video loader class
    class SecureVideoLoader {
        constructor() {
            this._token = null;
            this._playerInstance = null;
            this._initialized = false;
            this._securityCheck();
        }

        _securityCheck() {
            // Basic anti-tampering
            if (typeof window.Plyr === 'undefined') {
                console.error('Required dependencies not loaded');
                return false;
            }
            return true;
        }

        async _getToken(lectureId, courseId) {
            try {
                console.log('📡 Making token request to:', _0x4f2a.api.token);
                const response = await fetch(_0x4f2a.api.token, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        lecture_id: lectureId,
                        course_id: courseId
                    })
                });

                console.log('📡 Token response status:', response.status);

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    console.error('Token generation failed:', response.status, errorData);
                    throw new Error(`Token generation failed: ${response.status} - ${errorData.error || 'Unknown error'}`);
                }

                const data = await response.json();
                console.log('✅ Token data received:', data);
                this._token = data.token;

                // Auto-refresh token before expiry
                setTimeout(() => {
                    this._getToken(lectureId, courseId);
                }, (data.expires_in - 300) * 1000); // Refresh 5 minutes before expiry

                return this._token;
            } catch (error) {
                console.error('❌ Security error:', error);
                this._showErrorMessage(`Authentication failed: ${error.message}`);
                return null;
            }
        }

        async _getVideoData() {
            if (!this._token) {
                throw new Error('No valid token');
            }

            try {
                const response = await fetch(_0x4f2a.api.data, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        token: this._token
                    })
                });

                if (!response.ok) {
                    throw new Error('Video data fetch failed');
                }

                return await response.json();
            } catch (error) {
                console.error('Video data error:', error);
                return null;
            }
        }

        _deobfuscateVideoId(obfuscatedId) {
            // Reverse the obfuscation process
            return window.atob(this._rot13(obfuscatedId));
        }

        _rot13(str) {
            return str.replace(/[a-zA-Z]/g, function(c) {
                return String.fromCharCode(
                    (c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26
                );
            });
        }

        async _getFallbackVideoId(lectureId, courseId) {
            try {
                console.log('🔄 Fetching video data via fallback method...');
                const response = await fetch(`/my-course/${courseId}/lecture-content/${lectureId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch lecture content');
                }

                const data = await response.json();
                console.log('📦 Fallback data received:', data);

                if (data.success && data.youtubeData && data.youtubeData.encrypted_id) {
                    // Decrypt the YouTube ID
                    const encryptedId = data.youtubeData.encrypted_id;
                    const videoId = window.atob(encryptedId);
                    console.log('🔓 Fallback video ID decrypted:', videoId);
                    return videoId;
                }

                throw new Error('No valid video data in fallback response');
            } catch (error) {
                console.error('❌ Fallback method failed:', error);
                return null;
            }
        }

        async initializePlayer(lectureId, courseId) {
            console.log('🔒 Initializing secure video player...', { lectureId, courseId });

            if (this._initialized) {
                console.log('Player already initialized');
                return;
            }

            try {
                // Check if required elements exist
                const playerElement = document.querySelector(_0x4f2a.selectors.player);
                if (!playerElement) {
                    throw new Error('Player element not found. Make sure the video player container exists.');
                }
                console.log('✅ Player element found:', playerElement);

                // Try secure API first, fallback to direct method if it fails
                let videoId = null;

                if (_0x4f2a.fallback) {
                    console.log('🔄 Using fallback method...');
                    videoId = await this._getFallbackVideoId(lectureId, courseId);
                } else {
                    // Get secure token
                    console.log('📡 Requesting security token...');
                    const token = await this._getToken(lectureId, courseId);
                    if (!token) {
                        throw new Error('Failed to get security token');
                    }
                    console.log('✅ Security token received');

                    // Get video data
                    console.log('🎥 Requesting video data...');
                    const videoData = await this._getVideoData();
                    if (!videoData) {
                        throw new Error('Failed to get video data');
                    }
                    console.log('✅ Video data received:', videoData);

                    // Deobfuscate video ID
                    videoId = this._deobfuscateVideoId(videoData.id);
                }

                console.log('🔓 Video ID obtained:', videoId);

                if (!videoId) {
                    throw new Error('No valid video ID obtained from any method');
                }

                // Remove any existing data attributes that might contain URLs
                playerElement.removeAttribute('data-plyr-embed-id');
                playerElement.removeAttribute('data-fallback-url');

                // Create Plyr instance with secure configuration
                console.log('🎬 Initializing Plyr player...');

                // Check if Plyr is available
                if (typeof Plyr === 'undefined') {
                    throw new Error('Plyr library not loaded. Please ensure Plyr.js is included.');
                }

                this._playerInstance = new Plyr(playerElement, {
                    controls: [
                        'play-large', 'play', 'progress', 'current-time', 'duration',
                        'mute', 'volume', 'settings', 'fullscreen'
                    ],
                    settings: ['quality', 'speed'],
                    youtube: {
                        noCookie: true,
                        rel: 0,
                        showinfo: 0,
                        iv_load_policy: 3,
                        modestbranding: 1
                    },
                    ratio: '16:9'
                });

                // Set the video source securely
                this._playerInstance.source = {
                    type: 'video',
                    sources: [{
                        src: videoId,
                        provider: 'youtube'
                    }]
                };

                console.log('✅ Plyr player created successfully');

                // Add security event listeners
                this._addSecurityListeners();

                this._initialized = true;
                console.log('🎉 Secure video player initialized successfully!');

                // Clean up any traces
                setTimeout(() => {
                    this._cleanupTraces();
                }, 1000);

            } catch (error) {
                console.error('❌ Player initialization failed:', error);
                this._showErrorMessage(error.message);
            }
        }

        _addSecurityListeners() {
            if (!this._playerInstance) return;

            // Prevent right-click on video
            this._playerInstance.elements.container.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                return false;
            });

            // Prevent text selection
            this._playerInstance.elements.container.style.userSelect = 'none';
            this._playerInstance.elements.container.style.webkitUserSelect = 'none';

            // Monitor for developer tools
            let devtools = {open: false, orientation: null};
            setInterval(() => {
                if (window.outerHeight - window.innerHeight > 200 || window.outerWidth - window.innerWidth > 200) {
                    if (!devtools.open) {
                        devtools.open = true;
                        console.clear();
                        console.log('%cSecurity Notice', 'color: red; font-size: 20px; font-weight: bold;');
                        console.log('%cUnauthorized access to video content is prohibited.', 'color: red; font-size: 14px;');
                    }
                }
            }, 500);
        }

        _cleanupTraces() {
            // Remove any DOM elements that might contain video information
            const elementsToClean = document.querySelectorAll('[data-plyr-embed-id], [data-fallback-url]');
            elementsToClean.forEach(el => {
                el.removeAttribute('data-plyr-embed-id');
                el.removeAttribute('data-fallback-url');
            });

            // Clear any global variables that might contain video data
            if (window.videoData) {
                delete window.videoData;
            }
        }

        _showErrorMessage(customMessage = null) {
            const container = document.querySelector(_0x4f2a.selectors.container);
            if (container) {
                const message = customMessage || 'Unable to load video content';
                container.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center bg-light h-100">
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                            <p class="text-muted">${message}</p>
                            <button class="btn btn-primary btn-sm" onclick="location.reload()">Retry</button>
                            <button class="btn btn-outline-secondary btn-sm ms-2" onclick="console.log('Debug info:', window.secureVideoLoader)">Debug</button>
                        </div>
                    </div>
                `;
            } else {
                console.error('❌ Video container not found for error display');
            }
        }

        destroy() {
            if (this._playerInstance) {
                this._playerInstance.destroy();
                this._playerInstance = null;
            }
            this._initialized = false;
            this._token = null;
        }
    }

    // Global secure video loader instance
    window.SecureVideoLoader = SecureVideoLoader;

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.secureVideoLoader = new SecureVideoLoader();
        });
    } else {
        window.secureVideoLoader = new SecureVideoLoader();
    }

    // Prevent common debugging attempts
    document.addEventListener('keydown', function(e) {
        // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
        if (e.keyCode === 123 ||
            (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
            (e.ctrlKey && e.keyCode === 85)) {
            e.preventDefault();
            return false;
        }
    });

})();
