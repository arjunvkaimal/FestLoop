<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Total Registrations</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $upcomingRegistrations->count() + $pastRegistrations->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Upcoming Events</div>
                    <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $upcomingRegistrations->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium">Confirmed</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">
                        {{ $upcomingRegistrations->where('status', 'confirmed')->count() + $pastRegistrations->where('status', 'confirmed')->count() }}
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Events</h3>
                    
                    @if($upcomingRegistrations->isEmpty())
                        <div class="text-gray-500 text-center py-8">
                            <p>You haven't registered for any upcoming events yet.</p>
                            <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-900 mt-2 inline-block">Browse Events</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($upcomingRegistrations as $registration)
                                <div class="border rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-lg">{{ $registration->event->title }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($registration->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <span>📅 {{ $registration->event->start_time->format('M d, Y h:i A') }}</span>
                                            <span class="mx-2">|</span>
                                            <span>📍 {{ $registration->event->venue }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 w-full sm:w-auto">
                                        @if($registration->status === 'confirmed')
                                            <a href="{{ route('registrations.pass', $registration) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                View Pass
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('registrations.destroy', $registration) }}" class="w-full sm:w-auto" onsubmit="return confirm('Are you sure you want to cancel your registration?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium w-full sm:w-auto text-left">
                                                Cancel Registration
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Past Events -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg opacity-75">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Past Events</h3>
                    
                    @if($pastRegistrations->isEmpty())
                        <div class="text-gray-500 text-center py-4">
                            <p>No past event registrations.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($pastRegistrations as $registration)
                                <div class="border rounded-lg p-4 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-gray-600">{{ $registration->event->title }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-700">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-400 mt-1">
                                            <span>📅 {{ $registration->event->start_time->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
