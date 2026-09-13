<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Coordinator Dashboard') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Manage your events, view capacity, and track registrations.</p>
            </div>
            <a href="{{ route('coordinator.events.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                + Create New Event
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-5 border-l-4 border-l-indigo-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Events</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['total_events'] ?? 0 }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-5 border-l-4 border-l-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Total Registrations</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['total_registrations'] ?? 0 }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-5 border-l-4 border-l-yellow-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Upcoming Events</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['upcoming_events'] ?? 0 }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-5 border-l-4 border-l-green-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Published Events</div>
                    <div class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['published_events'] ?? 0 }}</div>
                </div>
            </div>

            <!-- Recent Events -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Recent Events</h3>
                        <a href="{{ route('coordinator.events.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs font-semibold">View All &rarr;</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registrations</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($recentEvents ?? [] as $event)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">{{ $event->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                            <x-badge :type="$event->category">{{ ucfirst($event->category) }}</x-badge>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                            {{ $event->start_time ? $event->start_time->format('M d, Y') : 'TBA' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-700 dark:text-gray-300">
                                            {{ $event->registrations_count ?? 0 }} / {{ $event->capacity_limit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-badge :type="$event->status">{{ ucfirst($event->status) }}</x-badge>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                            <a href="{{ route('coordinator.events.edit', $event) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                            <a href="{{ route('coordinator.events.registrations', $event) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Registrations</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">No events found. Create one to get started!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
