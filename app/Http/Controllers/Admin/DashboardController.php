<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_attendees' => User::where('role', 'attendee')->count(),
            'total_coordinators' => User::where('role', 'coordinator')->count(),
            'total_admins' => User::where('role', 'admin')->count(),

            'total_events' => Event::count(),
            'published_events' => Event::where('status', 'published')->count(),
            'draft_events' => Event::where('status', 'draft')->count(),
            'cancelled_events' => Event::where('status', 'cancelled')->count(),

            'total_registrations' => Registration::count(),
            'confirmed_registrations' => Registration::where('status', 'confirmed')->count(),
            'cancelled_registrations' => Registration::where('status', 'cancelled')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentEvents = Event::with('coordinator')->latest()->take(5)->get();
        $recentRegistrations = Registration::with(['user', 'event'])->latest('registered_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentEvents', 'recentRegistrations'));
    }
}
