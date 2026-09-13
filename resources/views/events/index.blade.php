@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Explore Events</h1>
        
        <form action="{{ Route::has('events.index') ? route('events.index') : '#' }}" method="GET" class="bg-white p-4 rounded-lg shadow-sm" x-data="{ search: '{{ request('search') }}' }">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="w-full md:w-1/3 relative">
                    <input 
                        type="text" 
                        name="search" 
                        x-model="search"
                        @input.debounce.500ms="$el.form.submit()"
                        placeholder="Search events..." 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10"
                    >
                    <svg class="h-5 w-5 text-gray-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                
                <div class="flex overflow-x-auto pb-2 md:pb-0 hide-scrollbar space-x-2">
                    @php
                        $categoriesList = ['All', 'Cultural', 'Technical', 'Sports', 'Workshop', 'Seminar', 'Other'];
                    @endphp
                    
                    @foreach($categoriesList as $cat)
                        @php
                            $catValue = $cat === 'All' ? '' : strtolower($cat);
                            $isActive = request('category') == $catValue || (request('category') == null && $cat === 'All');
                        @endphp
                        <button type="submit" name="category" value="{{ $catValue }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium {{ $isActive ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events ?? [] as $event)
            <x-event-card :event="$event" />
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-lg shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No events found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                <div class="mt-6">
                    <a href="{{ Route::has('events.index') ? route('events.index') : '#' }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Clear Filters
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    @if(isset($events) && method_exists($events, 'hasPages') && $events->hasPages())
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
