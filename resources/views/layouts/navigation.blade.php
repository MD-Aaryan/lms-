@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
    $initials = strtoupper(mb_substr($user->name, 0, 1));
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                <a href="/" class="flex items-center gap-2 text-slate-900">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-white">
                        <x-icon name="book" />
                    </span>
                    <span class="text-lg font-bold tracking-tight">Library</span>
                </a>

                <div class="hidden items-center gap-1 sm:flex">
                    @if ($isAdmin)
                        <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')">
                            <x-icon name="book" size="sm" /> Books
                        </x-nav-link>
                        <x-nav-link :href="route('circulation.index')" :active="request()->routeIs('circulation.*')">
                            <x-icon name="swap" size="sm" /> Issue / Return
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            <x-icon name="users" size="sm" /> Users
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('my')" :active="request()->routeIs('my')">
                            <x-icon name="book" size="sm" /> My Books
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">
                        {{ $initials }}
                    </span>
                    <span class="leading-tight">
                        <span class="block text-sm font-medium text-slate-900">{{ $user->name }}</span>
                        <span class="block text-xs text-slate-500">{{ $isAdmin ? 'Administrator' : 'Member' }}</span>
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-secondary-button type="submit" class="px-3 py-2 text-xs">
                        <x-icon name="logout" size="sm" /> Log out
                    </x-secondary-button>
                </form>
            </div>

            <button type="button" x-on:click="open = ! open" aria-label="Toggle navigation"
                    class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 sm:hidden">
                <span x-show="! open"><x-icon name="menu" /></span>
                <span x-cloak x-show="open"><x-icon name="close" /></span>
            </button>
        </div>
    </div>

    <div x-cloak x-show="open" x-transition.opacity.duration.150ms class="border-t border-slate-200 bg-white sm:hidden">
        <div class="space-y-1 px-4 py-4">
            @if ($isAdmin)
                <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')">Books</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('circulation.index')" :active="request()->routeIs('circulation.*')">Issue / Return</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Users</x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('my')" :active="request()->routeIs('my')">My Books</x-responsive-nav-link>
            @endif

            <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-200 pt-4">
                <span class="leading-tight">
                    <span class="block text-sm font-medium text-slate-900">{{ $user->name }}</span>
                    <span class="block text-xs text-slate-500">{{ $isAdmin ? 'Administrator' : 'Member' }}</span>
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-secondary-button type="submit" class="px-3 py-2 text-xs">Log out</x-secondary-button>
                </form>
            </div>
        </div>
    </div>
</nav>
