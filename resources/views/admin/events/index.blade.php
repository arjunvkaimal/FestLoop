<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Global Event Moderation') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Review, moderate status, and manage all events created across the campus.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-semibold flex items-center gap-1">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
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

            <!-- Search & Filters -->
            <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, description, or venue..." class="w-full pl-10 pr-4 py-2 text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-44">
                        <select name="category" class="w-full py-2 text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Categories</option>
                            <option value="cultural" {{ request('category') === 'cultural' ? 'selected' : '' }}>Cultural</option>
                            <option value="technical" {{ request('category') === 'technical' ? 'selected' : '' }}>Technical</option>
                            <option value="sports" {{ request('category') === 'sports' ? 'selected' : '' }}>Sports</option>
                            <option value="workshop" {{ request('category') === 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="seminar" {{ request('category') === 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-40">
                        <select name="status" class="w-full py-2 text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'category', 'status']))
                            <a href="{{ route('admin.events.index') }}" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">Clear</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Events Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-300 uppercase tracking-wider text-xs font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Event</th>
                                <th scope="col" class="px-6 py-3.5">Coordinator</th>
                                <th scope="col" class="px-6 py-3.5">Venue & Schedule</th>
                                <th scope="col" class="px-6 py-3.5">Capacity</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($events as $event)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">
                                            <a href="{{ route('events.show', $event) }}" target="_blank" class="hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-1">
                                                {{ $event->title }}
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        </div>
                                        <div class="mt-1">
                                            <x-badge :type="$event->category">{{ ucfirst($event->category) }}</x-badge>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-gray-900 dark:text-white text-xs">{{ $event->coordinator->name ?? 'Unassigned' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $event->coordinator->email ?? '' }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 dark:text-gray-300">
                                        <div class="flex items-center gap-1 font-semibold text-gray-800 dark:text-gray-200">
                                            <span>📍</span> {{ $event->venue }}
                                        </div>
                                        <div class="mt-1 text-gray-500 dark:text-gray-400">
                                            <span>📅</span> {{ $event->start_time ? $event->start_time->format('M d, Y · g:i A') : 'TBA' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $event->registrations_count }}</span> / {{ $event->capacity_limit }}
                                        <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1.5 overflow-hidden">
                                            @php
                                                $pct = $event->capacity_limit > 0 ? min(100, round(($event->registrations_count / $event->capacity_limit) * 100)) : 0;
                                            @endphp
                                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Inline Status Change Form --}}
                                        <form method="POST" action="{{ route('admin.events.updateStatus', $event) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="text-xs py-1 px-2.5 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 font-semibold"
                                                onchange="this.form.submit()">
                                                <option value="published" {{ $event->status === 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="draft" {{ $event->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="cancelled" {{ $event->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this event? All participant registrations will also be removed.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline font-semibold px-2 py-1 rounded transition">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        No events found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($events->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
