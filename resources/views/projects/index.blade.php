<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="eyebrow">/ projekty</span>
                <h2 class="text-4xl font-bold tracking-tight leading-none mt-2">Projekty</h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <form method="GET" class="card p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 mb-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Szukaj nazwy lub opisu..." class="input sm:col-span-2">
                <select name="status" class="input">
                    <option value="">Status: wszystkie</option>
                    @foreach(\App\Models\Project::STATUSES as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
                <select name="member" class="input">
                    <option value="">Członek: dowolny</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected(request('member') == $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                    <button class="btn btn-primary">Filtruj</button>
                    <a href="{{ route('projects.index') }}" class="btn">Wyczyść</a>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('projects.export', request()->query()) }}" class="btn">↓ Eksport CSV</a>
                    @can('create', App\Models\Project::class)
                        <a href="{{ route('projects.create') }}" class="btn btn-accent">+ Nowy projekt</a>
                    @endcan
                </div>
            </div>
        </form>

        <div class="card-flat overflow-x-auto" style="box-shadow: 4px 4px 0 0 #0A0A0A;">
            <table class="brutal">
                <thead>
                    <tr>
                        <x-sortable-th column="name" label="Nazwa" :current="$sort" :dir="$dir" />
                        <x-sortable-th column="status" label="Status" :current="$sort" :dir="$dir" />
                        <th>Zespół</th>
                        <th>Zadań</th>
                        <x-sortable-th column="deadline" label="Deadline" :current="$sort" :dir="$dir" />
                        <x-sortable-th column="created_at" label="Utworzono" :current="$sort" :dir="$dir" />
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>
                                <a href="{{ route('projects.show', $project) }}" class="font-bold link">{{ $project->name }}</a>
                                <div class="text-xs opacity-70 mt-1">{{ Str::limit($project->description, 90) }}</div>
                            </td>
                            <td><x-status-badge :status="$project->status" /></td>
                            <td class="mono font-bold">{{ $project->members->count() }}</td>
                            <td class="mono font-bold">{{ $project->tasks_count }}</td>
                            <td class="mono text-sm">{{ $project->deadline?->format('d.m.Y') ?? '—' }}</td>
                            <td class="mono text-sm opacity-70">{{ $project->created_at->format('d.m.Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('projects.show', $project) }}" class="font-bold link">Otwórz &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-10 opacity-50 font-medium">Brak projektów.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $projects->links() }}</div>
    </div>
</x-app-layout>
