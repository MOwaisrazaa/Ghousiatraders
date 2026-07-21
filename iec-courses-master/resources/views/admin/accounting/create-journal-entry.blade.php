@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Create Journal Entry</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.accounting.journal-entries.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="entry_type" class="form-label">Entry Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('entry_type') is-invalid @enderror" id="entry_type" name="entry_type" required>
                                <option value="">Select Type</option>
                                <option value="cash_deposit" {{ old('entry_type') === 'cash_deposit' ? 'selected' : '' }}>Cash Deposit</option>
                                <option value="bank_deposit" {{ old('entry_type') === 'bank_deposit' ? 'selected' : '' }}>Bank Deposit</option>
                                <option value="cash_withdrawal" {{ old('entry_type') === 'cash_withdrawal' ? 'selected' : '' }}>Cash Withdrawal</option>
                                <option value="bank_withdrawal" {{ old('entry_type') === 'bank_withdrawal' ? 'selected' : '' }}>Bank Withdrawal</option>
                            </select>
                            @error('entry_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_from" class="form-label">From Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('account_from') is-invalid @enderror" id="account_from" name="account_from" required>
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account }}" {{ old('account_from') === $account ? 'selected' : '' }}>{{ ucfirst($account) }}</option>
                                @endforeach
                            </select>
                            @error('account_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="account_to" class="form-label">To Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('account_to') is-invalid @enderror" id="account_to" name="account_to" required>
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account }}" {{ old('account_to') === $account ? 'selected' : '' }}>{{ ucfirst($account) }}</option>
                                @endforeach
                            </select>
                            @error('account_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" step="0.01" min="0.01" required value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number (Optional)</label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" id="reference_number" name="reference_number" value="{{ old('reference_number') }}">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Entry</button>
                            <a href="{{ route('admin.accounting.journal-entries') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
