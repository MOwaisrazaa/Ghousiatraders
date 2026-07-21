<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\UserCourse;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user dashboard with purchased courses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return redirect()->route('home');
    }

    /**
     * Display all quiz results for the user.
     *
     * @return \Illuminate\View\View
     */
    public function quizResults()
    {
        return redirect()->route('home');
    }
}
