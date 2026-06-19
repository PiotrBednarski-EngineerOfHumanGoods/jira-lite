<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jira-lite') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-slate-200">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-slate-200 mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-xs text-slate-500 flex justify-between">
                <span>Jira-lite &copy; {{ date('Y') }}</span>
                <span>Laravel 13 + SQLite</span>
            </div>
        </footer>
    </div>
</body>
</html>
