<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Users</h1>
                <p class="mt-1 text-sm text-slate-500">The members who borrow books, and the admins who run the desk.</p>
            </div>

            <x-badge tone="violet">{{ $admins }} admin{{ $admins === 1 ? '' : 's' }}</x-badge>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <x-card title="Add a user" subtitle="Share the password with them afterwards." body-class="space-y-5 p-5">
                <div class="space-y-1.5">
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="space-y-1.5">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" :value="old('email')" required
                                  placeholder="member@library.test" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="space-y-1.5">
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" name="password" type="password" required autocomplete="new-password" />
                    <p class="text-xs text-slate-500">At least 8 characters.</p>
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="space-y-1.5">
                    <x-input-label for="password_confirmation" value="Confirm password" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" required
                                  autocomplete="new-password" />
                </div>

                <label for="is_admin" class="flex items-center gap-2 border-t border-slate-200 pt-5 text-sm text-slate-600">
                    <input id="is_admin" name="is_admin" type="checkbox" value="1"
                           class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-2 focus:ring-indigo-100">
                    Give this user admin rights
                </label>

                <x-primary-button class="w-full">
                    <x-icon name="plus" size="sm" /> Add user
                </x-primary-button>
            </x-card>
        </form>

        <div class="lg:col-span-2">
            <x-card title="All users" :subtitle="$members.' member'.($members === 1 ? '' : 's').' and '.$admins.' admin'.($admins === 1 ? '' : 's')" body-class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[34rem] text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                                <th scope="col" class="px-5 py-3 text-start">Name</th>
                                <th scope="col" class="hidden px-5 py-3 text-start sm:table-cell">Email</th>
                                <th scope="col" class="px-5 py-3 text-start">Role</th>
                                <th scope="col" class="px-5 py-3 text-start">On loan</th>
                                <th scope="col" class="px-5 py-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($users as $u)
                                <tr class="transition hover:bg-slate-50/70">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">
                                                {{ strtoupper(mb_substr($u->name, 0, 1)) }}
                                            </span>
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $u->name }}</p>
                                                <p class="mt-0.5 text-xs text-slate-400">
                                                    {{ $u->id === auth()->id() ? 'That is you' : ($u->isAdmin() ? 'Administrator' : 'Member') }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden px-5 py-4 text-slate-600 sm:table-cell">{{ $u->email }}</td>
                                    <td class="px-5 py-4">
                                        @if ($u->isAdmin())
                                            <x-badge tone="violet">admin</x-badge>
                                        @else
                                            <x-badge tone="slate">member</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($u->active_issue_records_count > 0)
                                            <x-badge tone="sky">{{ $u->active_issue_records_count }}</x-badge>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-end">
                                        @unless ($u->isAdmin() || $u->id === auth()->id())
                                            <x-confirm-delete :action="route('users.destroy', $u)"
                                                              title="Delete this user?"
                                                              :message="'“'.$u->name.'” will be removed. Users with loans on record cannot be deleted.'" />
                                        @endunless
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-16 text-center">
                                        <x-icon name="users" size="xl" class="mx-auto text-slate-300" />
                                        <p class="mt-3 font-medium text-slate-700">No users yet</p>
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
