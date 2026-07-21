@extends('admin.layout')

@section('title', 'Homepage Sections')

@section('header', 'Manage Homepage Sections')

@section('actions')
    <a href="{{ route('admin.sections.create') }}" class="pf-btn-gold">
        <i class="fas fa-plus"></i> Add New Section
    </a>
@endsection

@section('content')
    <div class="pf-table-wrap">
        <div class="table-responsive">
            <table class="pf-table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 80px;">Order</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Background</th>
                        <th>Products Count</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr>
                            <td>
                                <span class="pf-badge-order">{{ $section->order }}</span>
                            </td>
                            <td><strong>{{ $section->title }}</strong></td>
                            <td>{{ $section->slug }}</td>
                            <td>
                                @if($section->bg_theme === 'ivory')
                                    <span class="pf-badge-gold">Ivory (Light)</span>
                                @else
                                    <span class="pf-badge-danger">Dark (Noir)</span>
                                @endif
                            </td>
                            <td>
                                <span class="pf-badge-gold">{{ $section->products_count }} Products</span>
                            </td>
                            <td>
                                @if($section->is_active)
                                    <span class="pf-badge-active">Active</span>
                                @else
                                    <span class="pf-badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $section->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <a href="{{ route('admin.sections.edit', $section->id) }}" class="pf-btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.sections.destroy', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this section?');">
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
                            <td colspan="8" class="pf-empty">
                                <i class="fas fa-list-ul"></i>
                                <p>No homepage sections found.</p>
                                <a href="{{ route('admin.sections.create') }}" class="pf-btn-gold">Create One Now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
