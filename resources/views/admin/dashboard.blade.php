<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ __('Admin Control Center') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Platform-wide statistics, user roles, and event administration.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manage Users
                </a>
                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 border border-transparent rounded-lg text-xs font-semibold text-white hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Moderate Events
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
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

            <!-- Platform Stats Grid -->
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span>📊</span> Platform KPI Breakdown
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    {{-- Total Users --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl p-5 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Users</p>
                                <p class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ $stats['total_users'] }}</p>
                            </div>
                            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Attendees: <strong class="text-gray-800 dark:text-gray-200">{{ $stats['total_attendees'] }}</strong></span>
                            <span>Coordinators: <strong class="text-gray-800 dark:text-gray-200">{{ $stats['total_coordinators'] }}</strong></span>
                            <span>Admins: <strong class="text-gray-800 dark:text-gray-200">{{ $stats['total_admins'] }}</strong></span>
                        </div>
                    </div>

                    {{-- Total Events --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl p-5 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Events</p>
                                <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 mt-2">{{ $stats['total_events'] }}</p>
                            </div>
                            <div class="p-3 bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Published: <strong class="text-green-600 dark:text-green-400">{{ $stats['published_events'] }}</strong></span>
                            <span>Draft: <strong class="text-yellow-600 dark:text-yellow-400">{{ $stats['draft_events'] }}</strong></span>
                            <span>Cancelled: <strong class="text-red-500">{{ $stats['cancelled_events'] }}</strong></span>
                        </div>
                    </div>

                    {{-- Registrations --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl p-5 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Registrations</p>
                                <p class="text-3xl font-extrabold text-green-600 dark:text-green-400 mt-2">{{ $stats['total_registrations'] }}</p>
                            </div>
                            <div class="p-3 bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Confirmed: <strong class="text-green-600 dark:text-green-400">{{ $stats['confirmed_registrations'] }}</strong></span>
                            <span>Cancelled: <strong class="text-red-500">{{ $stats['cancelled_registrations'] }}</strong></span>
                        </div>
                    </div>

                    {{-- Quick Links Card --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-xl p-5 text-white shadow-sm flex flex-col justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider font-semibold opacity-80">Quick Nav</p>
                            <h4 class="text-lg font-extrabold mt-1">Admin Tools</h4>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('admin.users.index') }}" class="flex-1 text-center py-2 px-3 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold transition backdrop-blur-sm">
                                Users
                            </a>
                            <a href="{{ route('admin.events.index') }}" class="flex-1 text-center py-2 px-3 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold transition backdrop-blur-sm">
                                Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2-Column Tables (Recent Users & Recent Registrations) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- Recent Users --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Recently Registered Users
                        </h4>
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">View All &rarr;</a>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentUsers as $user)
                            <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div>
                                    <x-badge :type="$user->role">{{ ucfirst($user->role) }}</x-badge>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 text-sm">No users registered yet.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Registrations --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Recent Registrations
                        </h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Live feed</span>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentRegistrations as $reg)
                            <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $reg->user->name ?? 'Unknown User' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $reg->event->title ?? 'Unknown Event' }}</p>
                                </div>
                                <div class="text-right">
                                    <x-badge :type="$reg->status">{{ ucfirst($reg->status) }}</x-badge>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $reg->registered_at ? $reg->registered_at->diffForHumans() : '' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 text-sm">No registrations recorded yet.</div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
