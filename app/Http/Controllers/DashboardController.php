<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $registrations = $user->registrations()
            ->with('event')
            ->join('events', 'registrations.event_id', '=', 'events.id')
            ->orderBy('events.start_time', 'asc')
            ->select('registrations.*')
            ->get();

        $upcomingRegistrations = $registrations->filter(function ($registration) {
            return $registration->event->start_time >= now();
        });

        $pastRegistrations = $registrations->filter(function ($registration) {
            return $registration->event->start_time < now();
        });

        return view('dashboard', compact('upcomingRegistrations', 'pastRegistrations'));
    }
}
