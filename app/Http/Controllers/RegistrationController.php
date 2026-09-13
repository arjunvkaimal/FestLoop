<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function store(Event $event)
    {
        if (!$event->registration_open) {
            return back()->with('error', 'Registration for this event is closed.');
        }

        $user = Auth::user();

        $alreadyRegistered = $user->registrations()
            ->where('event_id', $event->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($alreadyRegistered) {
            return back()->with('error', 'You are already registered for this event.');
        }

        if ($event->is_full) {
            return back()->with('error', 'This event has reached its capacity limit.');
        }

        $user->registrations()->create([
            'event_id' => $event->id,
            'status' => 'confirmed'
        ]);

        return back()->with('success', 'Successfully registered for the event!');
    }

    public function destroy(Registration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        $registration->update(['status' => 'cancelled']);

        return back()->with('success', 'Registration cancelled successfully.');
    }

    public function pass(Registration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        $registration->load('event');

        return view('registrations.pass', compact('registration'));
    }
}
