@extends('admin.layout')

@section('title', 'Edit Homepage Section')

@section('header', 'Edit Homepage Section')

@section('content')
    <div style="background:rgba(10,10,10,0.82);border:1px solid rgba(212,166,88,0.16);border-radius:20px;padding:32px;max-width:760px;margin:0 auto;">
        <div style="display:flex;align-items:center;gap:12px;margin:0 0 28px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Edit Section Details</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <form action="{{ route('admin.sections.update', $section->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="pf-row-3">
                <div class="pf-field">
                    <label for="title" class="pf-form-label">Section Title <span class="req">*</span></label>
                    <input type="text" class="pf-input @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $section->title) }}" required>
                    @error('title')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label for="order" class="pf-form-label">Display Order <span class="req">*</span></label>
                    <input type="number" class="pf-input @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $section->order) }}" min="0" required>
                    @error('order')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pf-field">
                    <label for="bg_theme" class="pf-form-label">Background Theme <span class="req">*</span></label>
                    <select class="pf-select-field @error('bg_theme') is-invalid @enderror" id="bg_theme" name="bg_theme" required>
                        <option value="dark" {{ old('bg_theme', $section->bg_theme) == 'dark' ? 'selected' : '' }}>Dark (Noir)</option>
                        <option value="ivory" {{ old('bg_theme', $section->bg_theme) == 'ivory' ? 'selected' : '' }}>Ivory (Light)</option>
                    </select>
                    @error('bg_theme')
                        <div class="pf-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pf-field" style="margin-bottom: 24px;">
                <div class="pf-switch-wrap">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $section->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                    <label class="pf-switch-label" for="is_active">
                        <strong>Active Status</strong><br>
                        Toggle whether this section will be visible on the homepage storefront.
                    </label>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
                <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Select Products</span>
                <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
            </div>

            <div class="pf-field">
                <p class="pf-hint" style="margin-bottom: 12px;">Choose which products will be featured in this section:</p>
                <div style="max-height: 280px; overflow-y: auto; background: rgba(255,255,255,0.03); border: 1px solid rgba(212,166,88,0.22); border-radius: 12px; padding: 18px;">
                    @forelse($products as $product)
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" id="product_{{ $product->id }}"
                                   {{ (is_array(old('products')) && in_array($product->id, old('products'))) || (!is_array(old('products')) && in_array($product->id, $selectedProductIds)) ? 'checked' : '' }}
                                   style="accent-color: #d4a658; cursor: pointer; width: 18px; height: 18px;">
                            <label for="product_{{ $product->id }}" style="color: #f8e7d0; cursor: pointer; font-size: 0.92rem; margin: 0; display: flex; justify-content: space-between; width: 100%;">
                                <span>{{ $product->name }}</span>
                                <span style="color: #d4a658; font-weight: 600;">Rs {{ number_format($product->weekly_price) }}</span>
                            </label>
                        </div>
                    @empty
                        <p class="pf-empty" style="padding: 20px 0; font-size: 0.9rem;">No products available. Please create products first.</p>
                    @endforelse
                </div>
                @error('products')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="pf-form-actions">
                <button type="submit" class="pf-btn-gold">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.sections.index') }}" class="pf-btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection
