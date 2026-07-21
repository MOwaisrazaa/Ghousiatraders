<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('sort_order')->get();

        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for editing a payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        // Handle details separately based on payment method type
        $details = [];

        switch ($paymentMethod->key) {
            case 'cash':
                $details['color'] = $request->input('color', 'text-success');
                break;

            case 'jazzcash':
                $validator = Validator::make($request->all(), [
                    'account_number' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
                }

                $details['account'] = $request->input('account_number');
                $details['color'] = $request->input('color', 'text-danger');
                break;

            case 'easypaisa':
                $validator = Validator::make($request->all(), [
                    'account_number' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
                }

                $details['account'] = $request->input('account_number');
                $details['color'] = $request->input('color', 'text-warning');
                break;

            case 'banktransfer':
                $validator = Validator::make($request->all(), [
                    'bank_name' => 'required|string|max:255',
                    'account_title' => 'required|string|max:255',
                    'account_number' => 'required|string|max:255',
                    'iban' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
                }

                $details['bank_name'] = $request->input('bank_name');
                $details['account_title'] = $request->input('account_title');
                $details['account_number'] = $request->input('account_number');
                $details['iban'] = $request->input('iban');
                $details['color'] = $request->input('color', 'text-primary');
                break;

            case 'card':
                $details['processor'] = $request->input('processor', 'stripe');
                $details['color'] = $request->input('color', 'text-info');
                break;
        }

        $validated['details'] = $details;

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', $paymentMethod->name . ' payment method has been updated.');
    }

    /**
     * Toggle the active status of the payment method.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active
        ]);

        $status = $paymentMethod->is_active ? 'enabled' : 'disabled';

        return redirect()->route('admin.payment-methods.index')
            ->with('success', $paymentMethod->name . ' has been ' . $status . '.');
    }

    /**
     * Update the sorting order of payment methods.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:payment_methods,id',
        ]);

        foreach ($validated['order'] as $index => $id) {
            PaymentMethod::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
