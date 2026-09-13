<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('coordinator')->withCount('registrations');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $events = $query->latest()->paginate(15)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function updateStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:published,draft,cancelled'],
        ]);

        $event->update(['status' => $request->status]);

        return back()->with('success', "Event '{$event->title}' status changed to " . ucfirst($request->status) . '.');
    }

    public function destroy(Event $event)
    {
        if ($event->banner_image) {
            Storage::disk('public')->delete($event->banner_image);
        }

        $title = $event->title;
        $event->delete();

        return back()->with('success', "Event '{$title}' and its associated registrations were deleted.");
    }
}
