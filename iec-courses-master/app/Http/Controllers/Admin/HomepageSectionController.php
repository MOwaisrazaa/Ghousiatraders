<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomepageSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of homepage sections.
     */
    public function index()
    {
        $sections = HomepageSection::withCount('products')
            ->orderBy('order')
            ->orderBy('title')
            ->get();

        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new homepage section.
     */
    public function create()
    {
        $products = Course::orderBy('name')->get();
        return view('admin.sections.create', compact('products'));
    }

    /**
     * Store a newly created homepage section.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:homepage_sections,title',
            'order' => 'required|integer|min:0',
            'bg_theme' => 'required|string|in:ivory,dark',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $section = HomepageSection::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'order' => $validated['order'],
            'bg_theme' => $validated['bg_theme'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $section->products()->sync($request->input('products', []));

        return redirect()->route('admin.sections.index')
            ->with('success', 'Homepage section created successfully.');
    }

    /**
     * Show the form for editing the specified homepage section.
     */
    public function edit(HomepageSection $section)
    {
        $products = Course::orderBy('name')->get();
        $selectedProductIds = $section->products()->pluck('products.id')->toArray();

        return view('admin.sections.edit', compact('section', 'products', 'selectedProductIds'));
    }

    /**
     * Update the specified homepage section.
     */
    public function update(Request $request, HomepageSection $section)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:homepage_sections,title,' . $section->id,
            'order' => 'required|integer|min:0',
            'bg_theme' => 'required|string|in:ivory,dark',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $section->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'order' => $validated['order'],
            'bg_theme' => $validated['bg_theme'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $section->products()->sync($request->input('products', []));

        return redirect()->route('admin.sections.index')
            ->with('success', 'Homepage section updated successfully.');
    }

    /**
     * Remove the specified homepage section.
     */
    public function destroy(HomepageSection $section)
    {
        $section->products()->detach();
        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Homepage section deleted successfully.');
    }
}
