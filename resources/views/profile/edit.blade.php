<x-app-layout>
    <x-slot name="header">
        <span class="eyebrow">/ ustawienia</span>
        <h2 class="text-4xl font-bold tracking-tight leading-none mt-2">Profil</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-5">

        <div class="card p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card p-6">
            <header class="mb-4 pb-3 border-b-2 border-ink">
                <h2 class="text-lg font-bold uppercase">Preferencje</h2>
                <p class="text-sm opacity-70 mt-1">Domyślne sortowanie listy projektów — zapamiętywane między wizytami.</p>
            </header>
            <form method="POST" action="{{ route('preferences.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <x-input-label value="Sortuj projekty wg" />
                    <select name="projects_sort" class="input">
                        @php $current = auth()->user()->preference('projects.sort', 'created_at'); @endphp
                        <option value="created_at" @selected($current === 'created_at')>data utworzenia</option>
                        <option value="name" @selected($current === 'name')>nazwa</option>
                        <option value="status" @selected($current === 'status')>status</option>
                        <option value="deadline" @selected($current === 'deadline')>deadline</option>
                    </select>
                </div>
                <div>
                    <x-input-label value="Kierunek" />
                    <select name="projects_dir" class="input">
                        @php $currentDir = auth()->user()->preference('projects.dir', 'desc'); @endphp
                        <option value="desc" @selected($currentDir === 'desc')>malejąco (najnowsze pierwsze)</option>
                        <option value="asc" @selected($currentDir === 'asc')>rosnąco</option>
                    </select>
                </div>
                <div class="flex items-center justify-between pt-3 border-t-2 border-ink">
                    <div class="mono text-xs opacity-70 truncate max-w-[60%]">zapisane: {{ json_encode(auth()->user()->preferences ?? []) }}</div>
                    <button class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>

        <div class="card p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
