<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('User & Role Management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">View all platform members, change permissions, and oversee accounts.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Search & Filters -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name or email..." class="w-full pl-10 pr-4 py-2 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-48">
                        <select name="role" class="w-full py-2 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Roles</option>
                            <option value="attendee" {{ request('role') === 'attendee' ? 'selected' : '' }}>Attendees</option>
                            <option value="coordinator" {{ request('role') === 'coordinator' ? 'selected' : '' }}>Coordinators</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admins</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'role']))
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50/75 text-gray-500 uppercase tracking-wider text-xs font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">User</th>
                                <th scope="col" class="px-6 py-3.5">Current Role</th>
                                <th scope="col" class="px-6 py-3.5">Activity</th>
                                <th scope="col" class="px-6 py-3.5">Joined</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                                    {{ $user->name }}
                                                    @if($user->id === auth()->id())
                                                        <span class="text-[10px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded font-normal">(You)</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Inline Role Form --}}
                                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="text-xs py-1 px-2 border-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500 
                                                @if($user->role === 'admin') bg-purple-50 text-purple-800 font-semibold border-purple-200
                                                @elseif($user->role === 'coordinator') bg-blue-50 text-blue-800 font-semibold border-blue-200
                                                @else bg-gray-50 text-gray-700 font-medium @endif"
                                                onchange="this.form.submit()">
                                                <option value="attendee" {{ $user->role === 'attendee' ? 'selected' : '' }}>Attendee</option>
                                                <option value="coordinator" {{ $user->role === 'coordinator' ? 'selected' : '' }}>Coordinator</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </form>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        <div>Events: <strong>{{ $user->coordinated_events_count }}</strong></div>
                                        <div>Registrations: <strong>{{ $user->registrations_count }}</strong></div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                        {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete user \'{{ $user->name }}\'? This will also delete their registrations and coordinated events.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-medium px-2 py-1 rounded hover:bg-red-50 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Protected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                        No users matching the criteria found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
