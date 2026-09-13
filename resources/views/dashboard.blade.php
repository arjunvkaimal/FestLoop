<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
            {{ __('My Attendee Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-950/50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-950/50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-5">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Registrations</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $upcomingRegistrations->count() + $pastRegistrations->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-5">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Upcoming Events</div>
                    <div class="mt-2 text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $upcomingRegistrations->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-5">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Confirmed Spots</div>
                    <div class="mt-2 text-3xl font-extrabold text-green-600 dark:text-green-400">
                        {{ $upcomingRegistrations->where('status', 'confirmed')->count() + $pastRegistrations->where('status', 'confirmed')->count() }}
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <span>🗓️</span> Upcoming Registered Events
                    </h3>
                    
                    @if($upcomingRegistrations->isEmpty())
                        <div class="text-gray-500 dark:text-gray-400 text-center py-10">
                            <p class="text-sm">You haven't registered for any upcoming events yet.</p>
                            <a href="{{ route('events.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline mt-2 inline-block font-semibold text-sm">Browse Campus Events &rarr;</a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($upcomingRegistrations as $registration)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/80 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('events.show', $registration->event) }}" class="font-bold text-base text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                {{ $registration->event->title }}
                                            </a>
                                            <x-badge :type="$registration->status">{{ ucfirst($registration->status) }}</x-badge>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-3">
                                            <span>📅 {{ $registration->event->start_time->format('M d, Y · g:i A') }}</span>
                                            <span>•</span>
                                            <span>📍 {{ $registration->event->venue }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 w-full sm:w-auto">
                                        @if($registration->status === 'confirmed')
                                            <a href="{{ route('registrations.pass', $registration) }}" target="_blank" class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900 border border-indigo-200 dark:border-indigo-800 rounded-lg text-xs font-semibold transition">
                                                View Pass 🎫
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('registrations.destroy', $registration) }}" class="w-full sm:w-auto" onsubmit="return confirm('Are you sure you want to cancel your registration?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/60 border border-red-200 dark:border-red-800/40 rounded-lg text-xs font-semibold transition">
                                                Cancel
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
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl">
                <div class="p-6">
                    <h3 class="text-base font-bold text-gray-700 dark:text-gray-300 mb-4">Past Event Registrations</h3>
                    
                    @if($pastRegistrations->isEmpty())
                        <div class="text-gray-400 dark:text-gray-500 text-center py-6 text-sm">
                            <p>No past event registrations recorded.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($pastRegistrations as $registration)
                                <div class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 bg-gray-50/75 dark:bg-gray-800/40 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 opacity-75">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-semibold text-sm text-gray-700 dark:text-gray-300">{{ $registration->event->title }}</h4>
                                            <x-badge :type="$registration->status">{{ ucfirst($registration->status) }}</x-badge>
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
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
