@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Account Usages</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.accounting.usages.create') }}" class="btn btn-primary">Record Usage</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Account</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Requested By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usages as $usage)
                        <tr>
                            <td>#{{ $usage->id }}</td>
                            <td>{{ ucfirst($usage->account_name) }}</td>
                            <td>{{ ucfirst($usage->usage_category) }}</td>
                            <td><strong>Rs {{ number_format($usage->amount, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $usage->status === 'pending' ? 'warning' : ($usage->status === 'approved' ? 'info' : 'success') }}">
                                    {{ ucfirst($usage->status) }}
                                </span>
                            </td>
                            <td>{{ $usage->requestedBy->name }}</td>
                            <td>{{ $usage->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($usage->status === 'pending')
                                        <form action="{{ route('admin.accounting.usages.approve', $usage) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this usage?')">Approve</button>
                                        </form>
                                    @elseif($usage->status === 'approved')
                                        <form action="{{ route('admin.accounting.usages.complete', $usage) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Complete this usage?')">Complete</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $usage->id }}">Details</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Details Modal -->
                        <div class="modal fade" id="detailsModal{{ $usage->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Usage Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Account:</strong> {{ ucfirst($usage->account_name) }}</p>
                                        <p><strong>Category:</strong> {{ ucfirst($usage->usage_category) }}</p>
                                        <p><strong>Amount:</strong> Rs {{ number_format($usage->amount, 2) }}</p>
                                        <p><strong>Description:</strong> {{ $usage->description }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($usage->status) }}</p>
                                        <p><strong>Requested By:</strong> {{ $usage->requestedBy->name }}</p>
                                        @if($usage->approvedBy)
                                            <p><strong>Approved By:</strong> {{ $usage->approvedBy->name }}</p>
                                            <p><strong>Approved At:</strong> {{ $usage->approved_at->format('M d, Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No usages found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $usages->links() }}
    </div>
</div>
@endsection
