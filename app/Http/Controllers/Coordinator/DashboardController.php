<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $events = $user->coordinatedEvents()->withCount('registrations')->get();

        $stats = [
            'total_events' => $events->count(),
            'total_registrations' => $events->sum('registrations_count'),
            'upcoming_events' => $events->where('start_time', '>=', now())->count(),
            'published_events' => $events->where('status', 'published')->count(),
        ];

        $recentEvents = $user->coordinatedEvents()
            ->withCount('registrations')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('coordinator.dashboard', compact('stats', 'recentEvents'));
    }
}
