{{--
    Tiny icon set. Usage: <x-icon name="book" size="sm" class="text-slate-400" />
    Sizes: xs | sm | md | lg | xl
--}}
@props(['name', 'size' => 'md'])

@php
    $sizes = [
        'xs' => 'h-3.5 w-3.5',
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
        'xl' => 'h-8 w-8',
    ];

    $paths = match ($name) {
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16" />',
        'close' => '<path d="M6 6l12 12M18 6L6 18" />',
        'search' => '<path d="M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14ZM20 20l-4.3-4.3" />',
        'plus' => '<path d="M12 5v14M5 12h14" />',
        'edit' => '<path d="M4 20h4l10-10-4-4L4 16v4ZM14 6l4 4" />',
        'trash' => '<path d="M5 7h14M9 7V5h6v2M7 7l1 12h8l1-12M10 11v5M14 11v5" />',
        'book' => '<path d="M12 7v12M12 7a6 6 0 0 0-7-1v12a6 6 0 0 1 7 1M12 7a6 6 0 0 1 7-1v12a6 6 0 0 0-7 1" />',
        'users' => '<path d="M16 19v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1M9.5 10a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M17 4.6a3.5 3.5 0 0 1 0 6.8M21 19v-1a4 4 0 0 0-3-3.9" />',
        'user' => '<path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8M4 21a8 8 0 0 1 16 0" />',
        'swap' => '<path d="M7 8h12M16 5l3 3-3 3M17 16H5M8 13l-3 3 3 3" />',
        'check' => '<path d="M5 13l4 4L19 7" />',
        'check-circle' => '<path d="M9 12.5l2.5 2.5 3-6M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />',
        'x-circle' => '<path d="M15 9l-6 6M9 9l6 6M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />',
        'alert' => '<path d="M12 9v4M12 16.5h.01M10.3 3.9 2.6 17.4a2 2 0 0 0 1.7 3.1h15.4a2 2 0 0 0 1.7-3.1L13.7 3.9a2 2 0 0 0-3.4 0Z" />',
        'info' => '<path d="M12 8h.01M11 12h1v5h1M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />',
        'calendar' => '<path d="M8 3v4M16 3v4M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />',
        'clock' => '<path d="M12 8v4l3 2M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />',
        'receipt' => '<path d="M7 3h10v18l-2.5-1.5L12 21l-2.5-1.5L7 21V3ZM10 8h4M10 12h4" />',
        'logout' => '<path d="M15 12H4M8 8l-4 4 4 4M10 4h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-7" />',
        default => '<path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />',
    };
@endphp

<svg {{ $attributes->merge([
    'class' => 'shrink-0 '.($sizes[$size] ?? $sizes['md']),
    'fill' => 'none',
    'viewBox' => '0 0 24 24',
    'stroke' => 'currentColor',
    'stroke-width' => '1.7',
    'stroke-linecap' => 'round',
    'stroke-linejoin' => 'round',
    'aria-hidden' => 'true',
]) }}>
    {!! $paths !!}
</svg>
