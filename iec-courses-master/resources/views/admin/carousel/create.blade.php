@extends('admin.layout')

@section('header', 'Create New Banner')

@section('actions')
    <a href="{{ route('admin.banners.index') }}" class="pf-btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
@endsection

@section('content')
<style>
    /* ── Shared form styles ─────────────────────────── */
    .pf-btn-gold {
        background: linear-gradient(135deg, #d4a658, #9d6f20);
        color: #111 !important;
        font-weight: 700;
        font-size: 0.88rem;
        letter-spacing: 0.04em;
        text-decoration: none;
        padding: 11px 24px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    .pf-btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212,166,88,0.35);
        color: #000 !important;
    }
    .pf-btn-outline {
        background: transparent;
        color: rgba(248,231,208,0.85) !important;
        border: 1px solid rgba(212,166,88,0.3);
        font-size: 0.88rem;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .pf-btn-outline:hover {
        border-color: #d4a658;
        background: rgba(212,166,88,0.08);
        color: #fff !important;
    }
    .pf-btn-cancel {
        background: rgba(255,255,255,0.05);
        color: rgba(248,231,208,0.7) !important;
        border: 1px solid rgba(255,255,255,0.1);
        font-size: 0.88rem;
        text-decoration: none;
        padding: 11px 20px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .pf-btn-cancel:hover {
        background: rgba(255,255,255,0.08);
        color: #fff !important;
    }

    .pf-form-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #d4a658;
        margin-bottom: 8px;
    }
    .pf-form-label .req { color: #f07080; }
    .pf-form-label .opt { color: rgba(248,231,208,0.4); font-weight: 400; text-transform: none; letter-spacing: 0; font-size: 0.8rem; }

    .pf-input, .pf-textarea, .pf-select-field {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(212,166,88,0.22);
        border-radius: 12px;
        color: #f8e7d0;
        padding: 11px 16px;
        font-size: 0.92rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: inherit;
        box-sizing: border-box;
    }
    .pf-input::placeholder, .pf-textarea::placeholder { color: rgba(248,231,208,0.3); }
    .pf-input:focus, .pf-textarea:focus, .pf-select-field:focus {
        border-color: #d4a658;
        box-shadow: 0 0 0 3px rgba(212,166,88,0.12);
    }
    .pf-input.is-invalid, .pf-textarea.is-invalid, .pf-select-field.is-invalid {
        border-color: #f07080;
    }
    .pf-textarea { resize: vertical; min-height: 100px; }
    .pf-select-field option { background: #1a1a1a; }

    .pf-hint {
        margin-top: 6px;
        font-size: 0.78rem;
        color: rgba(248,231,208,0.45);
    }
    .pf-error {
        margin-top: 6px;
        font-size: 0.8rem;
        color: #f07080;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pf-field { margin-bottom: 22px; }

    .pf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width:640px) { .pf-row { grid-template-columns: 1fr; } }

    .pf-divider {
        border: none;
        border-top: 1px solid rgba(212,166,88,0.12);
        margin: 24px 0;
    }

    /* File input */
    .pf-file-wrapper {
        position: relative;
    }
    .pf-file-wrapper input[type="file"] {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px dashed rgba(212,166,88,0.3);
        border-radius: 12px;
        color: #f8e7d0;
        padding: 11px 16px;
        font-size: 0.9rem;
        cursor: pointer;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .pf-file-wrapper input[type="file"]:hover { border-color: #d4a658; }

    /* Toggle switch */
    .pf-switch-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        background: rgba(212,166,88,0.06);
        border: 1px solid rgba(212,166,88,0.16);
        border-radius: 14px;
    }
    .pf-switch-wrap input[type="checkbox"] {
        width: 40px;
        height: 22px;
        cursor: pointer;
        accent-color: #d4a658;
        flex-shrink: 0;
    }
    .pf-switch-label {
        font-size: 0.9rem;
        color: #f8e7d0;
        line-height: 1.4;
    }
    .pf-switch-label strong { color: #d4a658; }

    /* Sidebar info box */
    .pf-sidebar-box {
        background: rgba(212,166,88,0.06);
        border: 1px solid rgba(212,166,88,0.18);
        border-radius: 20px;
        overflow: hidden;
    }
    .pf-sidebar-box__head {
        background: linear-gradient(135deg, rgba(212,166,88,0.25), rgba(157,111,32,0.2));
        padding: 16px 20px;
        border-bottom: 1px solid rgba(212,166,88,0.18);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        font-weight: 700;
        color: #d4a658;
        letter-spacing: 0.05em;
    }
    .pf-sidebar-box__body {
        padding: 20px;
        color: rgba(248,231,208,0.75);
        font-size: 0.88rem;
        line-height: 1.65;
    }
    .pf-sidebar-box__body p { margin-bottom: 12px; }
    .pf-sidebar-box__body ul { margin: 6px 0 14px 16px; padding: 0; }
    .pf-sidebar-box__body li { margin-bottom: 5px; }
    .pf-sidebar-box__body strong { color: #d4a658; }

    .pf-page-note {
        margin-top: 16px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .pf-page-note.has-btn {
        background: rgba(79,200,100,0.1);
        border: 1px solid rgba(79,200,100,0.25);
        color: #5fcf6e;
    }
    .pf-page-note.no-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(248,231,208,0.5);
    }

    /* Image preview */
    .pf-preview-box {
        margin-top: 14px;
        border: 2px dashed rgba(212,166,88,0.3);
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 4/3;
        max-width: 320px;
        display: none;
    }
    .pf-preview-box img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .pf-img-info {
        margin-top: 14px;
        padding: 14px 16px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(212,166,88,0.12);
        border-radius: 12px;
        font-size: 0.8rem;
        color: rgba(248,231,208,0.5);
        line-height: 1.7;
    }
    .pf-img-info ul { margin: 8px 0 0 16px; padding:0; }

    /* Layout */
    .pf-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; align-items: start; }
    @media (max-width: 900px) { .pf-grid { grid-template-columns: 1fr; } }

    .pf-form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid rgba(212,166,88,0.12);
    }
</style>

<div class="pf-grid">
    {{-- Main Form --}}
    <div>
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Page --}}
            <div class="pf-field">
                <label for="page_key" class="pf-form-label">Page <span class="req">*</span></label>
                <select class="pf-select-field @error('page_key') is-invalid @enderror"
                    id="page_key" name="page_key" required onchange="toggleCtaFields(this.value)">
                    @foreach(\App\Models\CarouselSlide::pageOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('page_key', request('page_key', 'home')) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('page_key')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Choose which page this banner should appear on.</div>
                <div id="cta_page_hint" class="pf-page-note" style="display:none;"></div>
            </div>

            {{-- Eyebrow --}}
            <div class="pf-field">
                <label for="eyebrow" class="pf-form-label">Banner Eyebrow / Small Heading</label>
                <input type="text" class="pf-input @error('eyebrow') is-invalid @enderror"
                    id="eyebrow" name="eyebrow" value="{{ old('eyebrow') }}" maxlength="100"
                    placeholder="e.g., POLANI FRAGRANCE">
                @error('eyebrow')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Optional. Appears above the main title. Maximum 100 characters.</div>
            </div>

            {{-- Title --}}
            <div class="pf-field">
                <label for="title" class="pf-form-label">Banner Title <span class="req">*</span></label>
                <input type="text" class="pf-input @error('title') is-invalid @enderror"
                    id="title" name="title" value="{{ old('title') }}" maxlength="100"
                    required placeholder="e.g., Discover Our Collection">
                @error('title')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Maximum 100 characters.</div>
            </div>

            {{-- Subtitle --}}
            <div class="pf-field">
                <label for="subtitle" class="pf-form-label">Banner Text <span class="req">*</span></label>
                <textarea class="pf-textarea @error('subtitle') is-invalid @enderror"
                    id="subtitle" name="subtitle" rows="3" maxlength="500"
                    required placeholder="Describe the banner content...">{{ old('subtitle') }}</textarea>
                @error('subtitle')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Maximum 500 characters. Appears below the title.</div>
            </div>

            {{-- CTA Fields --}}
            <div id="cta_fields_wrapper">
                <div class="pf-row">
                    <div class="pf-field">
                        <label for="cta_text" class="pf-form-label" id="cta_text_label">Button 1 Text <span class="req">*</span></label>
                        <input type="text" class="pf-input @error('cta_text') is-invalid @enderror"
                            id="cta_text" name="cta_text" value="{{ old('cta_text') }}"
                            maxlength="50" placeholder="e.g., Shop Now">
                        @error('cta_text')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Maximum 50 characters.</div>
                    </div>
                    <div class="pf-field">
                        <label for="cta_url" class="pf-form-label" id="cta_url_label">Button 1 URL <span class="req">*</span></label>
                        <input type="url" class="pf-input @error('cta_url') is-invalid @enderror"
                            id="cta_url" name="cta_url" value="{{ old('cta_url') }}"
                            placeholder="https://example.com">
                        @error('cta_url')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Full URL including https://</div>
                    </div>
                </div>
                <div class="pf-row" style="margin-top: 16px;">
                    <div class="pf-field">
                        <label for="secondary_cta_text" class="pf-form-label">Button 2 Text</label>
                        <input type="text" class="pf-input @error('secondary_cta_text') is-invalid @enderror"
                            id="secondary_cta_text" name="secondary_cta_text" value="{{ old('secondary_cta_text') }}"
                            maxlength="50" placeholder="e.g., Shop Now">
                        @error('secondary_cta_text')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Optional. Maximum 50 characters.</div>
                    </div>
                    <div class="pf-field">
                        <label for="secondary_cta_url" class="pf-form-label">Button 2 URL</label>
                        <input type="url" class="pf-input @error('secondary_cta_url') is-invalid @enderror"
                            id="secondary_cta_url" name="secondary_cta_url" value="{{ old('secondary_cta_url') }}"
                            placeholder="https://example.com">
                        @error('secondary_cta_url')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Optional. Full URL including https://</div>
                    </div>
                </div>
            </div>

            <hr class="pf-divider">

            {{-- Image --}}
            <div class="pf-field">
                <label for="image" class="pf-form-label">Hero Image <span class="req">*</span></label>
                <div class="pf-file-wrapper">
                    <input type="file" id="image" name="image"
                        accept="image/jpeg,image/png,image/webp" required>
                </div>
                @error('image')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-img-info">
                    <strong style="color:#d4a658;">Image Processing:</strong>
                    <ul>
                        <li>Format: JPEG, PNG, or WebP (auto-converted to WebP)</li>
                        <li>Aspect Ratio: Auto-adjusted to <strong style="color:#d4a658;">4:3 landscape</strong></li>
                        <li>Minimum Width: 1200px recommended</li>
                        <li>Maximum File Size: 5MB (compressed to ~150-200KB WebP)</li>
                    </ul>
                </div>
                <div class="pf-preview-box" id="image-preview-container">
                    <img id="image-preview" src="" alt="Preview">
                </div>
            </div>

            {{-- Active Toggle --}}
            <div class="pf-field">
                <div class="pf-switch-wrap">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                        value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <div class="pf-switch-label">
                        <strong>Active</strong> — This banner will be visible on the website
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pf-form-actions">
                <button type="submit" class="pf-btn-gold">
                    <i class="fas fa-save"></i> Create Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="pf-btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Sidebar Tips --}}
    <div>
        <div class="pf-sidebar-box">
            <div class="pf-sidebar-box__head">
                <i class="fas fa-lightbulb"></i> Tips & Best Practices
            </div>
            <div class="pf-sidebar-box__body">
                <p><strong>Banner Order:</strong> Banners appear in the order you create them. Reorder from the list view.</p>
                <p><strong>Image Tips:</strong></p>
                <ul>
                    <li>Use high-contrast images for readability</li>
                    <li>Keep important content centered</li>
                    <li>Avoid very bright or very dark images</li>
                    <li>Test on mobile for clarity</li>
                </ul>
                <p><strong>Performance:</strong> Images are auto-optimized to WebP with responsive sizes.</p>
                <hr style="border-color:rgba(212,166,88,0.15); margin:14px 0;">
                <p style="margin-bottom:6px;"><strong>Pages with Button:</strong></p>
                <p style="color:#5fcf6e; margin-bottom:12px; font-size:0.82rem;"><i class="fas fa-check-circle"></i> Home, Cart</p>
                <p style="margin-bottom:6px;"><strong>Pages without Button:</strong></p>
                <p style="font-size:0.82rem; margin-bottom:0;">Collection, Women, Attars, Oud, Scented Candles, Product, Checkout, Contact, Track Order, Order Status, About</p>
            </div>
        </div>
    </div>
</div>

<script>
    const pagesWithButton = ['home', 'cart'];

    function toggleCtaFields(pageKey) {
        const wrapper = document.getElementById('cta_fields_wrapper');
        const ctaText = document.getElementById('cta_text');
        const ctaUrl  = document.getElementById('cta_url');
        const hint    = document.getElementById('cta_page_hint');
        const hasBtn  = pagesWithButton.includes(pageKey);

        if (hasBtn) {
            wrapper.style.display = 'block';
            ctaText.required = true;
            ctaUrl.required  = true;
            hint.className   = 'pf-page-note has-btn';
            hint.style.display = 'block';
            hint.innerHTML   = '<i class="fas fa-check-circle"></i> This page shows a button — fields are required.';
        } else {
            wrapper.style.display = 'none';
            ctaText.required = false;
            ctaUrl.required  = false;
            ctaText.value    = '';
            ctaUrl.value     = '';
            hint.className   = 'pf-page-note no-btn';
            hint.style.display = 'block';
            hint.innerHTML   = '<i class="fas fa-ban"></i> This page does not have a button.';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleCtaFields(document.getElementById('page_key').value);
    });

    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            const box = document.getElementById('image-preview-container');
            document.getElementById('image-preview').src = ev.target.result;
            box.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection
