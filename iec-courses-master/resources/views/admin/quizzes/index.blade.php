<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Quiz Management</h1>
                <p class="mb-0">Manage lecture quizzes for your courses</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create New Quiz
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Quizzes</h6>
            </div>
            <div class="card-body">
                @if($quizzes->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                        <p class="mb-0">No quizzes found. Click the "Create New Quiz" button to add a quiz.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="quizzesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Lecture</th>
                                    <th>Course</th>
                                    <th>Points</th>
                                    <th>Pass Score</th>
                                    <th>Required</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $quiz->id }}</td>
                                        <td>{{ $quiz->title }}</td>
                                        <td>{{ $quiz->lecture->name }}</td>
                                        <td>{{ $quiz->lecture->course->name ?? 'Standalone' }}</td>
                                        <td>{{ $quiz->total_points }}</td>
                                        <td>{{ $quiz->passing_score }}%</td>
                                        <td>
                                            @if($quiz->is_required)
                                                <span class="badge bg-success">Required</span>
                                            @else
                                                <span class="badge bg-secondary">Optional</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.quizzes.attempts', $quiz) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </a>
                                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="d-inline" id="delete-form-{{ $quiz->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $quiz->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $quizzes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(quizId) {
            if (confirm('Are you sure you want to delete this quiz? All associated questions and attempts will also be deleted.')) {
                document.getElementById(`delete-form-${quizId}`).submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any plugins or additional functionality here
        });
    </script>
    @endpush
</x-app-layout> 