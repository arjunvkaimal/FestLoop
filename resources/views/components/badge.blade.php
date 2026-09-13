@props(['type' => 'default'])

@php
    $colors = match (strtolower($type)) {
        'confirmed', 'cultural' => 'bg-green-100 text-green-800',
        'pending', 'workshop' => 'bg-yellow-100 text-yellow-800',
        'cancelled' => 'bg-red-100 text-red-800',
        'technical' => 'bg-blue-100 text-blue-800',
        'sports' => 'bg-orange-100 text-orange-800',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colors}"]) }}>
    {{ $slot }}
</span>
