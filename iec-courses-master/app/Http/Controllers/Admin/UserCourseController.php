<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCourse;
use App\Models\User;
use App\Models\Course;
use App\Models\Lecture;

class UserCourseController extends Controller
{
    /**
     * Display a listing of user course access.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userCourses = UserCourse::with(['user', 'course', 'lecture', 'order'])
            ->latest()
            ->paginate(15);

        return view('admin.user-courses.index', compact('userCourses'));
    }

    /**
     * Show the form for creating a new user course access.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::all();
        $courses = Course::all();
        $lectures = Lecture::all();

        return view('admin.user-courses.create', compact('users', 'courses', 'lectures'));
    }

    /**
     * Store a newly created user course access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'access_type' => 'required|in:course,lecture',
            'course_id' => 'required_if:access_type,course|nullable|exists:courses,id',
            'lecture_id' => 'required_if:access_type,lecture|nullable|exists:lectures,id',
            'status' => 'required|in:active,inactive',
            'expires_at' => 'nullable|date',
        ]);

        UserCourse::create([
            'user_id' => $validatedData['user_id'],
            'course_id' => $validatedData['access_type'] === 'course' ? $validatedData['course_id'] : null,
            'lecture_id' => $validatedData['access_type'] === 'lecture' ? $validatedData['lecture_id'] : null,
            'status' => $validatedData['status'],
            'expires_at' => $validatedData['expires_at'] ?? null,
        ]);

        return redirect()->route('admin.user-courses.index')
            ->with('success', 'Course access granted successfully.');
    }

    /**
     * Show the form for editing the specified user course access.
     *
     * @param  \App\Models\UserCourse  $userCourse
     * @return \Illuminate\View\View
     */
    public function edit(UserCourse $userCourse)
    {
        $users = User::all();
        $courses = Course::all();
        $lectures = Lecture::all();

        return view('admin.user-courses.edit', compact('userCourse', 'users', 'courses', 'lectures'));
    }

    /**
     * Update the specified user course access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserCourse  $userCourse
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, UserCourse $userCourse)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:active,inactive',
            'expires_at' => 'nullable|date',
        ]);

        $userCourse->update([
            'status' => $validatedData['status'],
            'expires_at' => $validatedData['expires_at'] ?? null,
        ]);

        return redirect()->route('admin.user-courses.index')
            ->with('success', 'Course access updated successfully.');
    }

    /**
     * Remove the specified user course access.
     *
     * @param  \App\Models\UserCourse  $userCourse
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UserCourse $userCourse)
    {
        $userCourse->delete();

        return redirect()->route('admin.user-courses.index')
            ->with('success', 'Course access revoked successfully.');
    }
}
