<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Pulpit</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @php
                $cards = [
                    ['Projekty', $stats['projects_total'], 'aktywne: '.$stats['projects_active']],
                    ['Zadania', $stats['tasks_total'], 'gotowe: '.$stats['tasks_done']],
                    ['W toku', $stats['tasks_in_progress'], 'do zrobienia: '.$stats['tasks_todo']],
                    ['Moje zadania', $stats['my_tasks'], 'przypisane do mnie'],
                ];
            @endphp
            @foreach($cards as [$label, $value, $sub])
                <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                    <div class="text-xs text-gray-500 uppercase">{{ $label }}</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">{{ $value }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $sub }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Zadania wg statusu</h3>
                @foreach(\App\Models\Task::STATUSES as $st)
                    @php
                        $count = $tasksByStatus[$st] ?? 0;
                        $total = max(array_sum($tasksByStatus), 1);
                        $pct = round($count * 100 / $total);
                    @endphp
                    <div class="mb-2">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-600">{{ $st }}</span>
                            <span class="font-medium">{{ $count }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded">
                            <div class="h-2 bg-blue-500 rounded" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Wg priorytetu</h3>
                @foreach(\App\Models\Task::PRIORITIES as $p)
                    @php
                        $count = $tasksByPriority[$p] ?? 0;
                        $total = max(array_sum($tasksByPriority), 1);
                        $pct = round($count * 100 / $total);
                    @endphp
                    <div class="mb-2">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-600">{{ $p }}</span>
                            <span class="font-medium">{{ $count }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded">
                            <div class="h-2 bg-orange-500 rounded" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Nadchodzące terminy</h3>
                @forelse($upcoming as $task)
                    <div class="py-2 border-b border-gray-100 last:border-0">
                        <a href="{{ route('tasks.show', $task) }}" class="text-sm text-gray-800 hover:text-blue-600 block">{{ $task->title }}</a>
                        <div class="flex justify-between text-xs text-gray-500 mt-0.5">
                            <span>{{ $task->project->name }}</span>
                            <span>{{ $task->due_date->format('d.m.Y') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Brak terminów.</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Moje aktywne zadania</h3>
                @forelse($myAssigned as $task)
                    <div class="py-2 border-b border-gray-100 last:border-0 flex items-center justify-between">
                        <div class="min-w-0">
                            <a href="{{ route('tasks.show', $task) }}" class="text-sm text-gray-800 hover:text-blue-600">{{ $task->title }}</a>
                            <div class="text-xs text-gray-500">{{ $task->project->name }}</div>
                        </div>
                        <x-status-badge :status="$task->status" />
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Nie masz przypisanych zadań.</p>
                @endforelse
            </div>

            <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                <h3 class="font-semibold text-gray-700 mb-3">Ostatnia aktywność</h3>
                @php $actionMap = ['created' => 'utworzył', 'updated' => 'zmienił', 'deleted' => 'usunął'];
                     $typeMap = ['Project' => 'projekt', 'Task' => 'zadanie']; @endphp
                <div class="max-h-72 overflow-y-auto">
                    @forelse($recentActivity as $log)
                        <div class="py-1.5 border-b border-gray-100 last:border-0 text-xs">
                            <span class="font-medium">{{ $log->user?->name ?? 'system' }}</span>
                            {{ $actionMap[$log->action] ?? $log->action }}
                            {{ $typeMap[class_basename($log->auditable_type)] ?? class_basename($log->auditable_type) }}
                            #{{ $log->auditable_id }}
                            <span class="text-gray-400 float-right">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Brak aktywności.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
