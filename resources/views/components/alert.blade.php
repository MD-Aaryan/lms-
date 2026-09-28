{{-- Dismissible message box. Types: success | danger | warning | info --}}
@props(['type' => 'info'])

@php
    $styles = [
        'success' => ['border-emerald-200 bg-emerald-50 text-emerald-900', 'check-circle', 'text-emerald-500'],
        'danger' => ['border-rose-200 bg-rose-50 text-rose-900', 'x-circle', 'text-rose-500'],
        'warning' => ['border-amber-200 bg-amber-50 text-amber-900', 'alert', 'text-amber-500'],
        'info' => ['border-sky-200 bg-sky-50 text-sky-900', 'info', 'text-sky-500'],
    ][$type] ?? ['border-slate-200 bg-slate-50 text-slate-800', 'info', 'text-slate-400'];
@endphp

<div x-data="{ open: true }" x-show="open" role="status"
     {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-xl border px-4 py-3 text-sm shadow-sm '.$styles[0]]) }}>
    <x-icon :name="$styles[1]" class="mt-0.5 {{ $styles[2] }}" />

    <div class="flex-1">{{ $slot }}</div>

    <button type="button" x-on:click="open = false" aria-label="Dismiss message"
            class="rounded p-0.5 opacity-50 transition hover:opacity-100">
        <x-icon name="close" size="sm" />
    </button>
</div>
