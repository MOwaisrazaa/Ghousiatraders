<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Coupon;
use Livewire\WithPagination;

class CouponManager extends Component
{
    use WithPagination;

    public $code;
    public $type = 'percentage';
    public $value;
    public $max_uses;
    public $valid_from;
    public $valid_until;
    public $is_active = true;
    public $editingId = null;

    protected $rules = [
        'code' => 'required|string|unique:coupons,code',
        'type' => 'required|in:percentage,fixed,free',
        'value' => 'required|numeric|min:0',
        'max_uses' => 'nullable|integer|min:1',
        'valid_from' => 'required|date',
        'valid_until' => 'required|date|after:valid_from',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->coupons = Coupon::all();
    }

    public function generateRandomCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        $length = 8; // Length of the coupon code
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        $this->code = $code;
    }

    public function render()
    {
        return view('livewire.coupon-manager', [
            'coupons' => Coupon::latest()->paginate(10)
        ]);
    }

    public function save()
    {
        $this->validate();

        // Convert datetime-local input to proper format
        $validFrom = \Carbon\Carbon::parse($this->valid_from)->setTimezone('UTC');
        $validUntil = \Carbon\Carbon::parse($this->valid_until)->setTimezone('UTC');

        Coupon::create([
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'max_uses' => $this->max_uses,
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'is_active' => $this->is_active,
        ]);

        $this->reset();
        session()->flash('success', 'Coupon created successfully!');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->editingId = $id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->max_uses = $coupon->max_uses;
        $this->valid_from = $coupon->valid_from->format('Y-m-d\TH:i');
        $this->valid_until = $coupon->valid_until->format('Y-m-d\TH:i');
        $this->is_active = $coupon->is_active;
    }

    public function update()
    {
        $this->validate([
            'code' => 'required|string|unique:coupons,code,' . $this->editingId,
            'type' => 'required|in:percentage,fixed,free',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
        ]);

        // Convert datetime-local input to proper format
        $validFrom = \Carbon\Carbon::parse($this->valid_from)->setTimezone('UTC');
        $validUntil = \Carbon\Carbon::parse($this->valid_until)->setTimezone('UTC');

        $coupon = Coupon::findOrFail($this->editingId);
        $coupon->update([
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'max_uses' => $this->max_uses,
            'valid_from' => $validFrom,
            'valid_until' => $validUntil,
            'is_active' => $this->is_active,
        ]);

        $this->reset();
        session()->flash('success', 'Coupon updated successfully!');
    }

    public function delete($id)
    {
        Coupon::findOrFail($id)->delete();
        session()->flash('success', 'Coupon deleted successfully!');
    }

    public function cancel()
    {
        $this->reset();
    }
} 