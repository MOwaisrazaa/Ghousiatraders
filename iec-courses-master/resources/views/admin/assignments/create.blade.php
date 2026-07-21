<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Create Admin-User Assignment</h5>
                            <a href="{{ route('admin.assignments.index') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Assignments
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

                        <form action="{{ route('admin.assignments.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="admin_id" class="form-label">Select Admin</label>
                                @if(Auth::user()->isSuperAdmin())
                                    <select name="admin_id" id="admin_id" class="form-select @error('admin_id') is-invalid @enderror" required>
                                        <option value="">-- Select Admin --</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }} ({{ $admin->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('admin_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }} ({{ Auth::user()->email }})" disabled>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select User</label>
                                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- Select User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Create Assignment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
