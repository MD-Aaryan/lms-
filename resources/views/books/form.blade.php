<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ $book->exists ? 'Edit Book' : 'New Book' }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $book->exists ? 'Update the details of “'.$book->title.'”.' : 'Add a title to the catalogue.' }}
                </p>
            </div>

            <a href="{{ route('books.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <x-icon name="close" size="sm" /> Cancel
            </a>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST"
              action="{{ $book->exists ? route('books.update', $book) : route('books.store') }}"
              class="lg:col-span-2">
            @csrf
            @if ($book->exists)
                @method('PUT')
            @endif

            <x-card title="Book details" subtitle="Title, author and ISBN are required." body-class="space-y-5 p-5">
                <div class="space-y-1.5">
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" :value="old('title', $book->title)" required autofocus />
                    <x-input-error :messages="$errors->get('title')" />
                </div>

                <div class="space-y-1.5">
                    <x-input-label for="author" value="Author" />
                    <x-text-input id="author" name="author" :value="old('author', $book->author)" required />
                    <x-input-error :messages="$errors->get('author')" />
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="space-y-1.5">
                        <x-input-label for="isbn" value="ISBN" />
                        <x-text-input id="isbn" name="isbn" :value="old('isbn', $book->isbn)" required
                                      placeholder="9780441013593" />
                        <x-input-error :messages="$errors->get('isbn')" />
                    </div>

                    <div class="space-y-1.5">
                        <x-input-label for="published_year" value="Published year" />
                        <x-text-input id="published_year" name="published_year" type="number" min="1000" max="{{ date('Y') }}"
                                      :value="old('published_year', $book->published_year)" />
                        <x-input-error :messages="$errors->get('published_year')" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <x-input-label for="stock" value="Stock" />
                    <x-text-input id="stock" name="stock" type="number" min="0" :value="old('stock', $book->stock ?? 1)" required />
                    <p class="text-xs text-slate-500">Copies currently on the shelf. Issuing a book lowers this, returning it raises it.</p>
                    <x-input-error :messages="$errors->get('stock')" />
                </div>

                <div class="space-y-1.5">
                    <x-input-label for="description" value="Description" />
                    <x-textarea id="description" name="description" rows="4"
                                placeholder="Short summary shown in the catalogue…">{{ old('description', $book->description) }}</x-textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>
            </x-card>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <x-primary-button>
                    <x-icon name="check" size="sm" /> {{ $book->exists ? 'Save changes' : 'Add book' }}
                </x-primary-button>

                <a href="{{ route('books.index') }}"
                   class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                    Back to books
                </a>
            </div>
        </form>

        <aside class="space-y-4">
            <x-card title="How this works" body-class="space-y-3 p-5 text-sm text-slate-600">
                <p><span class="font-medium text-slate-800">Stock</span> is the number of copies on the shelf, so it drops to 0 when everything is on loan.</p>
                <p><span class="font-medium text-slate-800">ISBN</span> has to be unique — editing a book keeps its own ISBN valid.</p>
                <p>A book that has issue history cannot be deleted, so the loan record stays intact.</p>
            </x-card>
        </aside>
    </div>
</x-app-layout>
