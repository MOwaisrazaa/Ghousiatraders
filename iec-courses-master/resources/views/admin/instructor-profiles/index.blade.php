@extends('admin.layout')

@section('title', 'Instructor Profiles')

@section('header')
    Instructor Profiles
@endsection

@section('actions')
    <a href="{{ route('admin.instructor-profiles.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add New Instructor
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Expertise</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructorProfiles as $instructor)
                            <tr>
                                <td>
                                    @if($instructor->image_path)
                                        @php
                                            // Try both storage paths to ensure image shows up
                                            $imagePath = $instructor->image_path;
                                            $storageUrl = Storage::url($imagePath);
                                            $assetUrl = asset('storage/' . $imagePath);
                                        @endphp
                                        <img src="{{ $storageUrl }}"
                                            data-fallback-src="{{ $assetUrl }}"
                                            alt="{{ $instructor->name }}"
                                            class="rounded-circle instructor-profile-img"
                                            width="40" height="40">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white instructor-avatar-placeholder">
                                            {{ strtoupper(substr($instructor->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $instructor->name }}</td>
                                <td>{{ $instructor->title }}</td>
                                <td>
                                    @php
                                        $expertiseArray = is_string($instructor->expertise) ? 
                                            array_slice(explode(',', $instructor->expertise), 0, 3) : [];
                                    @endphp
                                    
                                    @foreach($expertiseArray as $expertise)
                                        <span class="badge bg-primary me-1">{{ trim($expertise) }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($instructor->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.instructor-profiles.edit', $instructor) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.instructor-profiles.destroy', $instructor) }}" method="POST" class="d-inline instructor-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No instructor profiles found.</p>
                                    <a href="{{ route('admin.instructor-profiles.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Add Instructor
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $instructorProfiles->links() }}
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-instructor-profiles.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/admin-instructor-profiles.js') }}"></script>
@endpush