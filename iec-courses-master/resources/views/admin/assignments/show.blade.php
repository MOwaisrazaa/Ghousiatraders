<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Assignment Details</h5>
                            <a href="{{ route('admin.assignments.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Assignments
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Admin Information</h6>
                                <div class="p-3 border rounded">
                                    <p class="mb-2"><strong>Name:</strong> {{ $assignment->admin->name }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $assignment->admin->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">User Information</h6>
                                <div class="p-3 border rounded">
                                    <p class="mb-2"><strong>Name:</strong> {{ $assignment->user->name }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $assignment->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Assignment Details</h6>
                                <div class="p-3 border rounded">
                                    <p class="mb-2"><strong>Created:</strong> {{ $assignment->created_at->format('F d, Y h:i A') }}</p>
                                    <p class="mb-0"><strong>Updated:</strong> {{ $assignment->updated_at->format('F d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back
                            </a>
                            <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this assignment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-2"></i> Remove Assignment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
