@extends('admin.layout')

@section('title', 'Categories')

@section('header', 'Manage Categories')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="pf-btn-gold">
        <i class="fas fa-plus"></i> Add New Category
    </a>
@endsection

@section('content')
    <div class="pf-table-wrap">
        <div class="table-responsive">
            <table class="pf-table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products Count</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->courses_count }}</td>
                            <td>{{ $category->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="pf-btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pf-btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="pf-empty">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
