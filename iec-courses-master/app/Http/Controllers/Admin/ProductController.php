<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    public function index()
    {
        $products = Course::with('category')->orderByDesc('id')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = new Course([
            'weekly_price' => 1000,
            'monthly_price' => 1000,
            'purchase_model' => 'flexible',
            'is_free' => false,
        ]);

        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['monthly_price'] = $validated['weekly_price'];
        $validated['is_free'] = false;
        $validated['purchase_model'] = 'flexible';
        $validated['instructor'] = null;

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $this->storeImage($request->file('image_path'));
        }

        Course::create($validated);

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function show(Course $course)
    {
        $product = $course;
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Course $course)
    {
        $product = $course;
        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $product = $course;
        $validated = $this->validateProduct($request, $product->id);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['monthly_price'] = $validated['weekly_price'];
        $validated['is_free'] = false;
        $validated['purchase_model'] = 'flexible';
        $validated['instructor'] = null;

        if ($request->hasFile('image_path')) {
            $this->deleteStoredImage($product->image_path);
            $validated['image_path'] = $this->storeImage($request->file('image_path'));
        }

        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Course $course)
    {
        $product = $course;
        $this->deleteStoredImage($product->image_path);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'             => 'required|string|max:255|unique:products,name' . ($ignoreId ? ',' . $ignoreId : ''),
            'description'      => 'nullable|string',
            'long_description' => 'nullable|string',
            'weekly_price'     => 'required|numeric|min:0',
            'category_id'      => 'nullable|exists:categories,id',
            'image_path'       => 'nullable|image|max:4096',
            'intro_video_url'  => 'nullable|string|max:255',
        ]);
    }

    private function storeImage($file): string
    {
        $directory = public_path('polani/assets/products');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . Str::random(8)
            . '.' . $file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return 'polani/assets/products/' . $filename;
    }

    private function deleteStoredImage(?string $path): void
    {
        if (!$path || !Str::startsWith($path, 'polani/assets/products/')) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
