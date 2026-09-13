<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Registrations for: ') }} {{ $event->title }}
                </h2>
                <a href="{{ route('coordinator.events.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block font-semibold">&larr; Back to Events</a>
            </div>
            <a href="{{ route('coordinator.events.registrations.export', $event) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-4 border-l-4 border-l-gray-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Capacity</div>
                    <div class="mt-1 text-2xl font-extrabold text-gray-900 dark:text-white">{{ $event->capacity_limit }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-4 border-l-4 border-l-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Total</div>
                    <div class="mt-1 text-2xl font-extrabold text-gray-900 dark:text-white">{{ $registrations->total() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-4 border-l-4 border-l-green-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Confirmed</div>
                    <div class="mt-1 text-2xl font-extrabold text-green-600 dark:text-green-400">{{ $event->registrations()->where('status', 'confirmed')->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-4 border-l-4 border-l-yellow-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Pending</div>
                    <div class="mt-1 text-2xl font-extrabold text-yellow-600 dark:text-yellow-400">{{ $event->registrations()->where('status', 'pending')->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl p-4 border-l-4 border-l-red-500">
                    <div class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">Cancelled</div>
                    <div class="mt-1 text-2xl font-extrabold text-red-600 dark:text-red-400">{{ $event->registrations()->where('status', 'cancelled')->count() }}</div>
                </div>
            </div>

            <!-- Registrations List -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm rounded-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Participant Name</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email Address</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registered At</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse($registrations as $registration)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-white">{{ $registration->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">{{ $registration->user->email ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                        {{ $registration->created_at ? $registration->created_at->format('M d, Y · g:i A') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge :type="$registration->status">{{ ucfirst($registration->status) }}</x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">No registrations for this event yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($registrations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $registrations->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
