<nav x-data="{ open: false }" class="bg-cream border-b-2 border-ink">
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
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 font-semibold text-sm border-2 border-ink bg-white hover:bg-sun" style="box-shadow: 3px 3px 0 0 #0A0A0A">
                        <span>{{ $u->name }}</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-52 bg-white border-2 border-ink z-50" style="box-shadow: 4px 4px 0 0 #0A0A0A">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm font-medium hover:bg-sun border-b-2 border-ink">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-tomato hover:text-white">Wyloguj</button>
                        </form>
                    </div>
                </div>
            </div>

            <button @click="open = !open" class="sm:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition class="sm:hidden border-t-2 border-ink bg-white">
        <div class="px-4 py-3 space-y-2">
            @foreach($links as [$pattern, $url, $label])
                <a href="{{ $url }}" class="block px-3 py-1.5 text-sm font-semibold {{ request()->routeIs($pattern) ? 'bg-ink text-cream' : '' }}">{{ $label }}</a>
            @endforeach
            <hr class="border-ink border-t-2">
            <a href="{{ route('profile.edit') }}" class="block px-3 py-1.5 text-sm font-semibold">Profil</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="block px-3 py-1.5 text-sm font-semibold text-tomato">Wyloguj</button>
            </form>
        </div>
    </div>
</nav>
