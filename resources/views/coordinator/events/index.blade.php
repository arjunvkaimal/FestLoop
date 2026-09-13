<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('My Events') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">All festival and college events coordinated by you.</p>
            </div>
            <a href="{{ route('coordinator.events.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                + Create New Event
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-950/50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Schedule</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Capacity</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($events as $event)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-white">
                                        <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition" target="_blank">
                                            {{ $event->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                        <x-badge :type="$event->category">{{ ucfirst($event->category) }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                        {{ $event->start_time ? $event->start_time->format('M d, Y · g:i A') : 'TBA' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $event->registrations_count ?? 0 }} / {{ $event->capacity_limit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge :type="$event->status">{{ ucfirst($event->status) }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                        <a href="{{ route('coordinator.events.edit', $event) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                        <a href="{{ route('coordinator.events.registrations', $event) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Registrations</a>
                                        
                                        <form action="{{ route('coordinator.events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">No events found.</td>
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
