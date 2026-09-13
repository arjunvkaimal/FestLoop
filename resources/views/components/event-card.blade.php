@props(['event'])

@php
    $registrationsCount = $event->registrations_count ?? $event->registrations()->count();
    $spotsLeft = max(0, $event->capacity_limit - $registrationsCount);
@endphp

<div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-xl hover:shadow-lg transition-all duration-300 flex flex-col justify-between">
    <div>
        <a href="{{ route('events.show', $event) }}" class="block overflow-hidden group">
            @if($event->banner_image)
                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
            @else
                <div class="w-full h-48 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center group-hover:scale-105 transition duration-300">
                    <span class="text-white text-lg font-bold px-4 text-center">{{ $event->title }}</span>
                </div>
            @endif
        </a>
        
        <div class="p-5">
            <div class="flex justify-between items-start mb-3 gap-2">
                <a href="{{ route('events.show', $event) }}" class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate hover:text-indigo-600 dark:hover:text-indigo-400 transition" title="{{ $event->title }}">{{ $event->title }}</h3>
                </a>
                <x-badge :type="$event->category">{{ ucfirst($event->category) }}</x-badge>
            </div>
            
            <div class="text-xs text-gray-600 dark:text-gray-300 space-y-2">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="truncate">{{ $event->venue }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>{{ $event->start_time ? $event->start_time->format('M d, Y · g:i A') : 'TBA' }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="{{ $spotsLeft > 0 ? 'text-green-600 dark:text-green-400 font-medium' : 'text-red-500 font-medium' }}">
                        {{ $spotsLeft > 0 ? ($spotsLeft . ' spots remaining') : 'Registration Full' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-5 pt-0">
        <a href="{{ route('events.show', $event) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-xs transition shadow-sm">
            View Details &rarr;
        </a>
    </div>
</div>
