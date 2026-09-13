<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Registrations for: ') }} {{ $event->title }}
                </h2>
                <a href="{{ route('coordinator.events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 mt-1 inline-block">&larr; Back to Events</a>
            </div>
            <a href="{{ route('coordinator.events.registrations.export', $event) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-gray-500">
                    <div class="text-gray-500 text-xs font-medium uppercase tracking-wider">Capacity</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $event->capacity_limit }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $registrations->total() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-green-500">
                    <div class="text-gray-500 text-xs font-medium uppercase tracking-wider">Confirmed</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $event->registrations()->where('status', 'confirmed')->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-yellow-500">
                    <div class="text-gray-500 text-xs font-medium uppercase tracking-wider">Pending</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $event->registrations()->where('status', 'pending')->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 border-l-4 border-red-500">
                    <div class="text-gray-500 text-xs font-medium uppercase tracking-wider">Cancelled</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $event->registrations()->where('status', 'cancelled')->count() }}</div>
                </div>
            </div>

            <!-- Registrations List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($registrations as $registration)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $registration->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $registration->user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $registration->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($registration->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No registrations for this event yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $registrations->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
