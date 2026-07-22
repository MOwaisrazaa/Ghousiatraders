@props(['title' => 'No Items Found', 'message' => 'Try adjusting your search filters or check back later.', 'icon' => 'package-open'])

<div class="empty-state-container" style="text-align: center; padding: 60px 20px; background-color: #FAF6F0; border-radius: 12px; border: 1px dashed #E5D5C5; max-width: 500px; margin: 40px auto; font-family: 'Plus Jakarta Sans', sans-serif;">
    <div style="width: 64px; height: 64px; background-color: #FFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: 1px solid #FAF6F0;">
        <i data-lucide="{{ $icon }}" style="width: 32px; height: 32px; color: #C68C2E;"></i>
    </div>
    <h3 style="font-size: 1.4rem; color: #5C3E21; margin: 0 0 10px; font-weight: 700; font-family: 'Lora', serif;">{{ $title }}</h3>
    <p style="color: #8C7B6B; font-size: 0.95rem; line-height: 1.6; margin: 0 0 25px;">{{ $message }}</p>
    <a href="{{ route('polani.collection') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
        Continue Shopping <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
    </a>
</div>
