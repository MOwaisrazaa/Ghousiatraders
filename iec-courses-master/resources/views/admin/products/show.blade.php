@extends('admin.layout')

@section('title', 'View Product')
@section('header', 'View Product')

@section('actions')
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i> Edit Product
    </a>
@endsection

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="{{ $product->image_path ? asset($product->image_path) : 'https://via.placeholder.com/500x500?text=No+Image' }}"
                        alt="{{ $product->name }}"
                        class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-md-8">
                    <h2 class="mb-2">{{ $product->name }}</h2>
                    <p class="text-muted mb-3">Category: {{ $product->category?->name ?? 'Uncategorized' }}</p>
                    <div class="mb-3">
                        <strong>Price:</strong> Rs {{ number_format((float) $product->weekly_price, 0) }}
                    </div>
                    <div class="mb-3">
                        <strong>Slug:</strong> {{ $product->slug }}
                    </div>
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p class="mt-2">{{ $product->description ?: 'No description added.' }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products') }}" class="btn btn-secondary">Back</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
