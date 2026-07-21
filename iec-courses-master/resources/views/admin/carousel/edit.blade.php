@extends('admin.layout')

@section('header', 'Edit Banner')

@section('actions')
    <a href="{{ route('admin.banners.index') }}" class="pf-btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
@endsection

@section('content')
<style>
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
    .pf-btn-cancel:hover { background: rgba(255,255,255,0.08); color: #fff !important; }

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
    .pf-input.is-invalid, .pf-textarea.is-invalid, .pf-select-field.is-invalid { border-color: #f07080; }
    .pf-textarea { resize: vertical; min-height: 100px; }
    .pf-select-field option { background: #1a1a1a; }

    .pf-hint { margin-top: 6px; font-size: 0.78rem; color: rgba(248,231,208,0.45); }
    .pf-error { margin-top: 6px; font-size: 0.8rem; color: #f07080; display: flex; align-items: center; gap: 5px; }
    .pf-field { margin-bottom: 22px; }
    .pf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width:640px) { .pf-row { grid-template-columns: 1fr; } }
    .pf-divider { border: none; border-top: 1px solid rgba(212,166,88,0.12); margin: 24px 0; }

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

    .pf-switch-wrap {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 18px;
        background: rgba(212,166,88,0.06);
        border: 1px solid rgba(212,166,88,0.16);
        border-radius: 14px;
    }
    .pf-switch-wrap input[type="checkbox"] { width: 40px; height: 22px; cursor: pointer; accent-color: #d4a658; flex-shrink: 0; }
    .pf-switch-label { font-size: 0.9rem; color: #f8e7d0; line-height: 1.4; }
    .pf-switch-label strong { color: #d4a658; }

    /* Current image preview */
    .pf-current-img-wrap {
        padding: 16px;
        background: rgba(212,166,88,0.06);
        border: 1px solid rgba(212,166,88,0.16);
        border-radius: 14px;
        display: inline-flex;
        flex-direction: column;
        gap: 10px;
    }
    .pf-current-img {
        width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid rgba(212,166,88,0.2);
    }

    /* Sidebar */
    .pf-sidebar-box {
        background: rgba(212,166,88,0.06);
        border: 1px solid rgba(212,166,88,0.18);
        border-radius: 20px;
        overflow: hidden;
    }
    .pf-sidebar-box__head {
        background: linear-gradient(135deg, rgba(212,166,88,0.2), rgba(157,111,32,0.15));
        padding: 16px 20px;
        border-bottom: 1px solid rgba(212,166,88,0.18);
        display: flex; align-items: center; gap: 10px;
        font-size: 0.9rem; font-weight: 700;
        color: #d4a658; letter-spacing: 0.05em;
    }
    .pf-sidebar-box__body { padding: 20px; color: rgba(248,231,208,0.75); font-size: 0.88rem; line-height: 1.65; }
    .pf-sidebar-box__body p { margin-bottom: 10px; }
    .pf-sidebar-box__body strong { color: #d4a658; }

    .pf-meta-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(212,166,88,0.1);
        font-size: 0.85rem;
        color: rgba(248,231,208,0.7);
    }
    .pf-meta-row:last-child { border-bottom: none; }
    .pf-meta-row strong { color: #d4a658; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; }

    .pf-badge-active { background: rgba(79,200,100,0.15); color: #5fcf6e; border: 1px solid rgba(79,200,100,0.3); padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
    .pf-badge-inactive { background: rgba(255,255,255,0.06); color: rgba(248,231,208,0.5); border: 1px solid rgba(255,255,255,0.1); padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
    .pf-badge-order { background: rgba(212,166,88,0.15); color: #d4a658; border: 1px solid rgba(212,166,88,0.3); padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }

    .pf-page-note { margin-top: 10px; padding: 9px 13px; border-radius: 10px; font-size: 0.8rem; font-weight: 600; }
    .pf-page-note.has-btn { background: rgba(79,200,100,0.1); border: 1px solid rgba(79,200,100,0.25); color: #5fcf6e; }
    .pf-page-note.no-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(248,231,208,0.5); }

    .pf-preview-box { margin-top: 14px; border: 2px dashed rgba(212,166,88,0.3); border-radius: 14px; overflow: hidden; aspect-ratio: 4/3; max-width: 280px; display: none; }
    .pf-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .pf-img-info { margin-top: 14px; padding: 14px 16px; background: rgba(255,255,255,0.03); border: 1px solid rgba(212,166,88,0.12); border-radius: 12px; font-size: 0.8rem; color: rgba(248,231,208,0.5); line-height: 1.7; }
    .pf-img-info ul { margin: 8px 0 0 16px; padding: 0; }

    .pf-grid { display: grid; grid-template-columns: 1fr 320px; gap: 28px; align-items: start; }
    @media (max-width: 900px) { .pf-grid { grid-template-columns: 1fr; } }
    .pf-form-actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-top: 28px; padding-top: 22px; border-top: 1px solid rgba(212,166,88,0.12); }
</style>

<div class="pf-grid">
    {{-- Main Form --}}
    <div>
        <form action="{{ route('admin.banners.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Page --}}
            <div class="pf-field">
                <label for="page_key" class="pf-form-label">Page <span class="req">*</span></label>
                <select class="pf-select-field @error('page_key') is-invalid @enderror"
                    id="page_key" name="page_key" required onchange="toggleCtaFields(this.value)">
                    @foreach(\App\Models\CarouselSlide::pageOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('page_key', $slide->page_key) === $value ? 'selected' : '' }}>{{ $label }}</option>
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
                    id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $slide->eyebrow) }}" maxlength="100"
                    placeholder="e.g., POLANI FRAGRANCE">
                @error('eyebrow')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Optional. Appears above the main title. Maximum 100 characters.</div>
            </div>

            {{-- Title --}}
            <div class="pf-field">
                <label for="title" class="pf-form-label">Banner Title <span class="req">*</span></label>
                <input type="text" class="pf-input @error('title') is-invalid @enderror"
                    id="title" name="title" value="{{ old('title', $slide->title) }}"
                    maxlength="100" required placeholder="e.g., Discover Our Collection">
                @error('title')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Maximum 100 characters.</div>
            </div>

            {{-- Subtitle --}}
            <div class="pf-field">
                <label for="subtitle" class="pf-form-label">Banner Text <span class="req">*</span></label>
                <textarea class="pf-textarea @error('subtitle') is-invalid @enderror"
                    id="subtitle" name="subtitle" rows="3" maxlength="500"
                    required>{{ old('subtitle', $slide->subtitle) }}</textarea>
                @error('subtitle')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Maximum 500 characters.</div>
            </div>

            {{-- CTA Fields --}}
            <div id="cta_fields_wrapper">
                <div class="pf-row">
                    <div class="pf-field">
                        <label for="cta_text" class="pf-form-label">Button 1 Text <span class="req">*</span></label>
                        <input type="text" class="pf-input @error('cta_text') is-invalid @enderror"
                            id="cta_text" name="cta_text"
                            value="{{ old('cta_text', $slide->cta_text) }}"
                            maxlength="50" placeholder="e.g., Shop Now">
                        @error('cta_text')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Maximum 50 characters.</div>
                    </div>
                    <div class="pf-field">
                        <label for="cta_url" class="pf-form-label">Button 1 URL <span class="req">*</span></label>
                        <input type="url" class="pf-input @error('cta_url') is-invalid @enderror"
                            id="cta_url" name="cta_url"
                            value="{{ old('cta_url', $slide->cta_url) }}"
                            placeholder="https://example.com">
                        @error('cta_url')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="pf-row" style="margin-top: 16px;">
                    <div class="pf-field">
                        <label for="secondary_cta_text" class="pf-form-label">Button 2 Text</label>
                        <input type="text" class="pf-input @error('secondary_cta_text') is-invalid @enderror"
                            id="secondary_cta_text" name="secondary_cta_text"
                            value="{{ old('secondary_cta_text', $slide->secondary_cta_text) }}"
                            maxlength="50" placeholder="e.g., Shop Now">
                        @error('secondary_cta_text')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Optional. Maximum 50 characters.</div>
                    </div>
                    <div class="pf-field">
                        <label for="secondary_cta_url" class="pf-form-label">Button 2 URL</label>
                        <input type="url" class="pf-input @error('secondary_cta_url') is-invalid @enderror"
                            id="secondary_cta_url" name="secondary_cta_url"
                            value="{{ old('secondary_cta_url', $slide->secondary_cta_url) }}"
                            placeholder="https://example.com">
                        @error('secondary_cta_url')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <hr class="pf-divider">

            {{-- Current Image --}}
            <div class="pf-field">
                <label class="pf-form-label">Current Image</label>
                <div class="pf-current-img-wrap">
                    <img src="{{ $slide->getImagePath('800w') }}" alt="{{ $slide->title }}" class="pf-current-img">
                    <span style="font-size:0.75rem; color:rgba(248,231,208,0.4);">{{ $slide->image_name }}</span>
                </div>
            </div>

            {{-- Replace Image --}}
            <div class="pf-field">
                <label for="image" class="pf-form-label">Replace Image <span class="opt">(Optional)</span></label>
                <div class="pf-file-wrapper">
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                </div>
                @error('image')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-img-info">
                    <strong style="color:#d4a658;">Image Processing:</strong>
                    <ul>
                        <li>Format: JPEG, PNG, or WebP (auto-converted to WebP)</li>
                        <li>Aspect Ratio: Auto-adjusted to <strong style="color:#d4a658;">4:3 landscape</strong></li>
                        <li>Maximum File Size: 5MB | Leave empty to keep current image</li>
                    </ul>
                </div>
                <div class="pf-preview-box" id="image-preview-container">
                    <img id="image-preview" src="" alt="Preview">
                </div>
            </div>

            {{-- Active Toggle --}}
            <div class="pf-field">
                <div class="pf-switch-wrap">
                    <input type="checkbox" id="is_active" name="is_active"
                        value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}>
                    <div class="pf-switch-label">
                        <strong>Active</strong> — This banner will be visible on the website
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pf-form-actions">
                <button type="submit" class="pf-btn-gold">
                    <i class="fas fa-save"></i> Update Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="pf-btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Sidebar Info --}}
    <div>
        <div class="pf-sidebar-box">
            <div class="pf-sidebar-box__head">
                <i class="fas fa-info-circle"></i> Banner Information
            </div>
            <div class="pf-sidebar-box__body">
                <div class="pf-meta-row">
                    <strong>Order</strong>
                    <span class="pf-badge-order"># {{ $slide->order }}</span>
                </div>
                <div class="pf-meta-row">
                    <strong>Status</strong>
                    @if($slide->is_active)
                        <span class="pf-badge-active">Active</span>
                    @else
                        <span class="pf-badge-inactive">Inactive</span>
                    @endif
                </div>
                <div class="pf-meta-row">
                    <strong>Created</strong>
                    <span style="font-size:0.8rem;">{{ $slide->created_at->format('M d, Y') }}</span>
                </div>
                <div class="pf-meta-row" style="border-bottom:none;">
                    <strong>Updated</strong>
                    <span style="font-size:0.8rem;">{{ $slide->updated_at->format('M d, Y') }}</span>
                </div>

                @if($slide->creator)
                <p style="margin-top:12px; font-size:0.82rem; color:rgba(248,231,208,0.5);">
                    Created by <strong>{{ $slide->creator->name }}</strong>
                </p>
                @endif

                <hr style="border-color:rgba(212,166,88,0.15); margin:14px 0;">
                <p style="margin-bottom:0; font-size:0.82rem;"><strong>Tip:</strong> Reorder banners by dragging from the list view.</p>
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
