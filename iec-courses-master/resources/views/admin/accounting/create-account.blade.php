@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Create New Account</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.accounting.accounts.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Account Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g., marketing, operations, development">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Use lowercase letters and underscores only</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                            <a href="{{ route('admin.accounting.accounts') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Account Naming Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li>✓ Use lowercase letters</li>
                        <li>✓ Use underscores for spaces</li>
                        <li>✓ Be descriptive</li>
                        <li>✓ Keep it short</li>
                        <li>✗ Don't use special characters</li>
                        <li>✗ Don't use spaces</li>
                    </ul>
                    <hr>
                    <p><strong>Examples:</strong></p>
                    <ul class="list-unstyled">
                        <li>marketing</li>
                        <li>operations</li>
                        <li>development</li>
                        <li>customer_support</li>
                        <li>sales_team</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
