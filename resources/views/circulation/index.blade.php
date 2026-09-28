@use('App\Models\IssueRecord')

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Issue &amp; Return</h1>
                <p class="mt-1 text-sm text-slate-500">Hand a copy out, or take one back and settle the fine.</p>
            </div>

            <x-badge tone="slate">
                <x-icon name="clock" size="xs" />
                {{ IssueRecord::LOAN_DAYS }} days &middot; fine रू{{ IssueRecord::FINE_PER_DAY }} per day
            </x-badge>
        </div>
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-stat label="Titles on the shelf" :value="$books->count()" icon="check" tone="emerald" />
        <x-stat label="Copies on loan" :value="$pending->count()" icon="swap" tone="sky" />
        <x-stat label="Overdue" :value="$overdue" icon="alert" :tone="$overdue > 0 ? 'rose' : 'slate'"
                :hint="$overdue > 0 ? 'Fine is adding up' : null" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-5">
        <form method="POST" action="{{ route('circulation.store') }}" class="lg:col-span-2">
            @csrf

            <x-card title="Issue a book" subtitle="Pick the member, then the copy they are taking." body-class="space-y-5 p-5">
                <div class="space-y-1.5">
                    <x-input-label for="user_id" value="Member" />
                    <x-select id="user_id" name="user_id" required>
                        <option value="">Select a member…</option>
                        @foreach ($users as $member)
                            <option value="{{ $member->id }}" @selected(old('user_id') == $member->id)>
                                {{ $member->name }} — {{ $member->email }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('user_id')" />
                    @if ($users->isEmpty())
                        <p class="text-xs text-amber-700">There are no members yet — add one first.</p>
                    @endif
                </div>

                <div class="space-y-1.5">
                    <x-input-label for="book_id" value="Book" />
                    <x-select id="book_id" name="book_id" required>
                        <option value="">Select a book…</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                                {{ $book->title }} — {{ $book->author }} ({{ $book->stock }} on shelf)
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('book_id')" />
                    @if ($books->isEmpty())
                        <p class="text-xs text-amber-700">Nothing is on the shelf right now — every copy is out on loan.</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-3 border-t border-slate-200 pt-5">
                    <x-primary-button :disabled="$books->isEmpty() || $users->isEmpty()">
                        <x-icon name="swap" size="sm" /> Issue book
                    </x-primary-button>
                    <p class="text-xs text-slate-500">Due {{ today()->addDays(IssueRecord::LOAN_DAYS)->format('d M Y') }}</p>
                </div>
            </x-card>
        </form>

        <div class="lg:col-span-3">
            <x-card title="On loan now" subtitle="Take a copy back to return it to the shelf." body-class="p-0">
                <x-slot name="actions">
                    <x-badge :tone="$overdue > 0 ? 'rose' : 'slate'">
                        {{ $pending->count() }} out &middot; {{ $overdue }} overdue
                    </x-badge>
                </x-slot>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[38rem] text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                                <th scope="col" class="px-5 py-3 text-start">Book</th>
                                <th scope="col" class="px-5 py-3 text-start">Member</th>
                                <th scope="col" class="hidden px-5 py-3 text-start sm:table-cell">Issued</th>
                                <th scope="col" class="px-5 py-3 text-start">Due</th>
                                <th scope="col" class="px-5 py-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($pending as $loan)
                                <tr class="{{ $loan->isOverdue() ? 'bg-rose-50/60' : '' }} transition hover:bg-slate-50/70">
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-slate-900">{{ $loan->book->title }}</p>
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $loan->book->author }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-slate-600">{{ $loan->user->name }}</td>
                                    <td class="hidden px-5 py-4 text-slate-600 sm:table-cell">{{ $loan->issued_at->format('d M Y') }}</td>
                                    <td class="px-5 py-4">
                                        <p class="text-slate-600">{{ $loan->due_at->format('d M Y') }}</p>
                                        @if ($loan->isOverdue())
                                            <x-badge tone="rose" class="mt-1">
                                                {{ $loan->daysLate() }} days late &middot; रू{{ $loan->currentFine() }}
                                            </x-badge>
                                        @else
                                            <x-badge tone="emerald" class="mt-1">{{ $loan->daysLeft() }} days left</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-end">
                                        <form method="POST" action="{{ route('circulation.return', $loan) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                                                <x-icon name="check" size="xs" /> Take back
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <x-icon name="check-circle" size="xl" class="mx-auto text-slate-300" />
                                        <p class="mt-3 font-medium text-slate-700">Nothing is out on loan</p>
                                        <p class="mt-1 text-sm text-slate-500">Every copy is back on the shelf.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
