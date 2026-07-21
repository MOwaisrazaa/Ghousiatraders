@extends('admin.layout')

@section('title', 'Device Management')

@section('header', 'Device Management')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">Device Management</h5>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                        <span class="alert-text">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                        <span class="alert-text">{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="alert alert-info mx-4" role="alert">
                    <h4 class="alert-heading">Device Restrictions</h4>
                    <p>For security reasons, users can only use one primary device (the device they initially registered with) and up to 3 different IP addresses.</p>
                    <hr>
                    <p class="mb-0">As an administrator, you can monitor and manage device access here.</p>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-trash-alt me-1"></i> Remove individual devices |
                            <i class="fas fa-redo-alt me-1"></i> Reset all device restrictions for a user
                        </small>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="row mx-4 mb-3">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Users</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $devices->groupBy('user_id')->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                        <i class="fas fa-laptop text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Devices</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $devices->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="fas fa-globe text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Unique IPs</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $devices->pluck('ip_address')->unique()->count() }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    User
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Device
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    IP Address
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Last Login
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $device->user->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $device->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="ps-4">
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            @if($device->device_type === 'Mobile')
                                                <i class="fas fa-mobile-alt text-success me-3"></i>
                                            @elseif($device->device_type === 'Tablet')
                                                <i class="fas fa-tablet-alt text-warning me-3"></i>
                                            @elseif($device->device_type === 'Desktop')
                                                <i class="fas fa-desktop text-primary me-3"></i>
                                            @else
                                                <i class="fas fa-question text-secondary me-3"></i>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $device->device_name }}</h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <span class="badge bg-{{ $device->device_type === 'Desktop' ? 'primary' : ($device->device_type === 'Mobile' ? 'success' : 'warning') }}">
                                                    {{ $device->device_type }}
                                                </span>
                                                {{ $device->browser }} on {{ $device->platform }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $device->ip_address }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <p class="text-sm font-weight-bold mb-0">{{ $device->last_login_at->diffForHumans() }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ $device->last_login_at->format('M d, Y H:i') }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($device->is_primary)
                                        <span class="badge badge-sm bg-gradient-success">Primary Device</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">Secondary Device</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        @if(!$device->is_primary)
                                            <form action="{{ route('devices.destroy', $device->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger text-gradient px-2 mb-0"
                                                        onclick="return confirm('Are you sure you want to remove this device?');">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Reset all devices for this user button --}}
                                        <form action="{{ route('devices.reset.user', $device->user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-warning text-gradient px-2 mb-0"
                                                    onclick="return confirm('Are you sure you want to reset ALL device restrictions for {{ $device->user->name }}? This will remove all their devices and allow them to login from any device.');">
                                                <i class="fas fa-redo-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @if($device->is_primary)
                                        <div class="text-xs text-secondary mt-1">Primary Device</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            @if($devices->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">No devices found</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
