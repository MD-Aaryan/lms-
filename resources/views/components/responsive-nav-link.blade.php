@props(['active' => false])

@php
    $classes = $active
        ? 'bg-slate-900 text-white'
        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => 'block rounded-lg px-3 py-2 text-sm font-medium transition '.$classes]) }}
   @if ($active) aria-current="page" @endif>
    {{ $slot }}
</a>
