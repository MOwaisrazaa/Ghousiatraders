<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecture;

class FreeCourseController extends Controller
{
    public function enrollCourse(Course $course)
    {
        return redirect()->route('polani.product', ['slug' => $course->slug])
            ->with('error', 'Free enrollment is not configured in this product branch.');
    }

    public function enrollLecture(Lecture $lecture)
    {
        return redirect()->route('polani.collection')
            ->with('error', 'Free lecture enrollment is not configured in this product branch.');
    }
}
