@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth-components.css') }}">
@endsection

@extends('admin.layout')

@section('title', 'Edit User')

@section('header', 'Edit User')

@section('actions')
    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <!-- User Information Form -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update' , $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{$user->name}}" required>
                                @error('name')
                                    <span class="text-danger text-sm">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{$user->email}}" required>
                                @error('email')
                                    <span class="text-danger text-sm">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <small class="text-muted">(leave blank to keep current password)</small>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="Enter new password..." autocomplete="new-password">
                            <div class="input-group-append">
                                <span class="input-group-text input-group-cursor-pointer" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        @error('password')
                            <span class="text-danger text-sm">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Roles <span class="text-danger">*</span></label>
                        <div class="border p-3 rounded">
                            @php
                                // Get current user role IDs safely
                                try {
                                    $userRoleIds = $user->roles->pluck('id')->toArray();
                                } catch (\Exception $e) {
                                    $userRoleIds = [];
                                }
                            @endphp

                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->id }}"
                                           id="role-{{ $role->id }}"
                                           @if(in_array($role->id, $userRoleIds)) checked @endif>
                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <span class="text-danger text-sm">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>User ID:</strong> {{ $user->id }}
                </div>
                <div class="mb-2">
                    <strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}
                </div>
                <div class="mb-2">
                    <strong>Current Roles:</strong>
                    <div class="mt-1">
                        @foreach($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function(e) {
                const password = document.getElementById('password');
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle between eye and eye-slash icons
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    });
</script>
@endsection
