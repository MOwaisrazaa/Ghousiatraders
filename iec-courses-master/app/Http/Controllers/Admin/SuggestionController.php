<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SuggestionController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('suggestions')) {
            return redirect()->route('admin.dashboard')->with('error', 'Feedback table is not available in this branch.');
        }

        $suggestions = Suggestion::with('user', 'course')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => Suggestion::count(),
            'pending' => Suggestion::where('status', 'pending')->count(),
            'reviewed' => Suggestion::where('status', 'reviewed')->count(),
            'resolved' => Suggestion::where('status', 'resolved')->count(),
        ];

        return view('admin.suggestions.index', compact('suggestions', 'stats'));
    }

    public function show(Suggestion $suggestion)
    {
        if (!Schema::hasTable('suggestions')) {
            return redirect()->route('admin.dashboard')->with('error', 'Feedback table is not available in this branch.');
        }

        return view('admin.suggestions.show', compact('suggestion'));
    }

    public function update(Request $request, Suggestion $suggestion)
    {
        if (!Schema::hasTable('suggestions')) {
            return redirect()->route('admin.dashboard')->with('error', 'Feedback table is not available in this branch.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_response' => 'nullable|string|min:10',
        ]);

        $suggestion->update([
            'status' => $validated['status'],
            'admin_response' => $validated['admin_response'],
            'admin_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.suggestions.index')->with('success', 'Suggestion updated successfully!');
    }

    public function destroy(Suggestion $suggestion)
    {
        if (!Schema::hasTable('suggestions')) {
            return redirect()->route('admin.dashboard')->with('error', 'Feedback table is not available in this branch.');
        }

        $suggestion->delete();
        return redirect()->route('admin.suggestions.index')->with('success', 'Suggestion deleted successfully!');
    }
}
