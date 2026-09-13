<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FestLoop') }}</title>

        <!-- Anti-FOUC Theme Initialization -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200 sticky top-0 z-40 backdrop-blur-md bg-white/95 dark:bg-gray-800/95">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ url('/') }}" class="text-2xl font-extrabold text-indigo-600 dark:text-indigo-400 flex items-center gap-1.5">
                                    <span>🎪</span> FestLoop
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('events.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('events.*') ? 'border-indigo-500 text-gray-900 dark:text-white font-semibold' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} text-sm font-medium transition">
                                    Events
                                </a>
                            </div>
                        </div>

                        <!-- Right: Theme Toggle & Auth buttons -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                            <x-theme-toggle />

                            @auth
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isCoordinator() ? route('coordinator.dashboard') : route('dashboard')) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                                        Log Out
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <!-- Hamburger & Mobile Theme Toggle -->
                        <div class="-mr-2 flex items-center sm:hidden space-x-2">
                            <x-theme-toggle />

                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Mobile Menu -->
                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="pt-2 pb-3 space-y-1">
                        <a href="{{ route('events.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('events.*') ? 'border-indigo-500 text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50' : 'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }} text-base font-medium">
                            Events
                        </a>
                    </div>

                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                        @auth
                            <div class="px-4 mb-2">
                                <div class="font-semibold text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="space-y-1">
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isCoordinator() ? route('coordinator.dashboard') : route('dashboard')) }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="space-y-1 px-4">
                                <a href="{{ route('login') }}" class="block py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block py-2 text-base font-medium text-indigo-600 dark:text-indigo-400 font-semibold">
                                        Register
                                    </a>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-900 text-gray-400 border-t border-gray-800 mt-auto">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
                    <p class="font-medium text-white flex items-center gap-1"><span>🎪</span> FestLoop</p>
                    <p>&copy; {{ date('Y') }} FestLoop. Festival & College Event Management Platform.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
