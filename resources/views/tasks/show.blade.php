<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-800">{{ $task->title }}</h2>
            <div class="flex gap-2">
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1.5 bg-slate-700 hover:bg-slate-800 text-white text-sm rounded">Edytuj</a>
                @endcan
                @can('delete', $task)
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Usunąć zadanie „{{ $task->title }}”?')">
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
                <div class="bg-white p-4 rounded shadow-sm border border-slate-200">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <x-status-badge :status="$task->status" />
                        <x-status-badge :status="$task->priority" />
                        @foreach($task->tags as $tag)
                            <span class="text-xs text-slate-500">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $task->description ?: 'Brak opisu.' }}</p>
                </div>

                <div class="bg-white p-4 rounded shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-700 mb-3">Załączniki ({{ $task->attachments->count() }})</h3>
                    @forelse($task->attachments as $a)
                        <div class="py-2 border-b border-slate-100 last:border-0 flex items-center justify-between">
                            <div class="min-w-0">
                                <a href="{{ route('attachments.download', $a) }}" class="text-sm text-blue-600 hover:underline">{{ $a->original_name }}</a>
                                <div class="text-xs text-slate-500">{{ $a->humanSize() }} • dodał {{ $a->uploader?->name }}</div>
                            </div>
                            @if(auth()->id() === $a->uploaded_by || auth()->user()->isManager())
                                <form method="POST" action="{{ route('attachments.destroy', $a) }}" onsubmit="return confirm('Usunąć załącznik?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">usuń</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Brak załączników.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white p-4 rounded shadow-sm border border-slate-200 text-sm space-y-2">
                    <div><span class="text-slate-500">Projekt:</span> <a href="{{ route('projects.show', $task->project) }}" class="text-blue-600 hover:underline">{{ $task->project->name }}</a></div>
                    <div><span class="text-slate-500">Przypisane:</span> {{ $task->assignee?->name ?? '-' }}</div>
                    <div><span class="text-slate-500">Twórca:</span> {{ $task->creator?->name }}</div>
                    <div><span class="text-slate-500">Termin:</span> {{ $task->due_date?->format('d.m.Y') ?? '-' }}</div>
                    <div><span class="text-slate-500">Utworzono:</span> {{ $task->created_at->format('d.m.Y H:i') }}</div>
                </div>

                <div class="bg-white p-4 rounded shadow-sm border border-slate-200">
                    <h3 class="font-semibold text-slate-700 mb-3">Historia zmian</h3>
                    @php $actionMap = ['created' => 'utworzył', 'updated' => 'zmienił', 'deleted' => 'usunął']; @endphp
                    <div class="max-h-96 overflow-y-auto">
                        @forelse($auditLogs as $log)
                            <div class="py-1.5 border-b border-slate-100 last:border-0 text-xs">
                                <div>
                                    <span class="font-medium">{{ $log->user?->name ?? 'system' }}</span>
                                    {{ $actionMap[$log->action] ?? $log->action }}
                                </div>
                                <div class="text-slate-400">{{ $log->created_at->format('d.m.Y H:i') }}</div>
                                @if($log->changes && $log->action === 'updated')
                                    <div class="text-slate-500">pola: {{ implode(', ', array_keys($log->changes)) }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Brak wpisów.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
