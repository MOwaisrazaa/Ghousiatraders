@if (session('error'))
    <div class="section-container" style="padding-top: 20px; padding-bottom: 10px;">
        <div style="background-color: #FDF2F2; border: 1px solid #FBD5D5; color: #9B1C1C; padding: 14px 18px; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; gap: 10px; font-size: 0.95rem;">
            <i data-lucide="alert-circle" style="width: 20px; height: 20px; flex-shrink: 0; color: #E02424;"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif

@if (session('success'))
    <div class="section-container" style="padding-top: 20px; padding-bottom: 10px;">
        <div style="background-color: #EDFDFD; border: 1px solid #C2F3F3; color: #036B6B; padding: 14px 18px; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; gap: 10px; font-size: 0.95rem;">
            <i data-lucide="check-circle" style="width: 20px; height: 20px; flex-shrink: 0; color: #0D9488;"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="section-container" style="padding-top: 20px; padding-bottom: 10px;">
        <div style="background-color: #FDF2F2; border: 1px solid #FBD5D5; color: #9B1C1C; padding: 14px 18px; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; display: flex; flex-direction: column; gap: 6px; font-size: 0.95rem;">
            <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i data-lucide="alert-circle" style="width: 20px; height: 20px; flex-shrink: 0; color: #E02424;"></i>
                <span>Please fix the following issues:</span>
            </div>
            <ul style="margin: 0 0 0 30px; padding: 0; list-style-type: disc; line-height: 1.5;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
