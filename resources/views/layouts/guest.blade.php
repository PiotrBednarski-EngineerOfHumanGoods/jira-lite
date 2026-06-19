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
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-slate-800">Jira-lite</h1>
                <p class="text-sm text-slate-500 mt-1">System zarządzania projektami</p>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 border border-slate-200">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
