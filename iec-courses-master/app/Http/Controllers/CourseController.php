<?php

namespace App\Http\Controllers;

use App\Models\Course;

class CourseController extends Controller
{
    public function create()
    {
        return redirect()->route('admin.products.create');
    }

    public function store()
    {
        return redirect()->route('admin.products.create');
    }

    public function index()
    {
        return redirect()->route('admin.products');
    }

    public function userCourseDetail(Course $course)
    {
        return redirect()->route('polani.product', ['slug' => $course->slug]);
    }
}
