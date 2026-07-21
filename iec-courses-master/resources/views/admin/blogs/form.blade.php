@extends('admin.layout')

@php
    $isEdit = $blog->exists ?? false;
@endphp

@section('title', $isEdit ? 'Edit Blog' : 'Add Blog')
@section('header', $isEdit ? 'Edit Blog' : 'Add Blog')

@section('content')
    {{-- Quill CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">

    <style>
        /* ── Quill dark theme override ── */
        .ql-toolbar.ql-snow {
            background: rgba(20,16,8,0.9) !important;
            border: 1px solid rgba(212,166,88,0.25) !important;
            border-bottom: none !important;
            border-radius: 12px 12px 0 0 !important;
        }
        .ql-container.ql-snow {
            background: rgba(15,12,5,0.8) !important;
            border: 1px solid rgba(212,166,88,0.25) !important;
            border-top: none !important;
            border-radius: 0 0 12px 12px !important;
            min-height: 350px;
            font-size: 0.95rem;
            color: #f8e7d0 !important;
        }
        .ql-editor { min-height: 330px; color: #f8e7d0 !important; line-height: 1.7; }
        .ql-editor.ql-blank::before { color: rgba(248,231,208,0.3) !important; font-style: italic; }
        .ql-toolbar .ql-stroke { stroke: rgba(212,166,88,0.7) !important; }
        .ql-toolbar .ql-fill  { fill:   rgba(212,166,88,0.7) !important; }
        .ql-toolbar .ql-picker-label { color: rgba(212,166,88,0.8) !important; }
        .ql-toolbar button:hover .ql-stroke,
        .ql-toolbar button.ql-active .ql-stroke { stroke: #d4a658 !important; }
        .ql-toolbar button:hover .ql-fill,
        .ql-toolbar button.ql-active .ql-fill   { fill:   #d4a658 !important; }
        .ql-toolbar .ql-picker-options {
            background: #1a1208 !important;
            border: 1px solid rgba(212,166,88,0.2) !important;
            border-radius: 8px !important;
        }
        .ql-toolbar .ql-picker-item { color: #f8e7d0 !important; }
        .ql-toolbar .ql-picker-item:hover { background: rgba(212,166,88,0.1) !important; }
        .ql-snow .ql-tooltip {
            background: #1a1208 !important;
            border: 1px solid rgba(212,166,88,0.25) !important;
            color: #f8e7d0 !important;
            border-radius: 8px !important;
        }
        .ql-snow .ql-tooltip input[type=text] {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(212,166,88,0.2) !important;
            color: #f8e7d0 !important;
            border-radius: 6px !important;
        }
        .ql-snow .ql-tooltip a.ql-action,
        .ql-snow .ql-tooltip a.ql-remove { color: #d4a658 !important; }

        .pf-hint-box {
            background: rgba(212,166,88,0.06);
            border: 1px solid rgba(212,166,88,0.2);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.82rem;
            color: rgba(248,231,208,0.6);
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .pf-hint-box i { color: #d4a658; margin-top: 2px; flex-shrink: 0; }
    </style>

    <div style="background:rgba(10,10,10,0.82);border:1px solid rgba(212,166,88,0.16);border-radius:20px;padding:32px;">
        <form action="{{ $isEdit ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" id="blog-form">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="pf-field">
                <label class="pf-form-label" for="title">Blog Title</label>
                <input type="text" id="title" name="title" class="pf-input @error('title') is-invalid @enderror"
                    value="{{ old('title', $blog->title) }}" placeholder="e.g., The Secret of Oud Woods" required>
                @error('title')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="pf-row">
                <div class="pf-field">
                    <label class="pf-form-label" for="slug">Slug (Optional)</label>
                    <input type="text" id="slug" name="slug" class="pf-input @error('slug') is-invalid @enderror"
                        value="{{ old('slug', $blog->slug) }}" placeholder="e.g., secret-of-oud-woods (auto-generates if empty)">
                    @error('slug')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label class="pf-form-label" for="image_path">Cover Image</label>
                    <input type="file" id="image_path" name="image_path" class="pf-input @error('image_path') is-invalid @enderror" accept="image/*">
                    <span class="pf-hint">Accepted: JPG, PNG, WEBP. Max 4MB.</span>
                    @error('image_path')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @if($isEdit && $blog->image_path)
                <div class="pf-field" style="margin-top: -10px; margin-bottom: 22px;">
                    <label class="pf-form-label">Current Cover Image</label>
                    <div>
                        <img src="{{ asset($blog->image_path) }}" alt="{{ $blog->title }}"
                            style="max-width:240px;max-height:160px;object-fit:cover;border-radius:12px;border:1px solid rgba(212,166,88,0.25);">
                    </div>
                </div>
            @endif

            <div class="pf-hint-box">
                <i class="fas fa-info-circle"></i>
                <span>
                    Use formatting tools below to design your blog post content. Heading tags, lists, bold text, links, and blockquotes are fully supported storefront-side.
                </span>
            </div>

            <div class="pf-field">
                <label class="pf-form-label">Blog Content</label>
                
                {{-- Fallback textarea: shown by default, hidden via JS if Quill loads successfully --}}
                <textarea id="blog_content_textarea" name="content" rows="18" class="pf-textarea @error('content') is-invalid @enderror"
                    placeholder="Write your blog post content here..." required>{{ old('content', $blog->content) }}</textarea>

                {{-- Quill editor container: hidden by default, shown via JS if Quill loads successfully --}}
                <div id="quill-editor-wrapper" style="display: none;">
                    <div id="quill-editor"></div>
                    <input type="hidden" id="blog_content_input" name="content" value="{{ old('content', $blog->content) }}">
                </div>

                @error('content')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Actions ── --}}
            <div class="pf-form-actions" style="margin-top:32px;">
                <button type="submit" class="pf-btn-gold" id="save-btn">
                    <i class="fas fa-save"></i> {{ $isEdit ? 'Update Post' : 'Publish Post' }}
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="pf-btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Quill JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if Quill loaded successfully
            if (typeof Quill !== 'undefined') {
                const textarea = document.getElementById('blog_content_textarea');
                const quillWrapper = document.getElementById('quill-editor-wrapper');
                const quillEditor = document.getElementById('quill-editor');
                const hiddenInput = document.getElementById('blog_content_input');

                // Hide the fallback textarea
                textarea.style.display = 'none';
                textarea.removeAttribute('required');
                textarea.removeAttribute('name'); // Remove name so it doesn't submit

                // Show the Quill wrapper
                quillWrapper.style.display = 'block';

                // Set initial content in Quill editor container
                if (textarea.value) {
                    quillEditor.innerHTML = textarea.value;
                }

                // Initialize Quill
                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Write your story...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'indent': '-1' }, { 'indent': '+1' }],
                            ['blockquote', 'code-block'],
                            ['link'],
                            ['clean']
                        ]
                    }
                });

                // Sync Quill HTML to the hidden input on submit
                document.getElementById('blog-form').addEventListener('submit', function () {
                    const html = quill.root.innerHTML;
                    hiddenInput.value = (html === '<p><br></p>' || html === '') ? '' : html;
                });
            } else {
                console.warn('Quill.js CDN failed to load. Using standard textarea fallback.');
            }
        });
    </script>
@endsection
