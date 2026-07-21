<!-- resources/views/partials/custom-video-controls.blade.php -->

<div class="video-controls-container">
    <div class="video-progress">
        <div class="video-progress-filled"></div>
    </div>
    <div class="video-controls">
        <button class="video-button play-pause">
            <i class="fas fa-play"></i>
        </button>
        <div class="video-time">
            <span class="current-time">0:00</span> / <span class="duration">0:00</span>
        </div>
        <div class="video-volume">
            <button class="video-button volume-btn">
                <i class="fas fa-volume-up"></i>
            </button>
            <div class="volume-slider">
                <div class="volume-filled"></div>
            </div>
        </div>
        <button class="video-button fullscreen">
            <i class="fas fa-expand"></i>
        </button>
    </div>
</div>
