<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::with(['parent'])->withCount('courses');

        // 1. Search keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // 2. Type filter
        if ($request->filled('type')) {
            if ($request->type === 'main') {
                $query->whereNull('parent_id');
            } elseif ($request->type === 'sub') {
                $query->whereNotNull('parent_id');
            }
        }

        // 3. Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'most_products':
                $query->orderBy('courses_count', 'desc');
                break;
            case 'least_products':
                $query->orderBy('courses_count', 'asc');
                break;
            case 'display_order':
                $query->orderBy('display_order', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->input('per_page', 10);
        $categories = $query->paginate($perPage)->withQueryString();

        // 4. Statistics counts
        $totalCategoriesCount = Category::count();
        $mainCategoriesCount = Category::whereNull('parent_id')->count();
        $subCategoriesCount = Category::whereNotNull('parent_id')->count();
        $hiddenCategoriesCount = Category::where('status', 'hidden')->count();

        // All categories for selectors
        $allCategories = Category::orderBy('name', 'asc')->get();

        return view('admin.categories.index', compact(
            'categories',
            'totalCategoriesCount',
            'mainCategoriesCount',
            'subCategoriesCount',
            'hiddenCategoriesCount',
            'allCategories'
        ));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'display_order' => 'nullable|integer',
            'status' => 'required|string|in:active,inactive,hidden',
            'image_path' => 'nullable|image|max:4096',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $directory = public_path('ghousiatraders/assets/categories');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '-' . Str::random(8)
                . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $validated['image_path'] = 'ghousiatraders/assets/categories/' . $filename;
        }

        $validated['display_order'] = $validated['display_order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'display_order' => 'nullable|integer',
            'status' => 'required|string|in:active,inactive,hidden',
            'image_path' => 'nullable|image|max:4096',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        // Loop validation: cannot be own parent or descendant of own category
        if ($request->filled('parent_id')) {
            $parentId = $request->parent_id;
            if ($parentId == $category->id) {
                return back()->withErrors(['parent_id' => 'A category cannot be its own parent.'])->withInput();
            }
            if ($this->isDescendant($parentId, $category->id)) {
                return back()->withErrors(['parent_id' => 'A category cannot select its own descendant as parent.'])->withInput();
            }
        }

        // Handle image upload
        if ($request->hasFile('image_path')) {
            // Delete old image if it exists
            if ($category->image_path) {
                $oldPath = public_path($category->image_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('image_path');
            $directory = public_path('ghousiatraders/assets/categories');
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '-' . Str::random(8)
                . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $validated['image_path'] = 'ghousiatraders/assets/categories/' . $filename;
        }

        $validated['display_order'] = $validated['display_order'] ?? 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from database.
     */
    public function destroy(Category $category)
    {
        // 1. Deletion constraints checking
        if ($category->courses()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category: ' . $category->name . ' has active products assigned to it. Please reassign the products first.');
        }

        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category: ' . $category->name . ' has subcategories. Please delete or reassign subcategories first.');
        }

        // Delete image asset from public storage
        if ($category->image_path) {
            $path = public_path($category->image_path);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Fast toggle status for category.
     */
    public function toggleStatus(Request $request, Category $category)
    {
        $status = $request->input('status');
        if (!in_array($status, ['active', 'inactive', 'hidden'])) {
            return back()->with('error', 'Invalid status selection.');
        }

        $category->update(['status' => $status]);

        return back()->with('success', 'Category status updated successfully.');
    }

    /**
     * Save dynamic display orders for categories.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:categories,id',
            'orders.*.display_order' => 'required|integer',
        ]);

        foreach ($request->orders as $item) {
            Category::where('id', $item['id'])->update(['display_order' => $item['display_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Categories display order updated successfully.']);
    }

    /**
     * Helper check: Is candidate parent a descendant of current category?
     */
    private function isDescendant($parentId, $childId)
    {
        if ($parentId == $childId) {
            return true;
        }
        $parent = Category::find($parentId);
        if (!$parent || !$parent->parent_id) {
            return false;
        }
        return $this->isDescendant($parent->parent_id, $childId);
    }
}
