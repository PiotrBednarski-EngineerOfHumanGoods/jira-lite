<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-slate-800">Profil</h2></x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-4">

        <div class="bg-white p-6 rounded shadow-sm border border-slate-200">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white p-6 rounded shadow-sm border border-slate-200">
            <header class="mb-4">
                <h2 class="text-lg font-medium text-slate-900">Preferencje</h2>
                <p class="text-sm text-slate-600 mt-1">Domyślne sortowanie listy projektów — zapamiętywane między wizytami.</p>
            </header>
            <form method="POST" action="{{ route('preferences.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Sortuj projekty wg:</label>
                    <select name="projects_sort" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                        @php $current = auth()->user()->preference('projects.sort', 'created_at'); @endphp
                        <option value="created_at" @selected($current === 'created_at')>data utworzenia</option>
                        <option value="name" @selected($current === 'name')>nazwa</option>
                        <option value="status" @selected($current === 'status')>status</option>
                        <option value="deadline" @selected($current === 'deadline')>deadline</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kierunek:</label>
                    <select name="projects_dir" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                        @php $currentDir = auth()->user()->preference('projects.dir', 'desc'); @endphp
                        <option value="desc" @selected($currentDir === 'desc')>malejąco (najnowsze pierwsze)</option>
                        <option value="asc" @selected($currentDir === 'asc')>rosnąco</option>
                    </select>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                    <div class="text-xs text-slate-500 font-mono truncate max-w-[60%]">zapisane: {{ json_encode(auth()->user()->preferences ?? []) }}</div>
                    <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">Zapisz</button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded shadow-sm border border-slate-200">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white p-6 rounded shadow-sm border border-slate-200">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
