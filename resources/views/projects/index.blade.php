<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Projekty</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <form method="GET" class="bg-white p-4 rounded shadow-sm border border-gray-200 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Szukaj nazwy lub opisu..." class="sm:col-span-2 border border-gray-300 rounded px-3 py-2 text-sm">
                <select name="status" class="border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">Status: wszystkie</option>
                    @foreach(\App\Models\Project::STATUSES as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
                <select name="member" class="border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">Członek: wszyscy</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(request('member') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-between items-center mt-3">
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 bg-gray-700 hover:bg-gray-800 text-white text-sm rounded">Filtruj</button>
                    <a href="{{ route('projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900 py-1.5">Wyczyść</a>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('projects.export', request()->query()) }}" class="px-3 py-1.5 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm rounded">Eksport CSV</a>
                    @can('create', App\Models\Project::class)
                        <a href="{{ route('projects.create') }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">+ Nowy projekt</a>
                    @endcan
                </div>
            </div>
        </form>

        <div class="bg-white shadow-sm rounded border border-gray-200 overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <x-sortable-th column="name" label="Nazwa" :current="$sort" :dir="$dir" />
                        <x-sortable-th column="status" label="Status" :current="$sort" :dir="$dir" />
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Członkowie</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Zadań</th>
                        <x-sortable-th column="deadline" label="Deadline" :current="$sort" :dir="$dir" />
                        <x-sortable-th column="created_at" label="Utworzono" :current="$sort" :dir="$dir" />
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('projects.show', $project) }}" class="text-sm font-medium text-blue-600 hover:underline">{{ $project->name }}</a>
                                <div class="text-xs text-gray-500">{{ Str::limit($project->description, 80) }}</div>
                            </td>
                            <td class="px-4 py-3"><x-status-badge :status="$project->status" /></td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $project->members->count() }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $project->tasks_count }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $project->deadline?->format('d.m.Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $project->created_at->format('d.m.Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('projects.show', $project) }}" class="text-sm text-blue-600 hover:underline">Otwórz</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400">Brak projektów.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $projects->links() }}</div>
    </div>
</x-app-layout>
