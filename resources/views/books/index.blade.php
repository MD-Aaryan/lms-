<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Books</h1>
                <p class="mt-1 text-sm text-slate-500">Manage the catalogue and keep an eye on what is out on loan.</p>
            </div>

            <a href="{{ route('books.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2">
                <x-icon name="plus" size="sm" /> New Book
            </a>
        </div>
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat label="Titles" :value="$stats['titles']" icon="book" />
        <x-stat label="Copies on shelf" :value="$stats['onShelf']" icon="check" tone="emerald" />
        <x-stat label="On loan" :value="$stats['onLoan']" icon="swap" tone="sky" />
        <x-stat label="Overdue" :value="$stats['overdue']" icon="alert" :tone="$stats['overdue'] > 0 ? 'rose' : 'slate'"
                :hint="$stats['overdue'] > 0 ? 'Follow up at the desk' : null" />
    </div>

    <form method="GET" action="{{ route('books.index') }}" class="mt-8 flex flex-wrap items-center gap-3">
        <div class="relative min-w-64 flex-1">
            <x-icon name="search" size="sm" class="pointer-events-none absolute start-3 top-1/2 -translate-y-1/2 text-slate-400" />
            <input type="search" name="q" value="{{ $q }}" autofocus
                   placeholder="Search by title, author or ISBN…"
                   class="block w-full rounded-lg border border-slate-300 bg-white py-2 pe-3 ps-9 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        </div>

        <x-primary-button>Search</x-primary-button>

        @if ($q !== '')
            <a href="{{ route('books.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <x-icon name="close" size="sm" /> Clear
            </a>
        @endif
    </form>

    <x-card class="mt-4" body-class="p-0">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[44rem] text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                        <th scope="col" class="px-5 py-3 text-start">Title</th>
                        <th scope="col" class="px-5 py-3 text-start">Author</th>
                        <th scope="col" class="px-5 py-3 text-start">ISBN</th>
                        <th scope="col" class="hidden px-5 py-3 text-start sm:table-cell">Year</th>
                        <th scope="col" class="px-5 py-3 text-start">Shelf</th>
                        <th scope="col" class="px-5 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($books as $book)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <p class="font-medium text-slate-900">{{ $book->title }}</p>
                                @if ($book->description)
                                    <p class="mt-0.5 line-clamp-1 text-xs text-slate-400">{{ $book->description }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $book->author }}</td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $book->isbn }}</td>
                            <td class="hidden px-5 py-4 text-slate-600 sm:table-cell">{{ $book->published_year ?? '—' }}</td>
                            <td class="px-5 py-4">
                                @if ($book->stock > 0)
                                    <x-badge tone="emerald">{{ $book->stock }} on shelf</x-badge>
                                @else
                                    <x-badge tone="rose">Out of stock</x-badge>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('books.edit', $book) }}"
                                       class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                                        <x-icon name="edit" size="xs" /> Edit
                                    </a>

                                    <x-confirm-delete :action="route('books.destroy', $book)"
                                                      title="Delete this book?"
                                                      :message="'“'.$book->title.'” will be removed from the catalogue.'" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <x-icon name="search" size="xl" class="mx-auto text-slate-300" />
                                <p class="mt-3 font-medium text-slate-700">
                                    {{ $q !== '' ? 'No books match “'.$q.'”' : 'No books in the catalogue yet' }}
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $q !== '' ? 'Try another title, author or ISBN.' : 'Add your first book to get started.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-6">{{ $books->links() }}</div>
</x-app-layout>
