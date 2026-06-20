<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div class="min-w-0">
                <span class="eyebrow">/ projekt #{{ $project->id }}</span>
                <h2 class="text-3xl font-bold tracking-tight leading-tight mt-2 truncate">{{ $project->name }}</h2>
            </div>
            <div class="flex gap-2 shrink-0">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="btn">Edytuj</a>
                @endcan
                @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Usunąć projekt „{{ $project->name }}”?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Usuń</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                <div class="card p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4 pb-4 border-b-2 border-ink">
                        <div>
                            <div class="mono text-xs uppercase tracking-wider opacity-60 mb-1">Status</div>
                            <x-status-badge :status="$project->status" />
                        </div>
                        <div>
                            <div class="mono text-xs uppercase tracking-wider opacity-60 mb-1">Deadline</div>
                            <div class="font-bold">{{ $project->deadline?->format('d.m.Y') ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="mono text-xs uppercase tracking-wider opacity-60 mb-1">Twórca</div>
                            <div class="font-semibold text-sm">{{ $project->creator?->name }}</div>
                        </div>
                        <div>
                            <div class="mono text-xs uppercase tracking-wider opacity-60 mb-1">Utworzono</div>
                            <div class="mono text-sm">{{ $project->created_at->format('d.m.Y') }}</div>
                        </div>
                    </div>
                    <p class="whitespace-pre-line">{{ $project->description ?: 'Brak opisu.' }}</p>
                </div>

                <div class="card p-5">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-ink">
                        <h3 class="text-xl font-bold uppercase">Zadania <span class="mono font-normal opacity-60 text-base">({{ $project->tasks->count() }})</span></h3>
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary">+ Nowe zadanie</a>
                    </div>
                    @forelse($project->tasks as $task)
                        <div class="py-3 border-b-2 border-ink last:border-0 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <a href="{{ route('tasks.show', $task) }}" class="font-semibold hover:bg-sun">{{ $task->title }}</a>
                                <div class="flex flex-wrap gap-2 mt-1.5">
                                    <x-status-badge :status="$task->status" />
                                    <x-status-badge :status="$task->priority" />
                                    @foreach($task->tags as $tag)
                                        <span class="mono text-xs opacity-60">#{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mono text-xs text-right shrink-0">
                                @if($task->assignee)<div class="font-semibold">{{ $task->assignee->name }}</div>@endif
                                @if($task->due_date)<div class="opacity-60">{{ $task->due_date->format('d.m.Y') }}</div>@endif
                            </div>
                        </div>
                    @empty
                        <p class="opacity-60 py-3">Brak zadań w tym projekcie.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                <div class="card p-5">
                    <h3 class="text-lg font-bold uppercase mb-3 pb-2 border-b-2 border-ink">Zespół <span class="mono text-sm opacity-60">({{ $project->members->count() }})</span></h3>
                    <ul class="space-y-2">
                        @foreach($project->members as $m)
                            <li class="flex justify-between items-center text-sm">
                                <span class="font-semibold">{{ $m->name }}</span>
                                <span class="pill" style="background:{{ $m->pivot->project_role === 'owner' ? '#FFD93D' : '#FFFFFF' }}">{{ $m->pivot->project_role }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card p-5">
                    <h3 class="text-lg font-bold uppercase mb-3 pb-2 border-b-2 border-ink">Historia zmian</h3>
                    @php $actionMap = ['created' => 'utworzył', 'updated' => 'zmienił', 'deleted' => 'usunął']; @endphp
                    <div class="max-h-96 overflow-y-auto -mx-1 px-1">
                        @forelse($auditLogs as $log)
                            <div class="py-2 border-b-2 border-ink last:border-0 text-xs">
                                <div class="flex justify-between gap-2">
                                    <span><span class="font-bold">{{ $log->user?->name ?? 'system' }}</span> {{ $actionMap[$log->action] ?? $log->action }}</span>
                                    <span class="mono opacity-60 shrink-0">{{ $log->created_at->format('d.m H:i') }}</span>
                                </div>
                                @if($log->changes && $log->action === 'updated')
                                    <div class="mono mt-1 opacity-70">pola: {{ implode(', ', array_keys($log->changes)) }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="opacity-60">Brak wpisów.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
