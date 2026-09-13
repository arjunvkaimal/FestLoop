<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegistrationController extends Controller
{
    use AuthorizesRequests;

    public function index(Event $event)
    {
        $this->authorize('manageRegistrations', $event);

        $registrations = $event->registrations()->with('user')->paginate(20);

        return view('coordinator.registrations.index', compact('event', 'registrations'));
    }

    public function export(Event $event)
    {
        $this->authorize('manageRegistrations', $event);

        $registrations = $event->registrations()->with('user')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=registrations_{$event->id}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Status', 'Registered At']);

            foreach ($registrations as $registration) {
                fputcsv($file, [
                    $registration->user->name,
                    $registration->user->email,
                    ucfirst($registration->status),
                    $registration->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
