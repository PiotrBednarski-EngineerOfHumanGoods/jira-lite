<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-800">Jira-lite</a>

                <div class="hidden sm:flex ml-8 space-x-6">
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
                        <a href="{{ $url }}" class="inline-flex items-center text-sm border-b-2 {{ $active ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex items-center">
                @php
                    $u = auth()->user();
                    $roleLabel = ['admin'=>'administrator','manager'=>'manager','developer'=>'developer'][$u->role] ?? $u->role;
                @endphp
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center text-sm text-gray-700 hover:text-gray-900">
                        <span>{{ $u->name }}</span>
                        <span class="text-xs text-gray-400 ml-2">({{ $roleLabel }})</span>
                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Wyloguj</button>
                        </form>
                    </div>
                </div>
            </div>

            <button @click="open = !open" class="sm:hidden text-gray-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition class="sm:hidden border-t border-gray-200">
        <div class="px-4 py-3 space-y-2">
            @foreach($links as [$pattern, $url, $label])
                <a href="{{ $url }}" class="block text-sm {{ request()->routeIs($pattern) ? 'text-blue-600' : 'text-gray-700' }}">{{ $label }}</a>
            @endforeach
            <hr>
            <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-700">Profil</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="text-sm text-gray-700">Wyloguj</button>
            </form>
        </div>
    </div>
</nav>
