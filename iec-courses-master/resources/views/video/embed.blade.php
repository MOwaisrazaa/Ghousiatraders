<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Video Player</title>
    <meta name="referrer" content="no-referrer">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://cdn.plyr.io; script-src 'self' 'unsafe-inline' https://cdn.plyr.io; style-src 'self' 'unsafe-inline' https://cdn.plyr.io; img-src 'self' data: https:; font-src 'self' data:;">

    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #000;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .plyr {
            width: 100%;
            height: 100%;
        }

        /* Watermark styles */
        .watermark-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10;
            overflow: hidden;
        }

        .watermark-text {
            position: absolute;
            color: rgba(255, 255, 255, 0.3);
            font-family: Arial, sans-serif;
            font-size: 14px;
            transform: rotate(-45deg);
            white-space: nowrap;
            pointer-events: none;
            user-select: none;
            animation: moveWatermark 30s linear infinite;
        }

        .dynamic-watermark {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-family: Arial, sans-serif;
            font-size: 12px;
            z-index: 100;
            pointer-events: none;
            animation: fadeInOut 5s ease-in-out infinite;
        }

        @keyframes moveWatermark {
            0% { transform: translate(-50%, -50%) rotate(-45deg); }
            100% { transform: translate(50%, 50%) rotate(-45deg); }
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.7; }
        }

        .copy-protection-notice {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 30px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 5;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="video-container">
        <div id="player" data-plyr-provider="" data-plyr-embed-id=""></div>
    </div>

    <div class="watermark-container" id="watermarkContainer"></div>
    <div class="dynamic-watermark">Protected Content - {{ auth()->user()->email }}</div>
    <div class="copy-protection-notice">Content is protected</div>
    <div class="video-overlay"></div>

    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

    <script>
        // Obfuscated initialization using a self-executing function
        (function() {
            'use strict';

            // Basic string obfuscation
            const _0x5f2a=['application/json','token','preventDefault','log','error','keydown','ctrlKey','key','s','p','u','I','shiftKey','copy','contextmenu','dragstart','selectstart','mousedown','touchstart'];

            // Security initialization
            function initSecurity() {
                const events = [_0x5f2a[14],_0x5f2a[15],_0x5f2a[16],_0x5f2a[13]];
                events.forEach(event => {
                    document.addEventListener(event, e => {
                        e[_0x5f2a[2]]();
                        showProtectionNotice();
                        return false;
                    });
                });

                // Keyboard shortcuts prevention
                document.addEventListener(_0x5f2a[5], e => {
                    if (e[_0x5f2a[6]] && (_0x5f2a[8] === e[_0x5f2a[7]] ||
                        _0x5f2a[9] === e[_0x5f2a[7]] ||
                        _0x5f2a[10] === e[_0x5f2a[7]] ||
                        (e[_0x5f2a[12]] && _0x5f2a[11] === e[_0x5f2a[7]]))) {
                        e[_0x5f2a[2]]();
                        showProtectionNotice();
                        return false;
                    }
                });
            }

            // Initialize watermarks
            function initWatermarks() {
                const container = document.getElementById('watermarkContainer');
                const email = '{{ auth()->user()->email }}';
                const timestamp = new Date().toISOString();

                // Create watermark pattern
                for(let i = 0; i < 10; i++) {
                    for(let j = 0; j < 10; j++) {
                        const watermark = document.createElement('div');
                        watermark.className = 'watermark-text';
                        watermark.textContent = `${email} - ${timestamp}`;
                        watermark.style.top = `${i * 100}px`;
                        watermark.style.left = `${j * 200}px`;
                        container.appendChild(watermark);
                    }
                }
            }

            // Show protection notice
            function showProtectionNotice() {
                const notice = document.querySelector('.copy-protection-notice');
                notice.style.opacity = '1';
                setTimeout(() => notice.style.opacity = '0', 2000);
            }

            // Initialize video player
            function initPlayer(videoData) {
                const player = new Plyr('#player', {
                    controls: ['play', 'progress', 'current-time', 'mute', 'volume'],
                    youtube: {
                        noCookie: true,
                        rel: 0,
                        showinfo: 0,
                        iv_load_policy: 3,
                        modestbranding: 1
                    },
                    keyboard: { focused: false, global: false }
                });

                // Set up player attributes
                const playerElement = document.getElementById('player');
                playerElement.setAttribute('data-plyr-provider', videoData.provider);
                playerElement.setAttribute('data-plyr-embed-id', videoData.embedId);

                // Additional player security
                player.on('ready', () => {
                    const iframe = document.querySelector('iframe');
                    if (iframe) {
                        iframe.setAttribute('sandbox', 'allow-same-origin allow-scripts allow-presentation');
                    }
                });
            }

            // Main initialization
            document.addEventListener('DOMContentLoaded', function() {
                initSecurity();
                initWatermarks();

                // Fetch video data
                fetch('/video/get-embed-url?token={{ $token }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': _0x5f2a[0],
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    initPlayer(data);
                })
                .catch(error => {
                    console[_0x5f2a[4]]('Error:', error);
                    document.querySelector('.video-container').innerHTML =
                        '<div class="video-error-message">Video unavailable</div>';
                });
            });

            // Override console functions
            const consoleProperties = ['log', 'debug', 'info', 'warn', 'error', 'assert', 'dir', 'dirxml',
                'trace', 'group', 'groupCollapsed', 'groupEnd', 'time', 'timeEnd', 'profile', 'profileEnd', 'count'];

            consoleProperties.forEach(prop => {
                console[prop] = function() { return undefined; };
            });

            // Detect DevTools
            setInterval(() => {
                const widthThreshold = window.outerWidth - window.innerWidth > 160;
                const heightThreshold = window.outerHeight - window.innerHeight > 160;
                if (widthThreshold || heightThreshold) {
                    showProtectionNotice();
                }
            }, 1000);
        })();
    </script>
</body>
</html>
