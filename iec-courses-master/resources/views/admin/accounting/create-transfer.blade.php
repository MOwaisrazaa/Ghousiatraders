@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Create Transfer</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.accounting.transfers.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" step="0.01" min="0.01" required value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="from_account" class="form-label">From Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('from_account') is-invalid @enderror" id="from_account" name="from_account" required>
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account }}" {{ old('from_account') === $account ? 'selected' : '' }}>{{ ucfirst($account) }}</option>
                                @endforeach
                            </select>
                            @error('from_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="to_account" class="form-label">To Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('to_account') is-invalid @enderror" id="to_account" name="to_account" required>
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account }}" {{ old('to_account') === $account ? 'selected' : '' }}>{{ ucfirst($account) }}</option>
                                @endforeach
                            </select>
                            @error('to_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="4" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Transfer</button>
                            <a href="{{ route('admin.accounting.transfers') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
