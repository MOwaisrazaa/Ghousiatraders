@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Account Transfers</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.accounting.transfers.create') }}" class="btn btn-primary">Create Transfer</a>
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
                        <th>From Account</th>
                        <th>To Account</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Requested By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                        <tr>
                            <td>#{{ $transfer->id }}</td>
                            <td>{{ ucfirst($transfer->from_account) }}</td>
                            <td>{{ ucfirst($transfer->to_account) }}</td>
                            <td><strong>Rs {{ number_format($transfer->amount, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $transfer->status === 'pending' ? 'warning' : ($transfer->status === 'approved' ? 'info' : ($transfer->status === 'completed' ? 'success' : 'danger')) }}">
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>
                            <td>{{ $transfer->requestedBy->name }}</td>
                            <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($transfer->status === 'pending')
                                        <form action="{{ route('admin.accounting.transfers.approve', $transfer) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this transfer?')">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $transfer->id }}">Reject</button>
                                    @elseif($transfer->status === 'approved')
                                        <form action="{{ route('admin.accounting.transfers.complete', $transfer) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Complete this transfer?')">Complete</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $transfer->id }}">Details</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Details Modal -->
                        <div class="modal fade" id="detailsModal{{ $transfer->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Transfer Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Amount:</strong> Rs {{ number_format($transfer->amount, 2) }}</p>
                                        <p><strong>From:</strong> {{ ucfirst($transfer->from_account) }}</p>
                                        <p><strong>To:</strong> {{ ucfirst($transfer->to_account) }}</p>
                                        <p><strong>Reason:</strong> {{ $transfer->reason }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($transfer->status) }}</p>
                                        <p><strong>Requested By:</strong> {{ $transfer->requestedBy->name }}</p>
                                        @if($transfer->approvedBy)
                                            <p><strong>Approved By:</strong> {{ $transfer->approvedBy->name }}</p>
                                            <p><strong>Approval Notes:</strong> {{ $transfer->approval_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $transfer->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.accounting.transfers.reject', $transfer) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Transfer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="reason" class="form-label">Reason for Rejection</label>
                                                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No transfers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $transfers->links() }}
    </div>
</div>
@endsection
