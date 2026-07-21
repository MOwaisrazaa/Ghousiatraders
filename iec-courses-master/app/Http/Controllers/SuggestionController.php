<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:suggestion,feedback',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $validated['user_id'] = Auth::id();

        Suggestion::create($validated);

        return redirect()->back()->with('success', 'Your ' . $validated['type'] . ' has been submitted successfully!');
    }

    public function index()
    {
        $suggestions = Auth::user()->suggestions()->latest()->paginate(10);
        return view('suggestions.index', compact('suggestions'));
    }
}
