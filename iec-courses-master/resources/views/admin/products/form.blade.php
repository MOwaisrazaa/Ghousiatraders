@extends('admin.ghousia-layout')

@php
    $isEdit = $product->exists ?? false;
@endphp

@section('title', $isEdit ? 'Admin Dashboard - Edit Product' : 'Admin Dashboard - Add Product')

@section('content')
<style>
    .product-form-container {
        width: 100%;
        box-sizing: border-box;
    }
    
    .gt-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 28px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        margin-bottom: 24px;
    }

    .form-section-sep {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 28px 0 20px 0;
    }

    .form-section-sep span {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--gt-primary);
        white-space: nowrap;
    }

    .form-section-sep hr {
        flex: 1;
        border: none;
        border-top: 1.5px dashed var(--gt-border);
        margin: 0;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .gt-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--gt-text);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .gt-input {
        background: #fffdf9;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.85rem;
        color: var(--gt-text);
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
        min-height: 38px;
        width: 100%;
    }

    .gt-input:focus {
        border-color: var(--gt-primary);
        box-shadow: 0 0 0 3px var(--gt-primary-light);
    }

    .gt-hint {
        font-size: 0.72rem;
        color: var(--gt-text-muted);
        margin-top: 4px;
        display: block;
    }

    .gt-error {
        color: #ef4444;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 4px;
        display: block;
    }

    /* Quill light theme styling */
    .ql-toolbar.ql-snow {
        background: #fffdf9 !important;
        border: 1.5px solid var(--gt-border) !important;
        border-bottom: none !important;
        border-radius: 10px 10px 0 0 !important;
    }
    .ql-container.ql-snow {
        background: #ffffff !important;
        border: 1.5px solid var(--gt-border) !important;
        border-radius: 0 0 10px 10px !important;
        min-height: 220px;
        font-size: 0.9rem;
        color: var(--gt-text) !important;
    }
    .ql-editor { min-height: 200px; color: var(--gt-text) !important; line-height: 1.6; }

    @media (max-width: 768px) {
        .form-grid-2, .form-grid-3 {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- Quill CSS --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">

<x-admin-page-header :title="$isEdit ? 'Edit Product' : 'Add New Product'">
    <a href="{{ route('admin.products') }}" class="gt-btn-outline" style="min-height:38px;padding:0 16px;">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Back to Products
    </a>
</x-admin-page-header>

<!-- Breadcrumb bar -->
<div style="margin-bottom: 20px; font-size: 0.8rem; color: var(--gt-text-muted); font-weight: 600; display: flex; align-items: center; gap: 6px;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--gt-text-muted); text-decoration: none;">Dashboard</a>
    <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
    <a href="{{ route('admin.products') }}" style="color: var(--gt-text-muted); text-decoration: none;">Products</a>
    <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
    <span style="color: var(--gt-primary); font-weight: 700;">{{ $isEdit ? 'Edit Product' : 'Add New Product' }}</span>
</div>

<div class="product-form-container">
    <div class="gt-card">
        <form action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Section 1: Basic Information -->
            <div class="form-section-sep" style="margin-top: 0;">
                <span>Basic Information</span><hr>
            </div>

            <div class="form-grid-3">
                <div>
                    <label class="gt-label" for="name">Product Name *</label>
                    <input type="text" id="name" name="name" class="gt-input @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name) }}" required placeholder="e.g. Baby Wipes 80 Pcs">
                    @error('name')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="gt-label" for="sku">SKU Code</label>
                    <input type="text" id="sku" name="sku" class="gt-input @error('sku') is-invalid @enderror"
                        value="{{ old('sku', $product->sku) }}" placeholder="e.g. GT-P-10020">
                    @error('sku')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="gt-label" for="category_id">Category *</label>
                    <select id="category_id" name="category_id" required class="gt-input @error('category_id') is-invalid @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Section 2: Pricing & Inventory -->
            <div class="form-section-sep">
                <span>Pricing & Inventory</span><hr>
            </div>

            <div class="form-grid-3">
                <div>
                    <label class="gt-label" for="weekly_price">Regular Price (PKR) *</label>
                    <input type="number" step="0.01" id="weekly_price" name="weekly_price"
                        class="gt-input @error('weekly_price') is-invalid @enderror"
                        value="{{ old('weekly_price', $product->weekly_price ?? 1000) }}" required placeholder="e.g. 1500">
                    @error('weekly_price')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="gt-label" for="sale_price">Sale Price (PKR)</label>
                    <input type="number" step="0.01" id="sale_price" name="sale_price"
                        class="gt-input @error('sale_price') is-invalid @enderror"
                        value="{{ old('sale_price', $product->sale_price) }}" placeholder="e.g. 1290">
                    @error('sale_price')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="gt-label" for="cost_price">Cost Price (PKR)</label>
                    <input type="number" step="0.01" id="cost_price" name="cost_price"
                        class="gt-input @error('cost_price') is-invalid @enderror"
                        value="{{ old('cost_price', $product->cost_price) }}" placeholder="e.g. 900">
                    @error('cost_price')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-grid-3">
                <div>
                    <label class="gt-label" for="stock">Stock Quantity *</label>
                    <input type="number" id="stock" name="stock"
                        class="gt-input @error('stock') is-invalid @enderror"
                        value="{{ old('stock', $product->stock ?? 10) }}" required>
                    @error('stock')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="gt-label" for="low_stock_threshold">Low Stock Threshold *</label>
                    <input type="number" id="low_stock_threshold" name="low_stock_threshold"
                        class="gt-input @error('low_stock_threshold') is-invalid @enderror"
                        value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" required>
                    @error('low_stock_threshold')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="gt-label" for="status">Status *</label>
                    <select id="status" name="status" required class="gt-input @error('status') is-invalid @enderror">
                        <option value="active" @selected(old('status', $product->status) == 'active')>Active</option>
                        <option value="draft" @selected(old('status', $product->status) == 'draft')>Draft</option>
                        <option value="inactive" @selected(old('status', $product->status) == 'inactive')>Inactive</option>
                    </select>
                    @error('status')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Section 3: Media & Visibility -->
            <div class="form-section-sep">
                <span>Media & Visibility</span><hr>
            </div>

            <div class="form-grid-2">
                <div>
                    <label class="gt-label" for="image_path">Product Image</label>
                    <input type="file" id="image_path" name="image_path" class="gt-input @error('image_path') is-invalid @enderror" accept="image/*" style="padding:4px 12px;">
                    <span class="gt-hint">Accepted formats: JPG, PNG, WEBP. Maximum 4MB.</span>
                    @error('image_path')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror

                    @if($isEdit && $product->image_path)
                        <div style="margin-top:12px;">
                            <span class="gt-label">Current Image</span>
                            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}"
                                style="max-width:140px;max-height:140px;object-fit:cover;border-radius:10px;border:1.5px solid var(--gt-border);">
                        </div>
                    @endif
                </div>

                <div>
                    <label class="gt-label" for="intro_video_url">YouTube Video URL</label>
                    <input type="url" id="intro_video_url" name="intro_video_url" class="gt-input @error('intro_video_url') is-invalid @enderror"
                        value="{{ old('intro_video_url', $product->intro_video_url) }}" placeholder="e.g. https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                    <span class="gt-hint">Optional product video link.</span>
                    @error('intro_video_url')
                        <span class="gt-error">{{ $message }}</span>
                    @enderror

                    <div style="margin-top:20px;">
                        <label class="gt-label">Featured Status</label>
                        <div style="display:flex;align-items:center;gap:10px;margin-top:6px;">
                            <input type="checkbox" name="is_featured" value="1" id="is_featured" @checked(old('is_featured', $product->is_featured))>
                            <label for="is_featured" style="font-size:0.82rem;font-weight:700;color:var(--gt-text);cursor:pointer;">Display in Featured Products section</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Descriptions -->
            <div class="form-section-sep">
                <span>Product Descriptions</span><hr>
            </div>

            <div style="margin-bottom: 20px;">
                <label class="gt-label" for="description">Short Description</label>
                <textarea id="description" name="description" rows="3" class="gt-input @error('description') is-invalid @enderror"
                    style="height:auto;padding:10px 14px;" placeholder="Brief summary shown on product cards and checkout...">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <span class="gt-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label class="gt-label">Detailed Product Description</label>
                
                <textarea id="long_description_textarea" name="long_description" rows="10" class="gt-input @error('long_description') is-invalid @enderror"
                    style="height:auto;padding:10px 14px;" placeholder="Detailed product specifications, ingredients, and instructions...">{{ old('long_description', $product->long_description) }}</textarea>

                <div id="quill-editor-wrapper" style="display: none;">
                    <div id="quill-editor"></div>
                    <input type="hidden" id="long_description_input" name="long_description" value="{{ old('long_description', $product->long_description) }}">
                </div>

                @error('long_description')
                    <span class="gt-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Actions -->
            <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:32px;padding-top:20px;border-top:1.5px solid var(--gt-border);">
                <a href="{{ route('admin.products') }}" class="gt-btn-outline" style="min-height:42px;padding:0 20px;">Cancel</a>
                <button type="submit" class="gt-btn-primary" style="min-height:42px;padding:0 24px;">
                    <i data-lucide="check"></i> {{ $isEdit ? 'Update Product' : 'Save Product' }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Quill JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Quill !== 'undefined') {
            const textarea = document.getElementById('long_description_textarea');
            const quillWrapper = document.getElementById('quill-editor-wrapper');
            const quillEditor = document.getElementById('quill-editor');
            const hiddenInput = document.getElementById('long_description_input');

            textarea.style.display = 'none';
            textarea.removeAttribute('name');
            quillWrapper.style.display = 'block';

            if (textarea.value) {
                quillEditor.innerHTML = textarea.value;
            }

            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Write detailed product specifications, ingredients, and instructions...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['blockquote'],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            document.getElementById('product-form').addEventListener('submit', function () {
                const html = quill.root.innerHTML;
                hiddenInput.value = (html === '<p><br></p>' || html === '') ? '' : html;
            });
        }
    });
</script>
@endsection
