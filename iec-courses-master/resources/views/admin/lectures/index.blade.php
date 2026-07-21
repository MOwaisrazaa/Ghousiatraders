@extends('admin.layout')

@section('title', 'Lectures Management')

@section('header', 'Lectures Management')

@section('actions')
    <div class="dropdown">
        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-plus"></i> Add New Lecture
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @foreach(\App\Models\Course::all() as $course)
                <li><a class="dropdown-item" href="{{ route('admin.course.create') }}?course={{ $course->id }}&type=lecture">{{ $course->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">All Lectures</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lectures as $lecture)
                            <tr>
                                <td>{{ $lecture->id }}</td>
                                <td>
                                    @if($lecture->image_path)
                                        <img src="{{ Storage::url($lecture->image_path) }}" alt="{{ $lecture->name }}" class="img-thumbnail" width="50">
                                    @else
                                        <img src="https://via.placeholder.com/50" alt="Placeholder" class="img-thumbnail" width="50">
                                    @endif
                                </td>
                                <td>{{ $lecture->name }}</td>
                                <td>
                                    @if($lecture->course)
                                        {{ $lecture->course->name }}
                                    @else
                                        <span class="badge bg-info">Standalone</span>
                                    @endif
                                </td>
                                <td>Rs {{ number_format($lecture->weekly_price, 2) }}</td>
                                <td>
                                    <a href="{{ route('lecture.detail', ['course' => $lecture->course_id ?? 0, 'lecture' => $lecture->id]) }}" class="btn btn-info btn-sm" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($lecture->course_id)
                                        <a href="{{ route('admin.course.create') }}?course={{ $lecture->course_id }}&lecture={{ $lecture->id }}&type=lecture" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.course.create') }}?lecture={{ $lecture->id }}&type=lecture&standalone=1" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $lecture->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No lectures found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $lectures->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const lectureId = this.getAttribute('data-id');

                if (confirm('Are you sure you want to delete this lecture? This action cannot be undone.')) {
                    // Submit delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/lecture/${lectureId}`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
