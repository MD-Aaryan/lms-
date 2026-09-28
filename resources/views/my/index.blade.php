<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">My Books</h1>
                <p class="mt-1 text-sm text-slate-500">Everything you have borrowed, and what is due next.</p>
            </div>

            @if ($overdue > 0)
                <x-badge tone="rose">
                    <x-icon name="alert" size="xs" /> {{ $overdue }} overdue
                </x-badge>
            @endif
        </div>
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat label="With me now" :value="$active->count()" icon="book" />
        <x-stat label="Overdue" :value="$overdue" icon="alert" :tone="$overdue > 0 ? 'rose' : 'slate'"
                :hint="$overdue > 0 ? 'Fined per late day' : null" />
        <x-stat label="Total fine" :value="'रू'.number_format((float) $totalFine, 0)" icon="receipt"
                :tone="$totalFine > 0 ? 'rose' : 'slate'"
                :hint="$totalFine > 0 ? 'Pay at the desk' : 'Nothing due'" />
    </div>

    <section class="mt-8">
        <x-card title="Currently with me" subtitle="Bring the copy back to the desk before the due date." body-class="p-0">
            <x-slot name="actions">
                <x-badge tone="slate">{{ $active->count() }} on loan</x-badge>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[34rem] text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            <th scope="col" class="px-5 py-3 text-start">Book</th>
                            <th scope="col" class="hidden px-5 py-3 text-start sm:table-cell">Issued</th>
                            <th scope="col" class="px-5 py-3 text-start">Due</th>
                            <th scope="col" class="px-5 py-3 text-start">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($active as $loan)
                            <tr class="{{ $loan->isOverdue() ? 'bg-rose-50/60' : '' }} transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900">{{ $loan->book->title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $loan->book->author }}</p>
                                </td>
                                <td class="hidden px-5 py-4 text-slate-600 sm:table-cell">{{ $loan->issued_at->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $loan->due_at->format('d M Y') }}</td>
                                <td class="px-5 py-4">
                                    @if ($loan->isOverdue())
                                        <x-badge tone="rose">
                                            {{ $loan->daysLate() }} days late &middot; fine रू{{ $loan->currentFine() }}
                                        </x-badge>
                                    @else
                                        <x-badge tone="emerald">{{ $loan->daysLeft() }} days left</x-badge>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <x-icon name="book" size="xl" class="mx-auto text-slate-300" />
                                    <p class="mt-3 font-medium text-slate-700">No books with you right now</p>
                                    <p class="mt-1 text-sm text-slate-500">Ask at the desk to borrow a title.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </section>

    <section class="mt-8">
        <x-card title="Returned history" subtitle="What you have already brought back." body-class="p-0">
            <x-slot name="actions">
                <x-badge tone="slate">{{ $history->count() }} returned</x-badge>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[30rem] text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                            <th scope="col" class="px-5 py-3 text-start">Book</th>
                            <th scope="col" class="hidden px-5 py-3 text-start sm:table-cell">Issued</th>
                            <th scope="col" class="px-5 py-3 text-start">Returned</th>
                            <th scope="col" class="px-5 py-3 text-end">Fine</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($history as $loan)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900">{{ $loan->book->title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $loan->book->author }}</p>
                                </td>
                                <td class="hidden px-5 py-4 text-slate-600 sm:table-cell">{{ $loan->issued_at->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $loan->returned_at->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-end">
                                    @if ($loan->fine > 0)
                                        <x-badge tone="rose">रू{{ number_format((float) $loan->fine, 0) }}</x-badge>
                                    @else
                                        <span class="text-slate-400">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <x-icon name="receipt" size="xl" class="mx-auto text-slate-300" />
                                    <p class="mt-3 font-medium text-slate-700">Nothing returned yet</p>
                                    <p class="mt-1 text-sm text-slate-500">Finished loans will show up here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </section>
</x-app-layout>
