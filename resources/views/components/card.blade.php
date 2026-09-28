{{--
    White panel. Usage:
    <x-card title="Issue a book" subtitle="…">…</x-card>
    <x-card body-class="p-0">…</x-card>            <!-- e.g. when the body is a table -->
    <x-card title="Books"><x-slot name="actions"><a …>New</a></x-slot>…</x-card>
--}}
@props(['title' => null, 'subtitle' => null, 'bodyClass' => 'p-5'])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-sm']) }}>
    @if ($title || isset($actions))
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
            <div>
                @if ($title)
                    <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
                @endif
                @if ($subtitle)
                    <p class="mt-0.5 text-sm text-slate-500">{{ $subtitle }}</p>
                @endif
            </div>

            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div class="{{ $bodyClass }}">{{ $slot }}</div>
</div>
