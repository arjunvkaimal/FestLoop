@props(['type' => 'default'])

@php
    $colors = match (strtolower($type)) {
        'confirmed', 'cultural' => 'bg-green-100 text-green-800 dark:bg-green-950/60 dark:text-green-300 border border-green-200 dark:border-green-800/40',
        'pending', 'workshop' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-950/60 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800/40',
        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-950/60 dark:text-red-300 border border-red-200 dark:border-red-800/40',
        'technical' => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/40',
        'sports' => 'bg-orange-100 text-orange-800 dark:bg-orange-950/60 dark:text-orange-300 border border-orange-200 dark:border-orange-800/40',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700/60 dark:text-gray-300 border border-gray-200 dark:border-gray-600/40',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {$colors}"]) }}>
    {{ $slot }}
</span>
