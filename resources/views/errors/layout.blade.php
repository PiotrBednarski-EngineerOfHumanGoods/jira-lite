<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code ?? 'Błąd' }} · Jira-lite</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,700|jetbrains-mono:400,500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { cream:'#FFFBEB', ink:'#0A0A0A', tomato:'#FF5C38', cobalt:'#3D5AFE', sun:'#FFD93D' },
            fontFamily: { sans:['"Space Grotesk"','sans-serif'], mono:['"JetBrains Mono"','monospace'] }
        }}}
    </script>
    <style>
        body { font-family: 'Space Grotesk', sans-serif; background: #FFFBEB; color: #0A0A0A; }
        .mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4" style="background-image: radial-gradient(#0A0A0A 1px, transparent 1px); background-size: 24px 24px;">
    <div class="bg-white border-2 border-ink p-10 max-w-lg w-full text-center" style="box-shadow: 8px 8px 0 0 #0A0A0A;">
        <div class="mono text-xs font-bold uppercase tracking-widest bg-{{ $color ?? 'sun' }} border-2 border-ink inline-block px-3 py-1 mb-6">
            Błąd {{ $code ?? '???' }}
        </div>
        <h1 class="text-6xl font-bold tracking-tight mb-3">{{ $title ?? 'Coś poszło nie tak' }}</h1>
        <p class="text-base opacity-80 mb-8">{{ $message ?? 'Spróbuj wrócić do strony głównej.' }}</p>
        <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-3 bg-cobalt text-white border-2 border-ink font-semibold" style="box-shadow: 4px 4px 0 0 #0A0A0A;">
            &larr; Wróć do aplikacji
        </a>
    </div>
</body>
</html>
