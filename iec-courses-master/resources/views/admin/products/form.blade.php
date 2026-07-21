@extends('admin.layout')

@php
    $isEdit = $product->exists ?? false;
@endphp

@section('title', $isEdit ? 'Edit Product' : 'Add Product')
@section('header', $isEdit ? 'Edit Product' : 'Add Product')

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
            min-height: 260px;
            font-size: 0.95rem;
            color: #f8e7d0 !important;
        }
        .ql-editor { min-height: 240px; color: #f8e7d0 !important; line-height: 1.7; }
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

        /* section label */
        .pf-section-sep {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 32px 0 20px;
        }
        .pf-section-sep span {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #d4a658;
            white-space: nowrap;
        }
        .pf-section-sep hr {
            flex: 1;
            border: none;
            border-top: 1px solid rgba(212,166,88,0.15);
        }

        .pf-hint-box {
            background: rgba(212,166,88,0.06);
            border: 1px solid rgba(212,166,88,0.2);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.82rem;
            color: rgba(248,231,208,0.6);
            margin-bottom: 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .pf-hint-box i { color: #d4a658; margin-top: 2px; flex-shrink: 0; }
    </style>

    <div style="background:rgba(10,10,10,0.82);border:1px solid rgba(212,166,88,0.16);border-radius:20px;padding:32px;">
        <form action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            {{-- ── Basic Info ── --}}
            <div class="pf-section-sep">
                <span>Basic Information</span><hr>
            </div>

            <div class="pf-row">
                <div class="pf-field">
                    <label class="pf-form-label" for="name">Product Name</label>
                    <input type="text" id="name" name="name" class="pf-input @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label class="pf-form-label" for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="pf-select-field @error('category_id') is-invalid @enderror">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pf-row">
                <div class="pf-field">
                    <label class="pf-form-label" for="weekly_price">Price (Rs)</label>
                    <input type="number" step="0.01" id="weekly_price" name="weekly_price"
                        class="pf-input @error('weekly_price') is-invalid @enderror"
                        value="{{ old('weekly_price', $product->weekly_price ?? 1000) }}" required>
                    @error('weekly_price')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label class="pf-form-label" for="image_path">Product Image</label>
                    <input type="file" id="image_path" name="image_path" class="pf-input @error('image_path') is-invalid @enderror" accept="image/*">
                    <span class="pf-hint">Accepted: JPG, PNG, WEBP. Max 2MB.</span>
                    @error('image_path')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pf-row">
                <div class="pf-field">
                    <label class="pf-form-label" for="intro_video_url">YouTube Video URL</label>
                    <input type="url" id="intro_video_url" name="intro_video_url" class="pf-input @error('intro_video_url') is-invalid @enderror"
                        value="{{ old('intro_video_url', $product->intro_video_url) }}" placeholder="e.g. https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                    <span class="pf-hint">Optional: Enter a YouTube video URL for this product.</span>
                    @error('intro_video_url')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @if($isEdit && $product->image_path)
                <div class="pf-field">
                    <label class="pf-form-label">Current Image</label>
                    <div>
                        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                            style="max-width:180px;max-height:180px;object-fit:cover;border-radius:12px;border:1px solid rgba(212,166,88,0.25);">
                    </div>
                </div>
            @endif

            {{-- ── Short Description ── --}}
            <div class="pf-section-sep">
                <span>Short Description</span><hr>
            </div>

            <div class="pf-field">
                <label class="pf-form-label" for="description">Short Description</label>
                <textarea id="description" name="description" rows="3" class="pf-textarea @error('description') is-invalid @enderror"
                    placeholder="Brief summary shown below the product title...">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Long Description (Rich Text) ── --}}
            <div class="pf-section-sep">
                <span>Long Description</span><hr>
            </div>

            <div class="pf-hint-box">
                <i class="fas fa-info-circle"></i>
                <span>
                    Yahan <strong style="color:#d4a658;">Amazon / Daraz</strong> jesi detailed description likhen — fragrance notes, ingredients, usage tips, performance details, etc. Bold, italic, headings, bullet points sab use kar sakte hain.
                </span>
            </div>

            <div class="pf-field">
                <label class="pf-form-label">Detailed Product Description</label>
                
                {{-- Fallback textarea: shown by default, hidden via JS if Quill loads successfully --}}
                <textarea id="long_description_textarea" name="long_description" rows="12" class="pf-textarea @error('long_description') is-invalid @enderror"
                    placeholder="Yahan poori detail likhen... Misaal ke taur par:
- Fragrance Family
- Top / Heart / Base Notes
- Performance (Longevity, Sillage)
- Usage Tips
- Ingredients">{{ old('long_description', $product->long_description) }}</textarea>

                {{-- Quill editor container: hidden by default, shown via JS if Quill loads successfully --}}
                <div id="quill-editor-wrapper" style="display: none;">
                    <div id="quill-editor"></div>
                    <input type="hidden" id="long_description_input" name="long_description" value="{{ old('long_description', $product->long_description) }}">
                </div>

                @error('long_description')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- ── Actions ── --}}
            <div class="pf-form-actions" style="margin-top:32px;">
                <button type="submit" class="pf-btn-gold" id="save-btn">
                    <i class="fas fa-save"></i> {{ $isEdit ? 'Update Product' : 'Save Product' }}
                </button>
                <a href="{{ route('admin.products') }}" class="pf-btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Quill JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if Quill loaded successfully
            if (typeof Quill !== 'undefined') {
                const textarea = document.getElementById('long_description_textarea');
                const quillWrapper = document.getElementById('quill-editor-wrapper');
                const quillEditor = document.getElementById('quill-editor');
                const hiddenInput = document.getElementById('long_description_input');

                // Hide the fallback textarea
                textarea.style.display = 'none';
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
                    placeholder: 'Yahan poori detail likhen...\n\nMisaal ke taur par:\n- Fragrance Family\n- Top / Heart / Base Notes\n- Performance (Longevity, Sillage)\n- Usage Tips\n- Ingredients\n- Why choose this fragrance?',
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
                document.getElementById('product-form').addEventListener('submit', function () {
                    const html = quill.root.innerHTML;
                    hiddenInput.value = (html === '<p><br></p>' || html === '') ? '' : html;
                });
            } else {
                console.warn('Quill.js CDN failed to load. Using standard textarea fallback.');
            }
        });
    </script>
@endsection
