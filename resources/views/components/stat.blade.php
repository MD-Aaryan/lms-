{{-- Summary tile: <x-stat label="On loan" :value="3" icon="swap" tone="sky" /> --}}
@props(['label', 'value', 'icon' => null, 'tone' => 'slate', 'hint' => null])

@php
    $tones = [
        'slate' => 'bg-slate-100 text-slate-600',
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'rose' => 'bg-rose-100 text-rose-700',
        'sky' => 'bg-sky-100 text-sky-700',
        'violet' => 'bg-violet-100 text-violet-700',
    ];
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
            @if ($hint)
                <p class="mt-1 text-xs text-slate-400">{{ $hint }}</p>
            @endif
        </div>

        @if ($icon)
            <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $tones[$tone] ?? $tones['slate'] }}">
                <x-icon :name="$icon" />
            </span>
        @endif
    </div>
</div>
