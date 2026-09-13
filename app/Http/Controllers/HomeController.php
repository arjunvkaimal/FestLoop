<?php

namespace App\Http\Controllers;

use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $featuredEvents = Event::published()
            ->upcoming()
            ->orderBy('start_time')
            ->take(6)
            ->get();

        $categories = ['cultural', 'technical', 'sports', 'workshop', 'seminar', 'other'];

        return view('welcome', compact('featuredEvents', 'categories'));
    }
}
