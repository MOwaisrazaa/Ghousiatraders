@extends('admin.layout')

@section('header', 'Footer Settings')

@section('content')

    <form action="{{ route('admin.footer.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Brand Information --}}
        <div style="display:flex;align-items:center;gap:12px;margin:0 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Brand Information</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="brand_name" class="pf-form-label">Brand Name</label>
                <input type="text" class="pf-input @error('brand_name') is-invalid @enderror" id="brand_name" name="brand_name" value="{{ old('brand_name', $footer->brand_name) }}" required>
                @error('brand_name') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
            <div class="pf-field">
                <label for="brand_tagline" class="pf-form-label">Brand Tagline</label>
                <input type="text" class="pf-input @error('brand_tagline') is-invalid @enderror" id="brand_tagline" name="brand_tagline" value="{{ old('brand_tagline', $footer->brand_tagline) }}" required>
                @error('brand_tagline') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="pf-field">
            <label for="brand_description" class="pf-form-label">Brand Description</label>
            <textarea class="pf-textarea @error('brand_description') is-invalid @enderror" id="brand_description" name="brand_description" rows="3" required>{{ old('brand_description', $footer->brand_description) }}</textarea>
            @error('brand_description') <div class="pf-error">{{ $message }}</div> @enderror
        </div>

        {{-- Social Media Links --}}
        <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Social Media Links</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="facebook_url" class="pf-form-label">Facebook URL</label>
                <input type="url" class="pf-input @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $footer->facebook_url) }}" placeholder="https://facebook.com/...">
                @error('facebook_url') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
            <div class="pf-field">
                <label for="instagram_url" class="pf-form-label">Instagram URL</label>
                <input type="url" class="pf-input @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $footer->instagram_url) }}" placeholder="https://instagram.com/...">
                @error('instagram_url') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="tiktok_url" class="pf-form-label">TikTok URL</label>
                <input type="url" class="pf-input @error('tiktok_url') is-invalid @enderror" id="tiktok_url" name="tiktok_url" value="{{ old('tiktok_url', $footer->tiktok_url) }}" placeholder="https://tiktok.com/...">
                @error('tiktok_url') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
            <div class="pf-field">
                <label for="youtube_url" class="pf-form-label">YouTube URL</label>
                <input type="url" class="pf-input @error('youtube_url') is-invalid @enderror" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $footer->youtube_url) }}" placeholder="https://youtube.com/...">
                @error('youtube_url') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="linkedin_url" class="pf-form-label">LinkedIn URL</label>
                <input type="url" class="pf-input @error('linkedin_url') is-invalid @enderror" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $footer->linkedin_url) }}" placeholder="https://linkedin.com/company/...">
                @error('linkedin_url') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Contact Information --}}
        <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Contact Information</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-field">
            <label for="address" class="pf-form-label">Address</label>
            <input type="text" class="pf-input @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $footer->address) }}" required>
            @error('address') <div class="pf-error">{{ $message }}</div> @enderror
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="email" class="pf-form-label">Email Address</label>
                <input type="email" class="pf-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $footer->email) }}" required>
                @error('email') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
            <div class="pf-field">
                <label for="phone" class="pf-form-label">Phone Number</label>
                <input type="text" class="pf-input @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $footer->phone) }}" required>
                @error('phone') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Copyright Information --}}
        <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Copyright Information</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="copyright_name" class="pf-form-label">Copyright Name</label>
                <input type="text" class="pf-input @error('copyright_name') is-invalid @enderror" id="copyright_name" name="copyright_name" value="{{ old('copyright_name', $footer->copyright_name) }}" required>
                @error('copyright_name') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
            <div class="pf-field">
                <label for="copyright_url" class="pf-form-label">Copyright URL</label>
                <input type="url" class="pf-input @error('copyright_url') is-invalid @enderror" id="copyright_url" name="copyright_url" value="{{ old('copyright_url', $footer->copyright_url) }}" required>
                @error('copyright_url') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="pf-field">
            <label for="footer_text" class="pf-form-label">Footer Text</label>
            <textarea class="pf-textarea @error('footer_text') is-invalid @enderror" id="footer_text" name="footer_text" rows="2" required>{{ old('footer_text', $footer->footer_text) }}</textarea>
            @error('footer_text') <div class="pf-error">{{ $message }}</div> @enderror
        </div>

        <div class="pf-form-actions">
            <button type="submit" class="pf-btn-gold">
                <i class="fas fa-save me-2"></i> Save Footer Settings
            </button>
            <a href="{{ route('admin.dashboard') }}" class="pf-btn-cancel">Cancel</a>
        </div>

    </form>

    {{-- Live Preview --}}
    <div style="margin-top:40px;background:rgba(255,255,255,0.03);border:1px solid rgba(212,166,88,0.2);border-radius:16px;padding:28px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
            <i class="fas fa-eye" style="color:#d4a658;"></i>
            <h5 style="margin:0;color:#d4a658;font-weight:700;letter-spacing:0.05em;">Live Preview</h5>
        </div>
        <p style="color:rgba(248,231,208,0.55);font-size:0.85rem;margin-bottom:20px;">Preview how your footer will appear on the website</p>

        <div style="background:rgba(0,0,0,0.35);border:1px solid rgba(212,166,88,0.12);border-radius:12px;padding:24px;">
            <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:16px;">
                <div>
                    <h6 style="color:#d4a658;font-weight:700;margin-bottom:8px;">{{ old('brand_name', $footer->brand_name) }}</h6>
                    <small style="color:rgba(248,231,208,0.55);">{{ old('brand_tagline', $footer->brand_tagline) }}</small>
                    <p style="color:rgba(248,231,208,0.65);font-size:0.85rem;margin-top:8px;margin-bottom:0;">{{ old('brand_description', $footer->brand_description) }}</p>
                </div>
                <div>
                    <h6 style="color:#d4a658;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;margin-bottom:12px;">Contact Info</h6>
                    <ul style="list-style:none;padding:0;margin:0;color:rgba(248,231,208,0.7);font-size:0.85rem;">
                        <li style="margin-bottom:8px;"><i class="fas fa-map-marker-alt me-2" style="color:#d4a658;"></i>{{ old('address', $footer->address) }}</li>
                        <li style="margin-bottom:8px;"><i class="fas fa-envelope me-2" style="color:#d4a658;"></i>{{ old('email', $footer->email) }}</li>
                        <li><i class="fas fa-phone me-2" style="color:#d4a658;"></i>{{ old('phone', $footer->phone) }}</li>
                    </ul>
                </div>
            </div>
            <div style="border-top:1px solid rgba(212,166,88,0.12);padding-top:14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                <span style="color:rgba(248,231,208,0.5);font-size:0.8rem;">&copy; {{ date('Y') }} <strong style="color:#d4a658;">{{ old('copyright_name', $footer->copyright_name) }}</strong>. All Rights Reserved. | Powered by <a href="https://snipezon.com" target="_blank" rel="noopener noreferrer" style="color:#d4a658;">snipezon.com</a></span>
                <span style="color:rgba(248,231,208,0.5);font-size:0.8rem;">{{ old('footer_text', $footer->footer_text) }}</span>
            </div>
        </div>
    </div>

@endsection
