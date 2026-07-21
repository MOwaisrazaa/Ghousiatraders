<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CarouselSlide;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page for both guests and authenticated users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return redirect()->route('home');
    }

    /**
     * Display the authenticated user's dashboard
     *
     * @return \Illuminate\View\View
     */
    public function authenticatedIndex()
    {
        return redirect()->route('home');
    }
}
