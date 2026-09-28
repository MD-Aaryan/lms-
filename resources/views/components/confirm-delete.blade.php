{{--
    Destructive action button with a styled confirmation dialog (no native confirm()).
    If JavaScript is unavailable the plain form still submits.

    <x-confirm-delete :action="route('books.destroy', $book)" title="Delete this book?" message="…" />
--}}
@props([
    'action',
    'title' => 'Are you sure?',
    'message' => 'This cannot be undone.',
    'label' => 'Delete',
])

<div x-data="{ confirming: false }" class="inline-flex">
    <form method="POST" action="{{ $action }}" x-ref="deleteForm" x-on:submit.prevent="confirming = true">
        @csrf
        @method('DELETE')

        <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-md border border-rose-200 bg-white px-2.5 py-1.5 text-xs font-medium text-rose-700 shadow-sm transition hover:bg-rose-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400">
            <x-icon name="trash" size="xs" />
            {{ $label }}
        </button>
    </form>

    <div x-cloak x-show="confirming" class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center">
        <div x-show="confirming" x-transition.opacity class="fixed inset-0 bg-slate-900/40"
             x-on:click="confirming = false"></div>

        <div x-show="confirming" x-transition role="dialog" aria-modal="true"
             class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <x-icon name="alert" />
                </span>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $message }}</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button type="button" x-on:click="confirming = false">Cancel</x-secondary-button>
                <x-danger-button type="button" x-on:click="$refs.deleteForm.submit()">Yes, delete</x-danger-button>
            </div>
        </div>
    </div>
</div>
