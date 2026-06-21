<nav class="bg-cream border-b-2 border-ink">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-2xl font-bold tracking-tight leading-none">
                    Jira<span class="text-tomato">.</span>lite
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    @php
                        $links = [
                            ['dashboard', route('dashboard'), 'Pulpit'],
                            ['projects.*', route('projects.index'), 'Projekty'],
                            ['tasks.*', route('tasks.index'), 'Zadania'],
                        ];
                        if (auth()->user()?->isManager()) {
                            $links[] = ['tags.*', route('tags.index'), 'Tagi'];
                        }
                    @endphp
                    @foreach($links as [$pattern, $url, $label])
                        @php $active = request()->routeIs($pattern); @endphp
                        <a href="{{ $url }}" class="px-3 py-1.5 text-sm font-semibold {{ $active ? 'bg-ink text-cream' : 'hover:bg-sun' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3">
                @php
                    $u = auth()->user();
                    $roleLabel = ['admin'=>'admin','manager'=>'manager','developer'=>'developer'][$u->role] ?? $u->role;
                    $roleColor = ['admin'=>'#FF5C38','manager'=>'#3D5AFE','developer'=>'#A8E063'][$u->role] ?? '#FFD93D';
                @endphp
                <span class="pill" style="background:{{ $roleColor }};">{{ $roleLabel }}</span>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-1.5 font-semibold text-sm border-2 border-ink bg-white hover:bg-sun" style="box-shadow: 3px 3px 0 0 #0A0A0A">
                    {{ $u->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center px-3 py-1.5 font-semibold text-sm border-2 border-ink bg-white hover:bg-tomato hover:text-white" style="box-shadow: 3px 3px 0 0 #0A0A0A">
                        Wyloguj
                    </button>
                </form>
            </div>

            <details class="sm:hidden relative">
                <summary class="list-none cursor-pointer">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </summary>
                <div class="absolute right-0 top-10 w-56 bg-white border-2 border-ink z-50 p-3 space-y-2" style="box-shadow: 4px 4px 0 0 #0A0A0A">
                    @foreach($links as [$pattern, $url, $label])
                        <a href="{{ $url }}" class="block px-3 py-1.5 text-sm font-semibold {{ request()->routeIs($pattern) ? 'bg-ink text-cream' : '' }}">{{ $label }}</a>
                    @endforeach
                    <hr class="border-ink border-t-2">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-1.5 text-sm font-semibold">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="block px-3 py-1.5 text-sm font-semibold text-tomato">Wyloguj</button>
                    </form>
                </div>
            </details>
        </div>
    </div>
</nav>
