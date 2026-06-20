<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div class="min-w-0">
                <span class="eyebrow">/ zadanie #{{ $task->id }} · <a href="{{ route('projects.show', $task->project) }}" class="link">{{ $task->project->name }}</a></span>
                <h2 class="text-3xl font-bold tracking-tight leading-tight mt-2">{{ $task->title }}</h2>
            </div>
            <div class="flex gap-2 shrink-0">
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="btn">Edytuj</a>
                @endcan
                @can('delete', $task)
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Usunąć zadanie „{{ $task->title }}”?')">
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
                    <div class="flex flex-wrap items-center gap-2 mb-4 pb-3 border-b-2 border-ink">
                        <x-status-badge :status="$task->status" />
                        <x-status-badge :status="$task->priority" />
                        @foreach($task->tags as $tag)
                            <span class="mono text-xs opacity-60">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <p class="whitespace-pre-line">{{ $task->description ?: 'Brak opisu.' }}</p>
                </div>

                <div class="card p-5">
                    <h3 class="text-lg font-bold uppercase mb-3 pb-2 border-b-2 border-ink">Załączniki <span class="mono text-sm opacity-60">({{ $task->attachments->count() }})</span></h3>
                    @forelse($task->attachments as $a)
                        <div class="py-2.5 border-b-2 border-ink last:border-0 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <a href="{{ route('attachments.download', $a) }}" class="font-bold link">{{ $a->original_name }}</a>
                                <div class="mono text-xs opacity-60 mt-0.5">{{ $a->humanSize() }} · {{ $a->uploader?->name }}</div>
                            </div>
                            @if(auth()->id() === $a->uploaded_by || auth()->user()->isManager())
                                <form method="POST" action="{{ route('attachments.destroy', $a) }}" onsubmit="return confirm('Usunąć załącznik?')">
                                    @csrf @method('DELETE')
                                    <button class="mono text-xs font-bold text-tomato hover:bg-tomato hover:text-white px-2 py-1">USUŃ</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="opacity-60">Brak załączników.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                <div class="card p-5">
                    <h3 class="text-lg font-bold uppercase mb-3 pb-2 border-b-2 border-ink">Szczegóły</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-2"><dt class="opacity-60 mono text-xs uppercase">Projekt</dt><dd><a href="{{ route('projects.show', $task->project) }}" class="link font-semibold">{{ $task->project->name }}</a></dd></div>
                        <div class="flex justify-between gap-2"><dt class="opacity-60 mono text-xs uppercase">Przypisane</dt><dd class="font-semibold">{{ $task->assignee?->name ?? '—' }}</dd></div>
                        <div class="flex justify-between gap-2"><dt class="opacity-60 mono text-xs uppercase">Twórca</dt><dd class="font-semibold">{{ $task->creator?->name }}</dd></div>
                        <div class="flex justify-between gap-2"><dt class="opacity-60 mono text-xs uppercase">Termin</dt><dd class="mono">{{ $task->due_date?->format('d.m.Y') ?? '—' }}</dd></div>
                        <div class="flex justify-between gap-2"><dt class="opacity-60 mono text-xs uppercase">Utworzono</dt><dd class="mono">{{ $task->created_at->format('d.m.Y H:i') }}</dd></div>
                    </dl>
                </div>

                <div class="card p-5">
                    <h3 class="text-lg font-bold uppercase mb-3 pb-2 border-b-2 border-ink">Historia</h3>
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
