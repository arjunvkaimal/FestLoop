<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $registration->event->title }} - Event Pass</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-area {
                box-shadow: none !important;
                border: 2px solid #e5e7eb !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white shadow-xl rounded-xl overflow-hidden print-area border border-gray-200">
        <!-- Header -->
        <div class="bg-indigo-600 px-6 py-4 text-center">
            <h1 class="text-2xl font-bold text-white tracking-widest uppercase">FestLoop</h1>
            <p class="text-indigo-200 text-sm mt-1">Official Event Pass</p>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">{{ $registration->event->title }}</h2>
                <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full border border-green-200">
                    {{ strtoupper($registration->status) }}
                </span>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Attendee</p>
                    <p class="font-medium text-gray-900">{{ $registration->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $registration->user->email }}</p>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Date & Time</p>
                    <p class="font-medium text-gray-900">{{ $registration->event->start_time->format('l, F j, Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $registration->event->start_time->format('g:i A') }} - {{ $registration->event->end_time->format('g:i A') }}</p>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Venue</p>
                    <p class="font-medium text-gray-900">{{ $registration->event->venue }}</p>
                </div>

                <div class="border-t border-dashed border-gray-300 pt-4 mt-6 text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Registration ID</p>
                    <p class="font-mono text-lg font-bold text-gray-800 tracking-wider">
                        {{ sprintf('REG-%06d', $registration->id) }}
                    </p>
                    <div class="mt-4">
                        <!-- Placeholder for a QR code if needed later -->
                        <div class="w-32 h-32 mx-auto bg-gray-100 border border-gray-200 rounded flex items-center justify-center">
                            <span class="text-gray-400 text-sm">QR Code</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="fixed bottom-8 left-0 right-0 flex justify-center no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Pass
        </button>
    </div>

</body>
</html>
