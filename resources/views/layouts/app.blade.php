@use('App\Models\IssueRecord')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-slate-200 bg-white">
                    <div class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6 lg:px-8">
                @if (session('success'))
                    <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
                @endif

                @if (session('error'))
                    <x-alert type="danger" class="mb-6">{{ session('error') }}</x-alert>
                @endif

                @if ($errors->any())
                    <x-alert type="danger" class="mb-6">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="mt-1 list-disc space-y-0.5 ps-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 bg-white">
                <div class="mx-auto flex w-full max-w-6xl flex-col gap-1 px-4 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                    <p>Loan period {{ IssueRecord::LOAN_DAYS }} days &middot; fine रू{{ IssueRecord::FINE_PER_DAY }} per late day</p>
                </div>
            </footer>
        </div>
    </body>
</html>
