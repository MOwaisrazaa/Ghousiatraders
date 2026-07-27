<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin', 'admin.permission:courses']);
    }

    /**
     * Display listing of products.
     */
    public function index(Request $request)
    {
        $query = Course::with('category');

        // 1. Search (keyword: name, SKU, slug, category, description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function($catQ) use ($search) {
                      $catQ->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // 2. Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Stock status filter
        if ($request->filled('stock_status')) {
            $statusFilter = $request->stock_status;
            if ($statusFilter === 'in_stock') {
                $query->where('stock', '>', DB::raw('low_stock_threshold'));
            } elseif ($statusFilter === 'low_stock') {
                $query->where('stock', '>', 0)
                      ->where('stock', '<=', DB::raw('low_stock_threshold'));
            } elseif ($statusFilter === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        // 4. Price type filter
        if ($request->filled('price_type')) {
            $pt = $request->price_type;
            if ($pt === 'regular') {
                $query->whereNull('sale_price');
            } elseif ($pt === 'on_sale') {
                $query->whereNotNull('sale_price');
            } elseif ($pt === 'free') {
                $query->where(function($q) {
                    $q->where('weekly_price', 0)->orWhere('is_free', true);
                });
            }
        }

        // Fetch sales map from orders for Best Selling sort and insights
        $orders = Order::all();
        $salesMap = [];
        foreach ($orders as $order) {
            $items = json_decode($order->cart_items, true) ?? [];
            foreach ($items as $item) {
                $courseId = $item['course_id'] ?? null;
                if ($courseId) {
                    $salesMap[$courseId] = ($salesMap[$courseId] ?? 0) + 1;
                }
            }
        }

        // 5. Product status tabs filter
        $tab = $request->input('status_tab', 'all');
        if ($tab === 'active') {
            $query->where('status', 'active');
        } elseif ($tab === 'draft') {
            $query->where('status', 'draft');
        } elseif ($tab === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($tab === 'low_stock') {
            $query->where('stock', '>', 0)
                  ->where('stock', '<=', DB::raw('low_stock_threshold'));
        } elseif ($tab === 'featured') {
            $query->where('is_featured', true);
        }

        // 6. Sorting
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
            case 'price_low':
                $query->orderBy('weekly_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('weekly_price', 'desc');
                break;
            case 'stock_low':
                $query->orderBy('stock', 'asc');
                break;
            case 'best_selling':
                // Retrieve matching IDs sorted globally in PHP
                $allIds = $query->pluck('id')->toArray();
                usort($allIds, function($a, $b) use ($salesMap) {
                    return ($salesMap[$b] ?? 0) <=> ($salesMap[$a] ?? 0);
                });
                if (empty($allIds)) {
                    $allIds = [0];
                }
                $idsString = implode(',', $allIds);
                $query->whereIn('id', $allIds)->orderByRaw("FIELD(id, $idsString)");
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage)->withQueryString();

        // 7. Dynamic totals count for KPIs
        $totalProductsCount = Course::count();
        $activeProductsCount = Course::where('status', 'active')->count();
        $outOfStockCount = Course::where('stock', '<=', 0)->count();
        $lowStockCount = Course::where('stock', '>', 0)->where('stock', '<=', DB::raw('low_stock_threshold'))->count();
        $draftProductsCount = Course::where('status', 'draft')->count();

        // 8. Tab counts
        $allTabCount = Course::count();
        $activeTabCount = Course::where('status', 'active')->count();
        $draftTabCount = Course::where('status', 'draft')->count();
        $outOfStockTabCount = Course::where('stock', '<=', 0)->count();
        $lowStockTabCount = Course::where('stock', '>', 0)->where('stock', '<=', DB::raw('low_stock_threshold'))->count();
        $featuredTabCount = Course::where('is_featured', true)->count();

        // 9. Right Insights - Top Selling Products filter by Selected period
        $period = $request->input('period', 'this_month');
        $startDate = now()->startOfMonth();
        if ($period === 'this_week') {
            $startDate = now()->startOfWeek();
        } elseif ($period === 'last_3_months') {
            $startDate = now()->subMonths(3);
        } elseif ($period === 'this_year') {
            $startDate = now()->startOfYear();
        }

        $filteredOrders = Order::where('created_at', '>=', $startDate)->get();
        $periodSalesMap = [];
        foreach ($filteredOrders as $order) {
            $items = json_decode($order->cart_items, true) ?? [];
            foreach ($items as $item) {
                $courseId = $item['course_id'] ?? null;
                if ($courseId) {
                    $periodSalesMap[$courseId] = ($periodSalesMap[$courseId] ?? 0) + 1;
                }
            }
        }

        // Fetch top 5 products sorted by quantity sold in selected period
        arsort($periodSalesMap);
        $topSellingIds = array_slice(array_keys($periodSalesMap), 0, 5, true);
        $topSellingProducts = [];
        foreach ($topSellingIds as $id) {
            $prod = Course::find($id);
            if ($prod) {
                $qty = $periodSalesMap[$id];
                $revenue = $qty * $prod->weekly_price;
                $topSellingProducts[] = [
                    'product' => $prod,
                    'qty' => $qty,
                    'revenue' => $revenue
                ];
            }
        }

        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'totalProductsCount',
            'activeProductsCount',
            'outOfStockCount',
            'lowStockCount',
            'draftProductsCount',
            'allTabCount',
            'activeTabCount',
            'draftTabCount',
            'outOfStockTabCount',
            'lowStockTabCount',
            'featuredTabCount',
            'topSellingProducts'
        ));
    }

    /**
     * Show creation form.
     */
    public function create()
    {
        $product = new Course([
            'weekly_price' => 1000,
            'monthly_price' => 1000,
            'purchase_model' => 'flexible',
            'is_free' => false,
            'stock' => 10,
            'low_stock_threshold' => 5,
            'status' => 'active',
        ]);

        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['monthly_price'] = $validated['weekly_price'];
        $validated['is_free'] = false;
        $validated['purchase_model'] = 'flexible';
        $validated['instructor'] = null;

        // Auto-assign SKU if empty
        if (empty($validated['sku'])) {
            $validated['sku'] = 'GT-P-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            while (Course::where('sku', $validated['sku'])->exists()) {
                $validated['sku'] = 'GT-P-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        }

        $validated['image_path'] = '';
        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $this->storeImage($request->file('image_path'));
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        Course::create($validated);

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    /**
     * Show detail page.
     */
    public function show(Course $course)
    {
        $product = $course;
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show edit form.
     */
    public function edit(Course $course)
    {
        $product = $course;
        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories'));
    }

    /**
     * Update product.
     */
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

        $validated['is_featured'] = $request->boolean('is_featured');

        $product->update($validated);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function destroy(Course $course)
    {
        $product = $course;
        
        // Safety check: do not delete product if assigned in orders history
        $ordersCount = Order::where('cart_items', 'like', '%"course_id":' . $product->id . '%')
            ->orWhere('cart_items', 'like', '%"course_id": ' . $product->id . '%')
            ->count();
        if ($ordersCount > 0) {
            return redirect()->route('admin.products')
                ->with('error', 'Cannot delete product: ' . $product->name . ' is referenced in ' . $ordersCount . ' orders. Please deactivate or set to Draft instead.');
        }

        $this->deleteStoredImage($product->image_path);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    /**
     * Fast toggle status.
     */
    public function toggleStatus(Request $request, Course $course)
    {
        $status = $request->input('status');
        if (!in_array($status, ['active', 'inactive', 'draft'])) {
            return back()->with('error', 'Invalid status selection.');
        }

        $course->update(['status' => $status]);

        return back()->with('success', 'Product status updated successfully.');
    }

    /**
     * Fast toggle featured status.
     */
    public function toggleFeatured(Request $request, Course $course)
    {
        $course->update(['is_featured' => !$course->is_featured]);

        return back()->with('success', 'Product featured status updated successfully.');
    }

    /**
     * Quick stock updates.
     */
    public function updateStock(Request $request, Course $course)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $course->update(['stock' => $request->stock]);

        return back()->with('success', 'Stock inventory updated successfully.');
    }

    /**
     * Export products to CSV.
     */
    public function export(Request $request)
    {
        $query = Course::with('category');

        // Apply same filters as main table
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_export_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Product ID', 'Product Name', 'SKU', 'Slug', 'Category', 'Price (PKR)', 'Sale Price (PKR)', 'Cost Price (PKR)', 'Stock', 'Low Stock Threshold', 'Status', 'Is Featured', 'Description']);

            foreach ($products as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->name,
                    $p->sku,
                    $p->slug,
                    $p->category->name ?? 'None',
                    $p->weekly_price,
                    $p->sale_price,
                    $p->cost_price,
                    $p->stock,
                    $p->low_stock_threshold,
                    $p->status,
                    $p->is_featured ? 'Yes' : 'No',
                    $p->description
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Batch import products from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = fgetcsv($handle, 1000, ',');
            
            // Normalize headers
            $headers = array_map(function($h) {
                return trim(strtolower(str_replace([' ', '(pkr)'], ['_', ''], $h)));
            }, $headers);

            $rowNum = 1;
            $errors = [];

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNum++;
                if (count($headers) !== count($data)) {
                    $errors[] = "Row {$rowNum}: Column count mismatch.";
                    continue;
                }

                $row = array_combine($headers, $data);
                $row = array_map('trim', $row);

                // Row validations
                $validator = Validator::make($row, [
                    'product_name' => 'required|string|max:255',
                    'sku' => 'nullable|string|max:255|unique:products,sku',
                    'category' => 'required|string',
                    'price' => 'required|numeric|min:0',
                    'sale_price' => 'nullable|numeric|min:0',
                    'cost_price' => 'nullable|numeric|min:0',
                    'stock' => 'nullable|integer|min:0',
                    'low_stock_threshold' => 'nullable|integer|min:0',
                    'status' => 'required|string|in:active,draft,inactive',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Row {$rowNum}: " . implode(' ', $validator->errors()->all());
                    continue;
                }

                // Resolve Category reference
                $catName = $row['category'];
                $category = Category::where('name', $catName)
                    ->orWhere('slug', Str::slug($catName))
                    ->first();
                if (!$category) {
                    $category = Category::create([
                        'name' => $catName,
                        'slug' => Str::slug($catName),
                    ]);
                }

                // Make unique slug
                $slug = Str::slug($row['product_name']);
                $baseSlug = $slug;
                $counter = 1;
                while (Course::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                // Resolve unique SKU if empty
                $sku = $row['sku'];
                if (empty($sku)) {
                    $sku = 'GT-P-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    while (Course::where('sku', $sku)->exists()) {
                        $sku = 'GT-P-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    }
                }

                Course::create([
                    'name' => $row['product_name'],
                    'slug' => $slug,
                    'sku' => $sku,
                    'category_id' => $category->id,
                    'weekly_price' => floatval($row['price']),
                    'monthly_price' => floatval($row['price']),
                    'sale_price' => $row['sale_price'] !== '' ? floatval($row['sale_price']) : null,
                    'cost_price' => $row['cost_price'] !== '' ? floatval($row['cost_price']) : null,
                    'stock' => $row['stock'] !== '' ? intval($row['stock']) : 10,
                    'low_stock_threshold' => $row['low_stock_threshold'] !== '' ? intval($row['low_stock_threshold']) : 5,
                    'status' => $row['status'],
                    'is_featured' => isset($row['is_featured']) ? filter_var($row['is_featured'], FILTER_VALIDATE_BOOLEAN) : false,
                    'description' => $row['description'] ?? 'No short description',
                    'long_description' => $row['long_description'] ?? null,
                    'purchase_model' => 'flexible',
                    'is_free' => false,
                    'image_path' => '',
                ]);
            }
            fclose($handle);

            if (!empty($errors)) {
                return back()->with('error', 'Import failed with errors:<br>' . implode('<br>', $errors));
            }

            return redirect()->route('admin.products')->with('success', 'Products imported successfully.');
        }

        return back()->with('error', 'Failed to read import CSV file.');
    }

    /**
     * Shared validator schema.
     */
    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255|unique:products,name' . ($ignoreId ? ',' . $ignoreId : ''),
            'sku' => 'nullable|string|max:255|unique:products,sku' . ($ignoreId ? ',' . $ignoreId : ''),
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'weekly_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'status' => 'required|string|in:active,draft,inactive',
            'category_id' => 'nullable|exists:categories,id',
            'image_path' => 'nullable|image|max:4096',
            'intro_video_url' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);
    }

    /**
     * File upload helper.
     */
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

    /**
     * Delete stored image asset helper.
     */
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
