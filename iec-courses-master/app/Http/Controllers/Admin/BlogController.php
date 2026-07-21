<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest('id')->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $blog = new Blog();
        return view('admin.blogs.form', compact('blog'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'slug'       => 'nullable|string|max:255|unique:blogs,slug',
            'content'    => 'required|string',
            'image_path' => 'nullable|image|max:4096',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $this->storeImage($request->file('image_path'));
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.form', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'slug'       => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'content'    => 'required|string',
            'image_path' => 'nullable|image|max:4096',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('image_path')) {
            $this->deleteStoredImage($blog->image_path);
            $validated['image_path'] = $this->storeImage($request->file('image_path'));
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->deleteStoredImage($blog->image_path);
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }

    private function storeImage($file): string
    {
        $directory = public_path('polani/assets/blogs');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '-' . Str::random(8)
            . '.' . $file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return 'polani/assets/blogs/' . $filename;
    }

    private function deleteStoredImage(?string $path): void
    {
        if (!$path || !Str::startsWith($path, 'polani/assets/blogs/')) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
