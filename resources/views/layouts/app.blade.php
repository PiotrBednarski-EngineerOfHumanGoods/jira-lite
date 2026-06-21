<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jira-lite') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FFFBEB',
                        ink: '#0A0A0A',
                        tomato: '#FF5C38',
                        cobalt: '#3D5AFE',
                        sun: '#FFD93D',
                        lime: '#A8E063',
                    },
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    boxShadow: {
                        'brutal': '4px 4px 0 0 #0A0A0A',
                        'brutal-sm': '2px 2px 0 0 #0A0A0A',
                        'brutal-lg': '6px 6px 0 0 #0A0A0A',
                        'brutal-hov': '2px 2px 0 0 #0A0A0A',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Space Grotesk', system-ui, sans-serif; background: #FFFBEB; color: #0A0A0A; -webkit-font-smoothing: antialiased; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        .card { background: #FFFFFF; border: 2px solid #0A0A0A; box-shadow: 4px 4px 0 0 #0A0A0A; }
        .card-flat { background: #FFFFFF; border: 2px solid #0A0A0A; }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            border: 2px solid #0A0A0A; padding: 0.5rem 1rem; font-weight: 600; font-size: 0.875rem;
            background: #FFFFFF; color: #0A0A0A;
            box-shadow: 3px 3px 0 0 #0A0A0A;
            transition: transform .08s ease-out, box-shadow .08s ease-out; cursor: pointer; }
        .btn:hover { transform: translate(1px,1px); box-shadow: 2px 2px 0 0 #0A0A0A; }
        .btn:active { transform: translate(3px,3px); box-shadow: 0 0 0 0 #0A0A0A; }
        .btn-primary { background: #3D5AFE; color: #FFFFFF; }
        .btn-accent { background: #FF5C38; color: #FFFFFF; }
        .btn-sun { background: #FFD93D; color: #0A0A0A; }
        .btn-danger { background: #FF5C38; color: #FFFFFF; }
        .btn-ghost { background: transparent; box-shadow: none; border-color: transparent; padding: 0.25rem 0.5rem; }
        .btn-ghost:hover { background: #FFD93D; transform: none; box-shadow: none; }

        .input, select, textarea {
            width: 100%; border: 2px solid #0A0A0A; padding: 0.5rem 0.75rem;
            background: #FFFFFF; color: #0A0A0A; border-radius: 0; font-family: inherit;
        }
        .input:focus, select:focus, textarea:focus { outline: none !important; box-shadow: 3px 3px 0 0 #FF5C38 !important; border-color: #0A0A0A !important; --tw-ring-color: transparent !important; }

        .pill { display: inline-block; padding: 2px 8px; border: 2px solid #0A0A0A; font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; }

        .link { color: #3D5AFE; text-decoration: underline; text-underline-offset: 3px; text-decoration-thickness: 2px; }
        .link:hover { background: #FFD93D; color: #0A0A0A; }

        table.brutal { border-collapse: collapse; width: 100%; }
        table.brutal th { background: #0A0A0A; color: #FFFBEB; text-align: left; padding: 10px 14px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; border-right: 2px solid #FFFBEB; }
        table.brutal th:last-child { border-right: 0; }
        table.brutal td { padding: 14px; border-bottom: 2px solid #0A0A0A; border-right: 2px solid #0A0A0A; }
        table.brutal td:last-child { border-right: 0; }
        table.brutal tr:last-child td { border-bottom: 0; }
        table.brutal tr:hover td { background: #FFD93D; }

        .eyebrow { font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.1em; color: #0A0A0A; background: #FFD93D; padding: 2px 6px; display: inline-block; }
        ::selection { background: #FF5C38; color: #FFFBEB; }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-cream border-b-2 border-ink">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="border-t-2 border-ink bg-cream mt-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-xs flex justify-between items-center">
                <span class="font-bold">Jira-lite &copy; {{ date('Y') }}</span>
                <span class="mono">Laravel 13 · SQLite</span>
            </div>
        </footer>
    </div>
</body>
</html>
