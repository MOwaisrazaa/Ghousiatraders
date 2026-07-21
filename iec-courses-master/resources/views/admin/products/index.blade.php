@extends('admin.layout')

@section('title', 'Products Management')

@section('header', 'Products')

@section('actions')
    <a href="{{ route('admin.products.create') }}" class="pf-btn-gold">
        <i class="fas fa-plus"></i> Add New Product
    </a>
@endsection

@section('content')
    <div class="pf-table-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px 14px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">All Products</span>
            <span class="pf-badge-page">{{ $products->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="pf-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td style="width:72px;">
                                <img src="{{ $product->image_path ? asset($product->image_path) : 'https://via.placeholder.com/80x80?text=No+Image' }}"
                                    alt="{{ $product->name }}"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:10px;border:1px solid rgba(212,166,88,0.25);">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name ?? 'Uncategorized' }}</td>
                            <td>Rs {{ number_format((float) $product->weekly_price, 0) }}</td>
                            <td>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <a href="{{ route('admin.products.show', $product) }}" class="pf-btn-edit" style="background:rgba(212,166,88,0.12);color:#d4a658;border:1px solid rgba(212,166,88,0.3);" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="pf-btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pf-btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="pf-empty">No products found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pf-pagination mt-4" style="padding:16px 24px;">
            {{ $products->links() }}
        </div>
    </div>
@endsection
