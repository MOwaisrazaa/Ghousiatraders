@extends('admin.layout')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Admin Permissions Management</h6>
                    <p class="text-sm">Configure which pages regular admins can access. Super Admins automatically have access to all pages.</p>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($admins->isEmpty())
                            <div class="alert alert-info m-3">
                                No regular admin users found. Only regular admins can have their permissions configured.
                            </div>
                        @else
                            <div class="accordion m-3" id="adminPermissionsAccordion">
                                @foreach($admins as $admin)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $admin->id }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $admin->id }}" aria-expanded="false"
                                                aria-controls="collapse{{ $admin->id }}">
                                                {{ $admin->name }} ({{ $admin->email }})
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $admin->id }}" class="accordion-collapse collapse"
                                            aria-labelledby="heading{{ $admin->id }}" data-bs-parent="#adminPermissionsAccordion">
                                            <div class="accordion-body">
                                                <form action="{{ route('admin.permissions.update') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="admin_id" value="{{ $admin->id }}">

                                                    <div class="table-responsive">
                                                        <table class="table align-items-center mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Page</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Access</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($availablePages as $page => $label)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex px-2 py-1">
                                                                                <div class="d-flex flex-column justify-content-center">
                                                                                    <h6 class="mb-0 text-sm">{{ $label }}</h6>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check form-switch">
                                                                                <input type="hidden" name="permissions[{{ $page }}]" value="0">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                    name="permissions[{{ $page }}]" value="1"
                                                                                    {{ $admin->permissions()->where('page', $page)->where('is_allowed', true)->exists() ? 'checked' : '' }}>
                                                                                <span class="small text-muted">
                                                                                    {{ $admin->permissions()->where('page', $page)->where('is_allowed', true)->exists() ? 'Enabled' : 'Disabled' }}
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
