<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $registration->event->title }} - Event Pass</title>
    
    <!-- Anti-FOUC Theme Initialization -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-area {
                box-shadow: none !important;
                border: 2px solid #e5e7eb !important;
                background: white !important;
                color: black !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4 transition-colors">

    <div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden print-area border border-gray-200 dark:border-gray-700">
        <!-- Header -->
        <div class="bg-indigo-600 px-6 py-5 text-center">
            <h1 class="text-2xl font-black text-white tracking-widest uppercase">FestLoop</h1>
            <p class="text-indigo-200 text-xs mt-1 font-medium">Official Event Entrance Pass</p>
        </div>
        
        <!-- Content -->
        <div class="p-6 sm:p-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">{{ $registration->event->title }}</h2>
                <div class="mt-2">
                    <x-badge :type="$registration->status">{{ strtoupper($registration->status) }}</x-badge>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider font-semibold">Attendee</p>
                    <p class="font-bold text-gray-900 dark:text-white text-base">{{ $registration->user->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $registration->user->email }}</p>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                    <p class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider font-semibold">Date & Time</p>
                    <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $registration->event->start_time->format('l, F j, Y') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $registration->event->start_time->format('g:i A') }} - {{ $registration->event->end_time->format('g:i A') }}</p>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                    <p class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider font-semibold">Venue</p>
                    <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $registration->event->venue }}</p>
                </div>

                <div class="border-t border-dashed border-gray-300 dark:border-gray-600 pt-4 mt-6 text-center">
                    <p class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold mb-1">Registration Identifier</p>
                    <p class="font-mono text-base font-extrabold text-indigo-600 dark:text-indigo-400 tracking-wider">
                        {{ sprintf('REG-%06d', $registration->id) }}
                    </p>
                    <div class="mt-4">
                        <div class="w-28 h-28 mx-auto bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl flex items-center justify-center">
                            <span class="text-gray-400 text-xs font-mono font-medium">QR CODE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="fixed bottom-6 left-0 right-0 flex justify-center no-print gap-3">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition duration-200 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Pass
        </button>
    </div>

</body>
</html>
