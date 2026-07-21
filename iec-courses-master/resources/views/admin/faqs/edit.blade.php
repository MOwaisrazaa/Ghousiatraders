@extends('admin.layout')

@section('title', 'Answer User Question')
@section('header', 'Answer Question')

@section('content')
    <a href="{{ route('admin.faqs.index') }}" class="pf-btn-gold mb-4" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; margin-bottom: 24px;">
        <i class="fas fa-arrow-left"></i> Back to Questions
    </a>

    <div style="background:rgba(10,10,10,0.82);border:1px solid rgba(212,166,88,0.16);border-radius:20px;padding:32px;">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="pf-field">
                    <span class="pf-form-label" style="display:block; margin-bottom:6px; color:#d4a658; font-weight:700;">Question Details</span>
                    <div style="display:flex; gap:10px; margin-bottom:18px;">
                        <span class="badge" style="background: {{ $faq->answer ? 'rgba(40, 167, 69, 0.15)' : 'rgba(255, 193, 7, 0.15)' }}; color: {{ $faq->answer ? '#2ecc71' : '#ffe69c' }}; border: 1px solid {{ $faq->answer ? '#28a745' : '#ffc107' }}; padding: 6px 12px; border-radius: 4px; font-size: 0.75rem;">
                            {{ $faq->answer ? 'Answered' : 'Pending' }}
                        </span>
                        <span class="badge" style="background: {{ $faq->is_published ? 'rgba(23, 162, 184, 0.15)' : 'rgba(108, 117, 125, 0.15)' }}; color: {{ $faq->is_published ? '#17a2b8' : '#dee2e6' }}; border: 1px solid {{ $faq->is_published ? '#17a2b8' : '#6c757d' }}; padding: 6px 12px; border-radius: 4px; font-size: 0.75rem;">
                            {{ $faq->is_published ? 'Published' : 'Hidden' }}
                        </span>
                    </div>
                </div>

                <div class="pf-field mb-4">
                    <span class="pf-form-label" style="display:block; margin-bottom:6px; color:#d4a658; font-weight:700;">Submitted By</span>
                    <div style="color:#f8e7d0;">
                        <strong>{{ $faq->name ?: 'Guest' }}</strong>
                        <span style="color:rgba(248,231,208,0.5); display:block; font-size:0.9rem;">{{ $faq->email ?: 'No Email Provided' }}</span>
                    </div>
                </div>

                <div class="pf-field mb-4">
                    <span class="pf-form-label" style="display:block; margin-bottom:6px; color:#d4a658; font-weight:700;">Submitted On</span>
                    <div style="color:#f8e7d0;">{{ $faq->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="pf-field mb-4" style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid rgba(212,166,88,0.1);">
                    <span class="pf-form-label" style="display:block; margin-bottom:8px; color:#d4a658; font-weight:700;">Question</span>
                    <p style="color:#f8e7d0; font-size:1.1rem; line-height:1.6; margin:0; white-space:pre-wrap;">{{ $faq->question }}</p>
                </div>
            </div>
        </div>

        <hr style="border-color:rgba(212,166,88,0.15); margin:24px 0;">

        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="pf-field">
                <label class="pf-form-label" for="answer" style="color:#d4a658; font-weight:700;">Your Answer</label>
                <textarea id="answer" name="answer" class="pf-input @error('answer') is-invalid @enderror" rows="6" placeholder="Type the answer here..." required style="background:rgba(255,255,255,0.03); border:1px solid rgba(212,166,88,0.25); border-radius:12px; color:#f8e7d0; width:100%; padding:14px; font-size:0.95rem; outline:none; resize:vertical; transition:border-color 0.2s;" onfocus="this.style.borderColor='#d4a658'" onblur="this.style.borderColor='rgba(212,166,88,0.25)'">{{ old('answer', $faq->answer) }}</textarea>
                @error('answer')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="pf-field" style="margin-top:20px; margin-bottom:28px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $faq->is_published) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:#d4a658; cursor:pointer;">
                    <label for="is_published" style="color:#f8e7d0; font-weight:600; cursor:pointer; user-select:none; margin:0;">Publish to Public FAQ Page</label>
                </div>
                <span style="display:block; color:rgba(248,231,208,0.5); font-size:0.82rem; margin-top:6px; margin-left:28px;">If checked, this Q&A will be visible publicly on the website's FAQ page.</span>
            </div>

            <div style="display:flex; gap:12px; align-items:center;">
                <button type="submit" class="pf-btn-gold" style="border:none; cursor:pointer; padding:12px 30px; border-radius:30px; font-weight:600;">
                    <i class="fas fa-save me-2"></i> Save & Update
                </button>
                <a href="{{ route('admin.faqs.index') }}" style="color:rgba(248,231,208,0.7); text-decoration:none; font-weight:600; font-size:0.9rem; margin-left:10px;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
