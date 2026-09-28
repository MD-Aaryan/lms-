<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="space-y-1.5">
            <x-input-label for="email" value="Email address" />
            <x-text-input id="email" name="email" type="email" :value="old('email')" required autofocus
                          autocomplete="username" placeholder="you@library.test" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="space-y-1.5">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" required
                          autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label for="remember" class="flex items-center gap-2 text-sm text-slate-600">
            <input id="remember" name="remember" type="checkbox" value="1"
                   class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-2 focus:ring-indigo-100">
            Keep me signed in
        </label>

        <x-primary-button class="w-full">Log in</x-primary-button>
    </form>

    @if (app()->isLocal())
        <div class="mt-6 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-3 text-xs text-slate-500">
            <p class="font-semibold text-slate-600">Demo logins</p>
            <p class="mt-1">
                Admin: admin@library.test &middot; Member: user@library.test &middot; password:
                <span class="font-mono">password</span>
            </p>
        </div>
    @endif
</x-guest-layout>
