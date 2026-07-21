<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use App\Services\CarouselImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CarouselController extends Controller
{
    protected CarouselImageProcessor $imageProcessor;

    public function __construct(CarouselImageProcessor $imageProcessor)
    {
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * Display a listing of banners.
     */
    public function index()
    {
        $slides = CarouselSlide::orderBy('order')->get()->groupBy('page_key');

        return view('admin.carousel.index', compact('slides'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.carousel.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        // Only Home and Cart pages have a button
        $pagesWithButton = ['home', 'cart'];
        $pageKey = $request->input('page_key');
        $ctaRequired = in_array($pageKey, $pagesWithButton, true) ? 'required' : 'nullable';

        // Validate input
        // Note: Image aspect ratio is NOT validated - it will be auto-cropped to 4:3
        // Note: File size limit is 5MB before upload (will be compressed to WebP after)
        $validated = $request->validate([
            'page_key' => 'required|in:' . implode(',', CarouselSlide::PAGE_KEYS),
            'eyebrow' => 'nullable|string|max:100',
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:500',
            'cta_text' => $ctaRequired . '|string|max:50',
            'cta_url' => $ctaRequired . '|url|max:255',
            'secondary_cta_text' => 'nullable|string|max:50',
            'secondary_cta_url' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            // Generate unique image name
            $imageName = 'carousel-slide-' . time() . '-' . Str::random(6);

            // Process and optimize image
            $this->imageProcessor->process($request->file('image'), $imageName);

            // Determine next order value
            $nextOrder = (int) CarouselSlide::where('page_key', $validated['page_key'])->max('order') + 1;

            // Create banner
            $slide = CarouselSlide::create([
                'page_key' => $validated['page_key'],
                'eyebrow' => $validated['eyebrow'],
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'],
                'cta_text' => $validated['cta_text'],
                'cta_url' => $validated['cta_url'],
                'secondary_cta_text' => $validated['secondary_cta_text'],
                'secondary_cta_url' => $validated['secondary_cta_url'],
                'image_name' => $imageName,
                'order' => $nextOrder,
                'is_active' => $validated['is_active'] ?? true,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('admin.banners.index')
                ->with('success', "Banner '{$slide->title}' created successfully!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['image' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(CarouselSlide $slide)
    {
        return view('admin.carousel.edit', compact('slide'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, CarouselSlide $slide)
    {
        // Only Home and Cart pages have a button
        $pagesWithButton = ['home', 'cart'];
        $pageKey = $request->input('page_key');
        $ctaRequired = in_array($pageKey, $pagesWithButton, true) ? 'required' : 'nullable';

        $validated = $request->validate([
            'page_key' => 'required|in:' . implode(',', CarouselSlide::PAGE_KEYS),
            'eyebrow' => 'nullable|string|max:100',
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:500',
            'cta_text' => $ctaRequired . '|string|max:50',
            'cta_url' => $ctaRequired . '|url|max:255',
            'secondary_cta_text' => 'nullable|string|max:50',
            'secondary_cta_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            // If new image provided, process it
            $imageName = $slide->image_name;
            if ($request->hasFile('image')) {
                // Delete old images
                $this->imageProcessor->deleteImages($slide->image_name);

                // Generate new image name
                $imageName = 'carousel-slide-' . time() . '-' . Str::random(6);

                // Process new image
                $this->imageProcessor->process($request->file('image'), $imageName);
            }

            // Update banner
            $slide->update([
                'page_key' => $validated['page_key'],
                'eyebrow' => $validated['eyebrow'],
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'],
                'cta_text' => $validated['cta_text'],
                'cta_url' => $validated['cta_url'],
                'secondary_cta_text' => $validated['secondary_cta_text'],
                'secondary_cta_url' => $validated['secondary_cta_url'],
                'image_name' => $imageName,
                'is_active' => $validated['is_active'] ?? true,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('admin.banners.index')
                ->with('success', "Banner '{$slide->title}' updated successfully!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['image' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(CarouselSlide $slide)
    {
        // Check if this is the last active banner
        $activeCount = CarouselSlide::where('is_active', true)->count();
        if ($slide->is_active && $activeCount <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last active banner. There must always be at least one active banner.']);
        }

        try {
            // Delete image files
            $this->imageProcessor->deleteImages($slide->image_name);

            // Delete database record
            $title = $slide->title;
            $slide->delete();

            return redirect()->route('admin.banners.index')
                ->with('success', "Banner '{$title}' deleted successfully!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete banner: ' . $e->getMessage()]);
        }
    }

    /**
     * Reorder banners via AJAX.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|integer|exists:carousel_slides,id',
            'slides.*.order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($validated['slides'] as $slideData) {
                CarouselSlide::where('id', $slideData['id'])
                    ->update(['order' => $slideData['order']]);
            }

            return response()->json(['success' => true, 'message' => 'Carousel reordered successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
