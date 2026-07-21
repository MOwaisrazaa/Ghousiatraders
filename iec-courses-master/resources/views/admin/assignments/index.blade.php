<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Admin-User Assignments</h5>
                            <a href="{{ route('admin.assignments.create') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-plus me-2"></i> Create Assignment
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Admin Filter (only for Super Admin) -->
                        @if(Auth::user()->isSuperAdmin() && count($admins) > 0)
                            <div class="mb-4">
                                <h6 class="mb-2">Filter by Admin</h6>
                                <form action="{{ route('admin.assignments.index') }}" method="GET" class="row g-3 align-items-center">
                                    <div class="col-md-4">
                                        <select name="admin_id" class="form-select">
                                            <option value="">All Admins</option>
                                            @foreach($admins as $admin)
                                                <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                                    {{ $admin->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-info">Filter</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Batch Assignment Form (Super Admin Only) -->
                        @if(Auth::user()->isSuperAdmin())
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Batch Assign Users</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.assignments.batch') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="admin_id" class="form-label">Select Admin</label>
                                                <select name="admin_id" id="admin_id" class="form-select" required>
                                                    <option value="">-- Select Admin --</option>
                                                    @foreach($admins as $admin)
                                                        <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Select Users to Assign</label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                                                    </div>
                                                                </th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($users as $user)
                                                                @if(!$user->assignedAdmin)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input user-checkbox" type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $user->name }}</td>
                                                                        <td>{{ $user->email }}</td>
                                                                    </tr>
                                                                @endif
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3" class="text-center">No unassigned users available</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Assign Selected Users</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Current Assignments -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Admin</th>
                                        <th>User</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignments as $assignment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $assignment->admin->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $assignment->admin->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $assignment->user->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $assignment->user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $assignment->created_at->format('M d, Y') }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('admin.assignments.show', $assignment) }}" class="btn btn-sm btn-info me-2">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" class="assignment-delete-form">
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
                                            <td colspan="4" class="text-center">No assignments found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Select all checkbox functionality
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });
    </script>
    <script src="{{ asset('js/admin-assignments.js') }}"></script>
    @endpush
</x-app-layout>
