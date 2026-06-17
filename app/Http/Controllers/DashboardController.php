<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $stats = [
            'projects_total' => Project::count(),
            'projects_active' => Project::where('status', 'active')->count(),
            'tasks_total' => Task::count(),
            'tasks_done' => Task::where('status', 'done')->count(),
            'tasks_todo' => Task::where('status', 'todo')->count(),
            'tasks_in_progress' => Task::where('status', 'in_progress')->count(),
            'my_tasks' => Task::where('assigned_to', $user->id)->whereIn('status', ['todo', 'in_progress', 'review'])->count(),
            'users_total' => User::count(),
        ];

        $tasksByStatus = Task::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $tasksByPriority = Task::query()
            ->selectRaw('priority, COUNT(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();

        $upcoming = Task::with(['project', 'assignee'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->toDateString())
            ->where('status', '!=', 'done')
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        $myAssigned = Task::with('project')
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        $recentActivity = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'stats', 'tasksByStatus', 'tasksByPriority',
            'upcoming', 'myAssigned', 'recentActivity'
        ));
    }
}
