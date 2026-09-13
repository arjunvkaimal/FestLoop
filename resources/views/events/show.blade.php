@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        {{-- Hero Banner --}}
        @if($event->banner_image)
            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-64 md:h-96 object-cover">
        @else
            <div class="w-full h-64 md:h-96 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                <span class="text-white text-3xl font-bold">{{ $event->title }}</span>
            </div>
        @endif

        <div class="p-6 md:p-10">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <x-badge :type="$event->category">{{ ucfirst($event->category) }}</x-badge>
                        <x-badge :type="$event->status">{{ ucfirst($event->status) }}</x-badge>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">{{ $event->title }}</h1>
                    <p class="text-gray-500 text-sm">Organized by {{ $event->coordinator->name }}</p>
                </div>

                {{-- Registration Sidebar --}}
                <div class="flex-shrink-0 bg-gray-50 p-6 rounded-lg border border-gray-100 min-w-64">
                    @auth
                        @if($isRegistered)
                            <div class="text-center">
                                <div class="text-green-600 font-semibold mb-2 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    You are registered
                                </div>
                                @php
                                    $existingRegistration = auth()->user()->registrations()->where('event_id', $event->id)->where('status', '!=', 'cancelled')->first();
                                @endphp
                                @if($existingRegistration)
                                    <p class="text-sm text-gray-500 mb-4">Status: {{ ucfirst($existingRegistration->status) }}</p>
                                    <form action="{{ route('registrations.destroy', $existingRegistration) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-md hover:bg-red-100 transition text-sm font-medium" onclick="return confirm('Are you sure you want to cancel your registration?')">
                                            Cancel Registration
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @elseif($event->status === 'cancelled')
                            <div class="text-center text-red-600 font-semibold py-2">
                                Event Cancelled
                            </div>
                        @elseif($event->is_full)
                            <div class="text-center text-orange-600 font-semibold py-2">
                                Registration Full
                            </div>
                        @elseif($event->registration_deadline->isPast())
                            <div class="text-center text-gray-600 font-semibold py-2">
                                Registration Closed
                            </div>
                        @else
                            <form action="{{ route('registrations.store', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-semibold shadow-sm">
                                    Register Now
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-2 text-center">
                                {{ $event->capacity_limit - $event->registrations_count }} spots left
                            </p>
                        @endif
                    @else
                        <div class="text-center">
                            <p class="text-gray-600 mb-4 text-sm">Please log in to register for this event.</p>
                            <a href="{{ route('login') }}" class="block w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-medium text-center">
                                Login to Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Details Grid --}}
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-b border-gray-100 py-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div>
                                <span class="block font-medium text-gray-900">Date & Time</span>
                                <span class="text-gray-600">{{ $event->start_time->format('l, F j, Y \a\t g:i A') }}</span>
                                <span class="text-gray-500 block text-sm">to {{ $event->end_time->format('F j, Y g:i A') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <span class="block font-medium text-gray-900">Venue</span>
                                <span class="text-gray-600">{{ $event->venue }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Info</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <span class="block font-medium text-gray-900">Deadline</span>
                                <span class="text-gray-600">{{ $event->registration_deadline->format('F j, Y g:i A') }}</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <div>
                                <span class="block font-medium text-gray-900">Capacity</span>
                                <span class="text-gray-600">{{ $event->capacity_limit - $event->registrations_count }} spots remaining out of {{ $event->capacity_limit }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Description --}}
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">About this Event</h3>
                <div class="prose max-w-none text-gray-700">
                    {!! \Illuminate\Support\Str::markdown($event->description) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
            &larr; Back to Events
        </a>
    </div>
</div>
@endsection
