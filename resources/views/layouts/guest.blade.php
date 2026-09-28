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
    <body class="min-h-screen bg-gradient-to-b from-slate-100 via-slate-50 to-slate-200 font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center gap-6 px-4 py-10">
            <div class="flex flex-col items-center gap-2 text-center">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
                    <x-icon name="book" size="lg" />
                </span>
                <h1 class="text-xl font-bold tracking-tight text-slate-900">{{ config('app.name') }}</h1>
                <p class="text-sm text-slate-500">Sign in to manage the catalogue and loans.</p>
            </div>

            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-300/40 sm:p-8">
                {{ $slot }}
            </div>

            <p class="text-xs text-slate-400">
                Loan period {{ IssueRecord::LOAN_DAYS }} days &middot; fine रू{{ IssueRecord::FINE_PER_DAY }} per late day
            </p>
        </div>
    </body>
</html>
