@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Record Account Usage</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.accounting.usages.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="account_name" class="form-label">Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('account_name') is-invalid @enderror" id="account_name" name="account_name" required>
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account }}" {{ old('account_name') === $account ? 'selected' : '' }}>{{ ucfirst($account) }}</option>
                                @endforeach
                            </select>
                            @error('account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="usage_category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('usage_category') is-invalid @enderror" id="usage_category" name="usage_category" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('usage_category') === $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                                @endforeach
                            </select>
                            @error('usage_category')
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

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Record Usage</button>
                            <a href="{{ route('admin.accounting.usages') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
