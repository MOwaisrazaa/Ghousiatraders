@extends('admin.layout')

@section('title', 'Blog Management')

@section('header', 'Blogs')

@section('actions')
    <a href="{{ route('admin.blogs.create') }}" class="pf-btn-gold">
        <i class="fas fa-plus"></i> Add New Blog
    </a>
@endsection

@section('content')
    <div class="pf-table-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px 14px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">All Blog Posts</span>
            <span class="pf-badge-page">{{ $blogs->total() }} total</span>
        </div>
        <div class="table-responsive">
            <table class="pf-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cover Image</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $blog)
                        <tr>
                            <td>{{ $blog->id }}</td>
                            <td style="width:72px;">
                                @if($blog->image_path)
                                    <img src="{{ asset($blog->image_path) }}"
                                        alt="{{ $blog->title }}"
                                        style="width:60px;height:60px;object-fit:cover;border-radius:10px;border:1px solid rgba(212,166,88,0.25);">
                                @else
                                    <div style="width:60px;height:60px;border-radius:10px;border:1px dashed rgba(212,166,88,0.25);display:flex;align-items:center;justify-content:center;background:rgba(212,166,88,0.05);color:rgba(212,166,88,0.4);font-size:0.8rem;">
                                        No Image
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $blog->title }}</strong></td>
                            <td><small>{{ $blog->slug }}</small></td>
                            <td>{{ $blog->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <a href="{{ route('polani.blog.detail', $blog->slug) }}" class="pf-btn-edit" style="background:rgba(212,166,88,0.12);color:#d4a658;border:1px solid rgba(212,166,88,0.3);" target="_blank" title="View Storefront">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="pf-btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('Delete this blog post?');">
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
                            <td colspan="6" class="pf-empty">No blogs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pf-pagination mt-4" style="padding:16px 24px;">
            {{ $blogs->links() }}
        </div>
    </div>
@endsection
