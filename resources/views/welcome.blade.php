@extends('layouts.public')

@section('content')
<div class="relative bg-gradient-to-r from-indigo-600 to-purple-700 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold text-white tracking-tight mb-4">
            FestLoop
        </h1>
        <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-10">
            Your Gateway to College Festivals & Events. Discover, register, and experience the best cultural, technical, and sports events on campus.
        </p>
        <div class="flex justify-center space-x-4">
            <a href="{{ Route::has('events.index') ? route('events.index') : '#' }}" class="px-8 py-3 bg-white text-indigo-600 font-semibold rounded-md shadow-md hover:bg-gray-50 transition duration-150 ease-in-out">
                Browse Events
            </a>
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-indigo-500 text-white font-semibold rounded-md shadow-md hover:bg-indigo-400 transition duration-150 ease-in-out border border-indigo-400">
                    Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="px-8 py-3 bg-indigo-500 text-white font-semibold rounded-md shadow-md hover:bg-indigo-400 transition duration-150 ease-in-out border border-indigo-400">
                    Get Started
                </a>
            @endauth
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Featured Events</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @if(isset($featuredEvents) && count($featuredEvents) > 0)
            @foreach($featuredEvents as $event)
                <x-event-card :event="$event" />
            @endforeach
        @else
            <p class="col-span-full text-center text-gray-500 py-8">No featured events at the moment. Check back later!</p>
        @endif
    </div>
</div>

<div class="bg-gray-50 py-16 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $categories = [
                    ['id' => 'cultural', 'name' => 'Cultural', 'icon' => '🎭'],
                    ['id' => 'technical', 'name' => 'Technical', 'icon' => '💻'],
                    ['id' => 'sports', 'name' => 'Sports', 'icon' => '⚽'],
                    ['id' => 'workshop', 'name' => 'Workshop', 'icon' => '🛠️'],
                    ['id' => 'seminar', 'name' => 'Seminar', 'icon' => '🎤'],
                    ['id' => 'other', 'name' => 'Other', 'icon' => '✨'],
                ];
            @endphp
            @foreach($categories as $category)
                <a href="{{ Route::has('events.index') ? route('events.index', ['category' => $category['id']]) : '#' }}" class="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md hover:border-indigo-300 border border-transparent transition duration-150 group">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">{{ $category['icon'] }}</div>
                    <h3 class="font-semibold text-gray-800">{{ $category['name'] }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
