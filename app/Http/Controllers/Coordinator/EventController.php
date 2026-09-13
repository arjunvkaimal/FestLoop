<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $events = Auth::user()->coordinatedEvents()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('coordinator.events.index', compact('events'));
    }

    public function create()
    {
        return view('coordinator.events.create');
    }

    public function show(Event $event)
    {
        return redirect()->route('coordinator.events.edit', $event);
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('events', 'public');
        }

        $data['coordinator_id'] = Auth::id();

        Event::create($data);

        return redirect()->route('coordinator.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        
        return view('coordinator.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $data = $request->validated();

        if ($request->hasFile('banner_image')) {
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->back()->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        if ($event->banner_image) {
            Storage::disk('public')->delete($event->banner_image);
        }
        
        $event->delete();

        return redirect()->route('coordinator.events.index')->with('success', 'Event deleted successfully.');
    }
}
