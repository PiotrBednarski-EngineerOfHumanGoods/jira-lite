<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jira-lite') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cream:'#FFFBEB', ink:'#0A0A0A', tomato:'#FF5C38', cobalt:'#3D5AFE', sun:'#FFD93D', lime:'#A8E063' },
            fontFamily: { sans:['"Space Grotesk"','system-ui','sans-serif'], mono:['"JetBrains Mono"','monospace'] },
        }}}
    </script>
    <style>
        body { font-family: 'Space Grotesk', system-ui, sans-serif; background: #FFFBEB; color: #0A0A0A; -webkit-font-smoothing: antialiased; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .card { background: #FFFFFF; border: 2px solid #0A0A0A; box-shadow: 6px 6px 0 0 #0A0A0A; }
        .input { width: 100%; border: 2px solid #0A0A0A; padding: 0.625rem 0.75rem; background: #FFFFFF; border-radius: 0; }
        .input:focus { outline: none !important; box-shadow: 3px 3px 0 0 #FF5C38 !important; --tw-ring-color: transparent !important; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            border: 2px solid #0A0A0A; padding: 0.625rem 1.25rem; font-weight: 600; font-size: 0.875rem;
            background: #3D5AFE; color: #FFFFFF; box-shadow: 4px 4px 0 0 #0A0A0A;
            transition: transform .08s ease-out, box-shadow .08s ease-out; cursor: pointer; }
        .btn:hover { transform: translate(2px,2px); box-shadow: 2px 2px 0 0 #0A0A0A; }
        .btn:active { transform: translate(4px,4px); box-shadow: 0 0 0 0 #0A0A0A; }
        .link { color: #3D5AFE; text-decoration: underline; text-underline-offset: 3px; text-decoration-thickness: 2px; font-weight: 600; }
        .link:hover { background: #FFD93D; color: #0A0A0A; }
        .eyebrow { font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.1em; background: #FFD93D; padding: 2px 6px; display: inline-block; border: 2px solid #0A0A0A; }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4"
         style="background-image: radial-gradient(#0A0A0A 1px, transparent 1px); background-size: 24px 24px;">
        <div class="w-full max-w-md">
            <div class="mb-6 flex items-end gap-3">
                <h1 class="text-5xl font-bold tracking-tight leading-none">Jira<span class="text-tomato">.</span>lite</h1>
                <span class="eyebrow mb-1">v1.0</span>
            </div>

            <div class="card p-7">
                {{ $slot }}
            </div>

            <p class="mt-4 text-xs mono text-center text-ink/60">PHP · LARAVEL · SQLITE</p>
        </div>
    </div>
</body>
</html>
