<x-app-layout>
    <x-slot name="header">
        <span class="eyebrow">/ zadania</span>
        <h2 class="text-4xl font-bold tracking-tight leading-none mt-2">Zadania</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <form method="GET" class="card p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Szukaj..." class="input lg:col-span-2">
                <select name="status" class="input">
                    <option value="">Status</option>
                    @foreach(\App\Models\Task::STATUSES as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
                <select name="priority" class="input">
                    <option value="">Priorytet</option>
                    @foreach(\App\Models\Task::PRIORITIES as $p)
                        <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
                    @endforeach
                </select>
                <select name="project_id" class="input">
                    <option value="">Projekt</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" @selected(request('project_id') == $p->id)>{{ Str::limit($p->name, 18) }}</option>
                    @endforeach
                </select>
                <select name="tag" class="input">
                    <option value="">Tag</option>
                    @foreach($tags as $t)
                        <option value="{{ $t->id }}" @selected(request('tag') == $t->id)>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                    <button class="btn btn-primary">Filtruj</button>
                    <a href="{{ route('tasks.index') }}" class="btn">Wyczyść</a>
                </div>
                <a href="{{ route('tasks.create') }}" class="btn btn-accent">+ Nowe zadanie</a>
            </div>
        </form>

        <div class="card-flat overflow-x-auto" style="box-shadow: 4px 4px 0 0 #0A0A0A;">
            <table class="brutal">
                <thead>
                    <tr>
                        <x-sortable-th column="title" label="Tytuł" :current="$sort" :dir="$dir" />
                        <th>Projekt</th>
                        <x-sortable-th column="status" label="Status" :current="$sort" :dir="$dir" />
                        <x-sortable-th column="priority" label="Priorytet" :current="$sort" :dir="$dir" />
                        <th>Przypisane</th>
                        <x-sortable-th column="due_date" label="Termin" :current="$sort" :dir="$dir" />
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td>
                                <a href="{{ route('tasks.show', $task) }}" class="font-bold link">{{ $task->title }}</a>
                                @if($task->tags->count())
                                    <div class="flex gap-2 mt-1">
                                        @foreach($task->tags as $tag)
                                            <span class="mono text-xs opacity-60">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="text-sm">
                                <a href="{{ route('projects.show', $task->project) }}" class="link">{{ $task->project->name }}</a>
                            </td>
                            <td><x-status-badge :status="$task->status" /></td>
                            <td><x-status-badge :status="$task->priority" /></td>
                            <td class="text-sm font-semibold">{{ $task->assignee?->name ?? '—' }}</td>
                            <td class="mono text-sm">{{ $task->due_date?->format('d.m.Y') ?? '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('tasks.show', $task) }}" class="font-bold link">Otwórz &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-10 opacity-50">Brak zadań.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $tasks->links() }}</div>
    </div>
</x-app-layout>
