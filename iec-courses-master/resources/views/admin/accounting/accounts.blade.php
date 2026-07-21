@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Manage Accounts</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.accounting.accounts.create') }}" class="btn btn-primary">Create Account</a>
        </div>
    </div>

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

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Account Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td>#{{ $account->id }}</td>
                            <td><strong>{{ ucfirst($account->name) }}</strong></td>
                            <td>{{ $account->description ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $account->is_active ? 'success' : 'secondary' }}">
                                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $account->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <form action="{{ route('admin.accounting.accounts.toggle', $account) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $account->is_active ? 'warning' : 'success' }} btn-sm">
                                            {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.accounting.accounts.delete', $account) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this account?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No accounts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Default Accounts Info -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Default Accounts</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">The following are default accounts that are commonly used:</p>
                    <ul>
                        <li><strong>main</strong> - Main operating account</li>
                        <li><strong>marketing</strong> - Marketing department account</li>
                        <li><strong>operations</strong> - Operations department account</li>
                        <li><strong>development</strong> - Development department account</li>
                        <li><strong>infrastructure</strong> - Infrastructure account</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
