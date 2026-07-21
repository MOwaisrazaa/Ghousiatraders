<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('user', 'answerer')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Faq::count(),
            'pending' => Faq::whereNull('answer')->count(),
            'answered' => Faq::whereNotNull('answer')->count(),
            'published' => Faq::where('is_published', true)->count(),
        ];

        return view('admin.faqs.index', compact('faqs', 'stats'));
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'answer' => 'required|string|max:2000',
            'is_published' => 'nullable|boolean',
        ]);

        $faq->update([
            'answer' => $validated['answer'],
            'is_published' => $request->has('is_published') ? (bool) $request->input('is_published') : false,
            'answered_by' => Auth::id(),
            'answered_at' => now(),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ answered and updated successfully!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully!');
    }
}
