<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-slate-800">Zadania</h2></x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <form method="GET" class="bg-white p-4 rounded shadow-sm border border-slate-200 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Szukaj..." class="lg:col-span-2 border border-slate-300 rounded px-3 py-2 text-sm">
                <select name="status" class="border border-slate-300 rounded px-3 py-2 text-sm">
                    <option value="">Status: wszystkie</option>
                    @foreach(\App\Models\Task::STATUSES as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
                <select name="priority" class="border border-slate-300 rounded px-3 py-2 text-sm">
                    <option value="">Priorytet: wszystkie</option>
                    @foreach(\App\Models\Task::PRIORITIES as $p)
                        <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
                    @endforeach
                </select>
                <select name="project_id" class="border border-slate-300 rounded px-3 py-2 text-sm">
                    <option value="">Projekt: wszystkie</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" @selected(request('project_id') == $p->id)>{{ Str::limit($p->name, 18) }}</option>
                    @endforeach
                </select>
                <select name="tag" class="border border-slate-300 rounded px-3 py-2 text-sm">
                    <option value="">Tag: wszystkie</option>
                    @foreach($tags as $t)
                        <option value="{{ $t->id }}" @selected(request('tag') == $t->id)>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-between items-center mt-3">
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 bg-slate-700 hover:bg-slate-800 text-white text-sm rounded">Filtruj</button>
                    <a href="{{ route('tasks.index') }}" class="text-sm text-slate-600 hover:text-slate-900 py-1.5">Wyczyść</a>
                </div>
                <a href="{{ route('tasks.create') }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">+ Nowe zadanie</a>
            </div>
        </form>

        <div class="bg-white shadow-sm rounded border border-slate-200 overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <x-sortable-th column="title" label="Tytuł" :current="$sort" :dir="$dir" />
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-600 uppercase">Projekt</th>
                        <x-sortable-th column="status" label="Status" :current="$sort" :dir="$dir" />
                        <x-sortable-th column="priority" label="Priorytet" :current="$sort" :dir="$dir" />
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-600 uppercase">Przypisane</th>
                        <x-sortable-th column="due_date" label="Termin" :current="$sort" :dir="$dir" />
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium text-blue-600 hover:underline">{{ $task->title }}</a>
                                @if($task->tags->count())
                                    <div class="flex gap-2 mt-1">
                                        @foreach($task->tags as $tag)
                                            <span class="text-xs text-slate-500">#{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <a href="{{ route('projects.show', $task->project) }}" class="hover:underline">{{ $task->project->name }}</a>
                            </td>
                            <td class="px-4 py-3"><x-status-badge :status="$task->status" /></td>
                            <td class="px-4 py-3"><x-status-badge :status="$task->priority" /></td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $task->assignee?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $task->due_date?->format('d.m.Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('tasks.show', $task) }}" class="text-sm text-blue-600 hover:underline">Otwórz</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-slate-400">Brak zadań.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $tasks->links() }}</div>
    </div>
</x-app-layout>
