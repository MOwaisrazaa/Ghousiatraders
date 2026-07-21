<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of navigation pages.
     */
    public function index()
    {
        $pages = NavigationPage::orderBy('order')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        $products = \App\Models\Course::orderBy('name')->get();
        return view('admin.pages.create', compact('products'));
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'system');

        $rules = [
            'type' => 'required|in:system,custom',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'products' => 'nullable|array',
            'products.*' => 'integer|exists:products,id',
        ];

        if ($type === 'system') {
            $rules['link'] = 'required|string|max:255';
        } else {
            $rules['slug'] = 'nullable|string|max:255|unique:navigation_pages,slug';
            $rules['content'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Set default order if empty
        if (!isset($validated['order']) || $validated['order'] === '') {
            $validated['order'] = (int) NavigationPage::max('order') + 1;
        }

        $validated['is_active'] = $request->has('is_active');

        if ($type === 'custom') {
            $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);
            // Ensure slug is unique
            $originalSlug = $slug;
            $count = 1;
            while (NavigationPage::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $validated['slug'] = $slug;
            $validated['link'] = '/page/' . $slug;
        }

        $page = NavigationPage::create($validated);

        if ($request->has('products')) {
            $page->products()->sync($request->input('products'));
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Navigation page created successfully.');
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit($id)
    {
        $page = NavigationPage::with('products')->findOrFail($id);
        $products = \App\Models\Course::orderBy('name')->get();
        return view('admin.pages.edit', compact('page', 'products'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, $id)
    {
        $page = NavigationPage::findOrFail($id);
        $type = $request->input('type', $page->type);

        $rules = [
            'type' => 'required|in:system,custom',
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'products' => 'nullable|array',
            'products.*' => 'integer|exists:products,id',
        ];

        if ($type === 'system') {
            $rules['link'] = 'required|string|max:255';
        } else {
            $rules['slug'] = 'nullable|string|max:255|unique:navigation_pages,slug,' . $id;
            $rules['content'] = 'required|string';
        }

        $validated = $request->validate($rules);
        $validated['is_active'] = $request->has('is_active');

        if ($type === 'custom') {
            $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);
            // Ensure slug is unique excluding current page
            $originalSlug = $slug;
            $count = 1;
            while (NavigationPage::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $validated['slug'] = $slug;
            $validated['link'] = '/page/' . $slug;
            $validated['content'] = $request->input('content');
        } else {
            $validated['slug'] = null;
            $validated['content'] = null;
        }

        $page->update($validated);

        if ($request->has('products')) {
            $page->products()->sync($request->input('products'));
        } else {
            $page->products()->sync([]);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Navigation page updated successfully.');
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy($id)
    {
        $page = NavigationPage::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Navigation page deleted successfully.');
    }

    /**
     * Reorder pages via AJAX.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'pages' => 'required|array',
            'pages.*.id' => 'required|integer|exists:navigation_pages,id',
            'pages.*.order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($validated['pages'] as $pageData) {
                NavigationPage::where('id', $pageData['id'])
                    ->update(['order' => $pageData['order']]);
            }

            return response()->json(['success' => true, 'message' => 'Navigation reordered successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
