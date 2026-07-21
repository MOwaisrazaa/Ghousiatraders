@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Journal Entries</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.accounting.journal-entries.create') }}" class="btn btn-primary">Create Entry</a>
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
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr>
                            <td>#{{ $entry->id }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $entry->entry_type)) }}</td>
                            <td>{{ ucfirst($entry->account_from) }}</td>
                            <td>{{ ucfirst($entry->account_to) }}</td>
                            <td><strong>Rs {{ number_format($entry->amount, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $entry->status === 'pending' ? 'warning' : ($entry->status === 'verified' ? 'info' : 'success') }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                            <td>{{ $entry->reference_number ?? '-' }}</td>
                            <td>{{ $entry->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($entry->status === 'pending')
                                        <form action="{{ route('admin.accounting.journal-entries.verify', $entry) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm" onclick="return confirm('Verify this entry?')">Verify</button>
                                        </form>
                                    @elseif($entry->status === 'verified')
                                        <form action="{{ route('admin.accounting.journal-entries.complete', $entry) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Complete this entry?')">Complete</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $entry->id }}">Details</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Details Modal -->
                        <div class="modal fade" id="detailsModal{{ $entry->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Entry Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $entry->entry_type)) }}</p>
                                        <p><strong>From Account:</strong> {{ ucfirst($entry->account_from) }}</p>
                                        <p><strong>To Account:</strong> {{ ucfirst($entry->account_to) }}</p>
                                        <p><strong>Amount:</strong> Rs {{ number_format($entry->amount, 2) }}</p>
                                        <p><strong>Description:</strong> {{ $entry->description }}</p>
                                        <p><strong>Reference Number:</strong> {{ $entry->reference_number ?? 'N/A' }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($entry->status) }}</p>
                                        <p><strong>Created By:</strong> {{ $entry->createdBy->name }}</p>
                                        @if($entry->verifiedBy)
                                            <p><strong>Verified By:</strong> {{ $entry->verifiedBy->name }}</p>
                                            <p><strong>Verified At:</strong> {{ $entry->verified_at->format('M d, Y H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No journal entries found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $entries->links() }}
    </div>
</div>
@endsection
