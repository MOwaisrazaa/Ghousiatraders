<div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Coupon Manager</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Value</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Uses</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valid From</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valid Until</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupons as $coupon)
                                        <tr>
                                            <td>
                                                <div class="px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $coupon->code }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ ucfirst($coupon->type) }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">
                                                    @if($coupon->type === 'percentage')
                                                        {{ $coupon->value }}%
                                                    @elseif($coupon->type === 'fixed')
                                                        Rs {{ number_format($coupon->value, 2) }}
                                                    @else
                                                        Free
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $coupon->uses_count }} / {{ $coupon->max_uses ?? '∞' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $coupon->valid_from->format('M d, Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $coupon->valid_until->format('M d, Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-{{ $coupon->is_active ? 'success' : 'danger' }}">
                                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <button wire:click="edit({{ $coupon->id }})" class="btn btn-link text-secondary mb-0">
                                                    <i class="fa fa-edit text-xs"></i>
                                                </button>
                                                <button wire:click="delete({{ $coupon->id }})" class="btn btn-link text-danger mb-0">
                                                    <i class="fa fa-trash text-xs"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $coupons->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>{{ $editingId ? 'Edit Coupon' : 'Create New Coupon' }}</h6>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $editingId ? 'update' : 'save' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code" class="form-control-label">Coupon Code</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="code" wire:model="code">
                                            <button type="button" class="btn btn-outline-primary" wire:click="generateRandomCode">
                                                <i class="fas fa-random"></i> Generate
                                            </button>
                                        </div>
                                        @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="form-control-label">Type</label>
                                        <select class="form-control" id="type" wire:model="type">
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="free">Free</option>
                                        </select>
                                        @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="value" class="form-control-label">Value</label>
                                        <input type="number" class="form-control" id="value" wire:model="value" step="0.01">
                                        @error('value') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="max_uses" class="form-control-label">Maximum Uses (Optional)</label>
                                        <input type="number" class="form-control" id="max_uses" wire:model="max_uses">
                                        @error('max_uses') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="valid_from" class="form-control-label">Valid From</label>
                                        <input type="datetime-local" class="form-control" id="valid_from" wire:model="valid_from">
                                        @error('valid_from') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="valid_until" class="form-control-label">Valid Until</label>
                                        <input type="datetime-local" class="form-control" id="valid_until" wire:model="valid_until">
                                        @error('valid_until') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" wire:model="is_active">
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                @if($editingId)
                                    <button type="button" class="btn btn-secondary me-2" wire:click="cancel">Cancel</button>
                                @endif
                                <button type="submit" class="btn btn-primary">
                                    {{ $editingId ? 'Update' : 'Create' }} Coupon
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 