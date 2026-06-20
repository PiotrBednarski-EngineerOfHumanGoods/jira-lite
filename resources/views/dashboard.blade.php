<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="eyebrow mb-2">/ {{ now()->locale('pl')->translatedFormat('l · j F') }}</span>
                <h2 class="text-4xl font-bold tracking-tight leading-none mt-2">Pulpit</h2>
            </div>
            <span class="pill" style="background:#3D5AFE; color:#fff">{{ auth()->user()->name }}</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            @php
                $cards = [
                    ['Projekty', $stats['projects_total'], 'aktywne: '.$stats['projects_active'], '#FFFFFF'],
                    ['Zadania', $stats['tasks_total'], 'gotowe: '.$stats['tasks_done'], '#A8E063'],
                    ['W toku', $stats['tasks_in_progress'], 'do zrobienia: '.$stats['tasks_todo'], '#3D5AFE'],
                    ['Moje', $stats['my_tasks'], 'przypisane do mnie', '#FFD93D'],
                ];
            @endphp
            @foreach($cards as [$label, $value, $sub, $bg])
                <div class="card p-4" style="background:{{ $bg }};">
                    <div class="mono text-xs font-bold uppercase tracking-wider">{{ $label }}</div>
                    <div class="text-5xl font-bold mt-2 leading-none">{{ $value }}</div>
                    <div class="mono text-xs mt-2 opacity-70">{{ $sub }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div class="card p-5">
                <h3 class="font-bold text-lg uppercase tracking-tight mb-4 border-b-2 border-ink pb-2">Wg statusu</h3>
                @foreach(\App\Models\Task::STATUSES as $st)
                    @php
                        $count = $tasksByStatus[$st] ?? 0;
                        $total = max(array_sum($tasksByStatus), 1);
                        $pct = round($count * 100 / $total);
                    @endphp
                    <div class="mb-3">
                        <div class="flex justify-between text-xs mb-1">
                            <x-status-badge :status="$st" />
                            <span class="mono font-bold">{{ $count }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-3 border-2 border-ink bg-cream">
                            <div class="h-full bg-cobalt border-r-2 border-ink" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card p-5">
                <h3 class="font-bold text-lg uppercase tracking-tight mb-4 border-b-2 border-ink pb-2">Wg priorytetu</h3>
                @foreach(\App\Models\Task::PRIORITIES as $p)
                    @php
                        $count = $tasksByPriority[$p] ?? 0;
                        $total = max(array_sum($tasksByPriority), 1);
                        $pct = round($count * 100 / $total);
                    @endphp
                    <div class="mb-3">
                        <div class="flex justify-between text-xs mb-1">
                            <x-status-badge :status="$p" />
                            <span class="mono font-bold">{{ $count }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-3 border-2 border-ink bg-cream">
                            <div class="h-full bg-tomato border-r-2 border-ink" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card p-5">
                <h3 class="font-bold text-lg uppercase tracking-tight mb-4 border-b-2 border-ink pb-2">Nadchodzące</h3>
                @forelse($upcoming as $task)
                    <div class="py-2 border-b-2 border-ink last:border-0">
                        <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold hover:bg-sun block">{{ $task->title }}</a>
                        <div class="flex justify-between mono text-xs mt-1">
                            <span class="opacity-60">{{ $task->project->name }}</span>
                            <span class="font-bold">{{ $task->due_date->format('d.m') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm opacity-60">Brak terminów.</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="card p-5">
                <h3 class="font-bold text-lg uppercase tracking-tight mb-4 border-b-2 border-ink pb-2">Moje aktywne zadania</h3>
                @forelse($myAssigned as $task)
                    <div class="py-3 border-b-2 border-ink last:border-0 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <a href="{{ route('tasks.show', $task) }}" class="text-sm font-semibold hover:bg-sun">{{ $task->title }}</a>
                            <div class="mono text-xs opacity-60 mt-1">{{ $task->project->name }}</div>
                        </div>
                        <div class="flex gap-1 shrink-0">
                            <x-status-badge :status="$task->status" />
                        </div>
                    </div>
                @empty
                    <p class="text-sm opacity-60">Brak przypisanych zadań.</p>
                @endforelse
            </div>

            <div class="card p-5">
                <h3 class="font-bold text-lg uppercase tracking-tight mb-4 border-b-2 border-ink pb-2">Ostatnia aktywność</h3>
                @php $actionMap = ['created' => 'utworzył', 'updated' => 'zmienił', 'deleted' => 'usunął'];
                     $typeMap = ['Project' => 'projekt', 'Task' => 'zadanie']; @endphp
                <div class="max-h-72 overflow-y-auto -mx-1 px-1">
                    @forelse($recentActivity as $log)
                        <div class="py-1.5 border-b-2 border-ink last:border-0 text-xs flex justify-between gap-2">
                            <span>
                                <span class="font-bold">{{ $log->user?->name ?? 'system' }}</span>
                                {{ $actionMap[$log->action] ?? $log->action }}
                                <span class="font-semibold">{{ $typeMap[class_basename($log->auditable_type)] ?? '' }}</span>
                                <span class="mono opacity-60">#{{ $log->auditable_id }}</span>
                            </span>
                            <span class="mono opacity-50 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-sm opacity-60">Brak aktywności.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
