<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

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
     * Update specified payment method general information (6 fields).
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'instructions' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $paymentMethod->update([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?? $paymentMethod->icon,
            'description' => $validated['description'] ?? '',
            'instructions' => $validated['instructions'] ?? '',
            'sort_order' => isset($validated['sort_order']) ? (int)$validated['sort_order'] : $paymentMethod->sort_order,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : $paymentMethod->is_active,
        ]);

        return redirect()->to(url('/admin/settings?tab=payment_methods'))
            ->with('success', $paymentMethod->name . ' updated successfully.')
            ->with('open_accordion', $paymentMethod->id);
    }

    /**
     * Toggle active status via AJAX or POST.
     */
    public function toggleStatus(Request $request, PaymentMethod $paymentMethod)
    {
        $newStatus = $request->has('is_active') 
            ? (bool)$request->input('is_active') 
            : !$paymentMethod->is_active;

        $paymentMethod->update([
            'is_active' => $newStatus
        ]);

        $statusText = $paymentMethod->is_active ? 'Active' : 'Inactive';
        $message = $paymentMethod->name . ' is now ' . $statusText . '.';

        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'is_active' => $paymentMethod->is_active,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Update order of payment methods.
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
