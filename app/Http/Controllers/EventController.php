<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::published();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $sort = $request->input('sort', 'asc');
        $query->orderBy('start_time', $sort === 'desc' ? 'desc' : 'asc');

        $events = $query->paginate(12)->withQueryString();

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('coordinator')->loadCount('registrations');
        
        $isRegistered = false;
        if (Auth::check()) {
            $isRegistered = Auth::user()->registrations()
                ->where('event_id', $event->id)
                ->where('status', '!=', 'cancelled')
                ->exists();
        }

        return view('events.show', compact('event', 'isRegistered'));
    }

    public function search(Request $request)
    {
        $query = Event::published();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $events = $query->select(['id', 'title', 'category', 'venue', 'start_time', 'banner_image'])
            ->orderBy('start_time', 'asc')
            ->take(10)
            ->get();

        return response()->json($events);
    }
}
