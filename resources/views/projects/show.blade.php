<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">{{ $project->name }}</h2>
            <div class="flex gap-2">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="px-3 py-1.5 bg-gray-700 hover:bg-gray-800 text-white text-sm rounded">Edytuj</a>
                @endcan
                @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Usunąć projekt „{{ $project->name }}”? Wszystkie zadania zostaną usunięte.')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm rounded">Usuń</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <x-status-badge :status="$project->status" />
                        @if($project->deadline)
                            <span class="text-sm text-gray-600">Deadline: <strong>{{ $project->deadline->format('d.m.Y') }}</strong></span>
                        @endif
                        <span class="text-sm text-gray-600">Twórca: <strong>{{ $project->creator?->name }}</strong></span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $project->description ?: 'Brak opisu.' }}</p>
                </div>

                <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-gray-700">Zadania ({{ $project->tasks->count() }})</h3>
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">+ Nowe zadanie</a>
                    </div>
                    @forelse($project->tasks as $task)
                        <div class="py-2 border-b border-gray-100 last:border-0 flex items-center justify-between">
                            <div class="min-w-0">
                                <a href="{{ route('tasks.show', $task) }}" class="text-sm text-gray-800 hover:text-blue-600">{{ $task->title }}</a>
                                <div class="flex gap-2 mt-1">
                                    <x-status-badge :status="$task->status" />
                                    <x-status-badge :status="$task->priority" />
                                    @foreach($task->tags as $tag)
                                        <span class="text-xs text-gray-500">#{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 text-right ml-3">
                                @if($task->assignee)<div>{{ $task->assignee->name }}</div>@endif
                                @if($task->due_date)<div>{{ $task->due_date->format('d.m.Y') }}</div>@endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 py-2">Brak zadań w tym projekcie.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                    <h3 class="font-semibold text-gray-700 mb-3">Zespół ({{ $project->members->count() }})</h3>
                    <ul class="text-sm space-y-2">
                        @foreach($project->members as $m)
                            <li class="flex justify-between">
                                <span>{{ $m->name }}</span>
                                <span class="text-xs text-gray-500">{{ $m->pivot->project_role }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                    <h3 class="font-semibold text-gray-700 mb-3">Historia zmian</h3>
                    @php $actionMap = ['created' => 'utworzył', 'updated' => 'zmienił', 'deleted' => 'usunął']; @endphp
                    <div class="max-h-96 overflow-y-auto">
                        @forelse($auditLogs as $log)
                            <div class="py-1.5 border-b border-gray-100 last:border-0 text-xs">
                                <div>
                                    <span class="font-medium">{{ $log->user?->name ?? 'system' }}</span>
                                    {{ $actionMap[$log->action] ?? $log->action }}
                                </div>
                                <div class="text-gray-400">{{ $log->created_at->format('d.m.Y H:i') }}</div>
                                @if($log->changes && $log->action === 'updated')
                                    <div class="text-gray-500">pola: {{ implode(', ', array_keys($log->changes)) }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">Brak wpisów.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
