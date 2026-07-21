@extends('admin.layout')

@section('title', 'Add Navigation Page')
@section('header', 'Add Navigation Page / Link')

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
            box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
            border-radius: 8px !important;
        }
        .ql-snow .ql-tooltip input[type=text] {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(212,166,88,0.2) !important;
            color: #f8e7d0 !important;
            border-radius: 6px !important;
        }
    </style>

    <div style="background:rgba(10,10,10,0.82);border:1px solid rgba(212,166,88,0.16);border-radius:20px;padding:32px;max-width:800px;">
        <div style="display:flex;align-items:center;gap:12px;margin:0 0 28px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Page Configurations</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <form action="{{ route('admin.pages.store') }}" method="POST" id="page-form">
            @csrf
            
            <div class="pf-field">
                <label for="type" class="pf-form-label">Link Type</label>
                <select name="type" id="type" class="pf-input" onchange="toggleFields()">
                    <option value="system" {{ old('type') === 'system' ? 'selected' : '' }}>System Link (Redirects to routes like /shop, /contact, or external URL)</option>
                    <option value="custom" {{ old('type') === 'custom' ? 'selected' : '' }}>Custom Content Page (Dynamically displays custom page content)</option>
                </select>
                @error('type')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="pf-field">
                <label for="name" class="pf-form-label">Link/Page Name (Header Label)</label>
                <input type="text" class="pf-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. ABOUT US" required>
                @error('name')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Predefined link field: shown only for system link type --}}
            <div class="pf-field" id="system-link-field">
                <label for="link" class="pf-form-label">Link URL / Path</label>
                <input type="text" class="pf-input @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}" placeholder="e.g. /shop, /contact, /#signature, or https://google.com">
                <span style="font-size:0.75rem;color:rgba(248,231,208,0.45);margin-top:6px;display:block;">
                    Use relative path (e.g. <code>/shop</code>) for internal pages, or anchors (e.g. <code>/#signature</code>) for section scrolls, or full URLs for external links.
                </span>
                @error('link')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Custom page fields: shown only for custom page type --}}
            <div id="custom-page-fields" style="display:none;">
                <div class="pf-field">
                    <label for="slug" class="pf-form-label">Slug (Optional)</label>
                    <input type="text" class="pf-input @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. brand-story">
                    <span style="font-size:0.75rem;color:rgba(248,231,208,0.45);margin-top:6px;display:block;">
                        The URL path will be <code>/page/your-slug</code>. If left blank, it will be auto-generated from the page name.
                    </span>
                    @error('slug')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label class="pf-form-label">Page Content</label>
                    {{-- Fallback textarea: shown by default, hidden via JS if Quill loads successfully --}}
                    <textarea id="page_content_textarea" name="content" rows="15" class="pf-textarea @error('content') is-invalid @enderror"
                        placeholder="Write your page content here (HTML supported)...">{{ old('content') }}</textarea>

                    {{-- Quill editor container: hidden by default, shown via JS if Quill loads successfully --}}
                    <div id="quill-editor-wrapper" style="display: none;">
                        <div id="quill-editor"></div>
                        <input type="hidden" id="page_content_input" name="content" value="{{ old('content') }}">
                    </div>

                    @error('content')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label class="pf-form-label">Associate Products (Show on this page)</label>
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(212,166,88,0.2); border-radius: 12px; padding: 20px; max-height: 250px; overflow-y: auto;">
                        @forelse($products as $product)
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <input type="checkbox" name="products[]" id="product_{{ $product->id }}" value="{{ $product->id }}" {{ is_array(old('products')) && in_array($product->id, old('products')) ? 'checked' : '' }} style="accent-color:#d4a658; width:16px; height:16px; cursor:pointer;">
                                <label for="product_{{ $product->id }}" style="color: rgba(248,231,208,0.85); font-size: 0.9rem; cursor:pointer; margin-bottom:0; user-select:none;">
                                    {{ $product->name }} <span style="font-size: 0.8rem; color: rgba(212,166,88,0.6);">({{ $product->category ? $product->category->name : 'No Category' }})</span>
                                </label>
                            </div>
                        @empty
                            <div style="color: rgba(248,231,208,0.5); font-size: 0.9rem;">No products found in the database.</div>
                        @endforelse
                    </div>
                    <span style="font-size:0.75rem;color:rgba(248,231,208,0.45);margin-top:6px;display:block;">
                        Select which products will be featured and listed at the bottom of this page.
                    </span>
                </div>
            </div>

            <div class="pf-field">
                <label for="order" class="pf-form-label">Sort Order (Optional)</label>
                <input type="number" class="pf-input @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order') }}" placeholder="e.g. 9">
                @error('order')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="pf-field" style="display:flex;align-items:center;gap:10px;margin-top:20px;">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} style="accent-color:#d4a658;width:18px;height:18px;cursor:pointer;">
                <label for="is_active" class="pf-form-label" style="margin-bottom:0;cursor:pointer;user-select:none;">Visible in Storefront Header Navigation</label>
            </div>

            <div class="pf-form-actions" style="margin-top:32px;">
                <button type="submit" class="pf-btn-gold">
                    <i class="fas fa-save"></i> Save Page
                </button>
                <a href="{{ route('admin.pages.index') }}" class="pf-btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Quill JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
    <script>
        function toggleFields() {
            const type = document.getElementById('type').value;
            const systemField = document.getElementById('system-link-field');
            const customFields = document.getElementById('custom-page-fields');
            const linkInput = document.getElementById('link');
            const textareaInput = document.getElementById('page_content_textarea');

            if (type === 'system') {
                systemField.style.display = 'block';
                customFields.style.display = 'none';
                linkInput.setAttribute('required', 'required');
                textareaInput.removeAttribute('required');
            } else {
                systemField.style.display = 'none';
                customFields.style.display = 'block';
                linkInput.removeAttribute('required');
                // Standard textarea requires if no Quill, but since we update hidden input, let's keep it clean
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initial toggle
            toggleFields();

            // Check if Quill loaded successfully
            if (typeof Quill !== 'undefined') {
                const textarea = document.getElementById('page_content_textarea');
                const quillWrapper = document.getElementById('quill-editor-wrapper');
                const quillEditor = document.getElementById('quill-editor');
                const hiddenInput = document.getElementById('page_content_input');

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
                    placeholder: 'Write your page content here...',
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
                document.getElementById('page-form').addEventListener('submit', function (e) {
                    const type = document.getElementById('type').value;
                    if (type === 'custom') {
                        const html = quill.root.innerHTML;
                        const contentValue = (html === '<p><br></p>' || html === '') ? '' : html;
                        hiddenInput.value = contentValue;
                        
                        if (!contentValue) {
                            e.preventDefault();
                            alert('Page content is required for custom content pages.');
                        }
                    }
                });
            } else {
                console.warn('Quill.js CDN failed to load. Using standard textarea fallback.');
                document.getElementById('page-form').addEventListener('submit', function (e) {
                    const type = document.getElementById('type').value;
                    if (type === 'custom') {
                        const textarea = document.getElementById('page_content_textarea');
                        if (!textarea.value.trim()) {
                            e.preventDefault();
                            alert('Page content is required for custom content pages.');
                        }
                    }
                });
            }
        });
    </script>
@endsection
