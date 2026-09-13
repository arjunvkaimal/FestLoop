@props(['event'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
    <a href="{{ Route::has('events.show') ? route('events.show', $event) : '#' }}" class="block">
        @if(isset($event->banner_image))
            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                <span class="text-white text-lg font-semibold">{{ $event->title ?? 'Event' }}</span>
            </div>
        @endif
    </a>
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <a href="{{ Route::has('events.show') ? route('events.show', $event) : '#' }}">
                <h3 class="text-xl font-bold text-gray-900 truncate pr-4">{{ $event->title }}</h3>
            </a>
            <x-badge :type="$event->category ?? 'default'">{{ ucfirst($event->category ?? 'Event') }}</x-badge>
        </div>
        
        <div class="text-sm text-gray-600 space-y-2">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $event->venue ?? 'TBA' }}
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ isset($event->start_date) ? \Carbon\Carbon::parse($event->start_date)->format('M d, Y h:i A') : 'TBA' }}
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                {{ ($event->spots_remaining ?? 0) > 0 ? ($event->spots_remaining . ' spots remaining') : 'Event Full' }}
            </div>
        </div>
        
        <div class="mt-6">
            <a href="{{ Route::has('events.show') ? route('events.show', $event) : '#' }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                View Details
            </a>
        </div>
    </div>
</div>
