@extends('admin.layout')

@section('title', 'Edit Category')

@section('header', 'Edit Category')

@section('content')
    <div style="background:rgba(10,10,10,0.82);border:1px solid rgba(212,166,88,0.16);border-radius:20px;padding:32px;max-width:560px;">
        <div style="display:flex;align-items:center;gap:12px;margin:0 0 28px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Category Details</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="pf-field">
                <label for="name" class="pf-form-label">Category Name</label>
                <input type="text" class="pf-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="pf-form-actions">
                <button type="submit" class="pf-btn-gold">
                    <i class="fas fa-save"></i> Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="pf-btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
@endsection
