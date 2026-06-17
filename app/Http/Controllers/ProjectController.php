<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $defaultSort = $request->user()->preference('projects.sort', 'created_at');
        $defaultDir = $request->user()->preference('projects.dir', 'desc');

        $sort = $request->input('sort', $defaultSort);
        $dir = $request->input('dir', $defaultDir) === 'asc' ? 'asc' : 'desc';
        $allowed = ['name', 'status', 'deadline', 'created_at'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        $query = Project::query()
            ->with(['creator', 'members'])
            ->withCount('tasks');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($memberId = $request->input('member')) {
            $query->whereHas('members', fn($q) => $q->where('users.id', $memberId));
        }

        $projects = $query->orderBy($sort, $dir)->paginate(10)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('projects.index', compact('projects', 'sort', 'dir', 'users'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        $users = User::orderBy('name')->get();
        return view('projects.create', compact('users'));
    }

    public function store(ProjectStoreRequest $request)
    {
        $data = $request->validated();
        $members = $data['members'] ?? [];
        unset($data['members']);
        $data['created_by'] = $request->user()->id;

        $project = Project::create($data);
        $project->members()->sync(array_fill_keys($members, ['project_role' => 'member']));
        $project->members()->syncWithoutDetaching([$request->user()->id => ['project_role' => 'owner']]);

        return redirect()->route('projects.show', $project)
            ->with('success', "Projekt „{$project->name}” został utworzony.");
    }

    public function show(Project $project)
    {
        $project->load(['creator', 'members', 'tasks.assignee', 'tasks.tags']);
        $auditLogs = $project->auditLogs()->with('user')->orderByDesc('created_at')->limit(15)->get();
        return view('projects.show', compact('project', 'auditLogs'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $users = User::orderBy('name')->get();
        $selectedMembers = $project->members->pluck('id')->toArray();
        return view('projects.edit', compact('project', 'users', 'selectedMembers'));
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $data = $request->validated();
        $members = $data['members'] ?? [];
        unset($data['members']);

        $project->update($data);
        $syncData = collect($members)->mapWithKeys(fn($id) => [$id => ['project_role' => 'member']])->toArray();
        $syncData[$project->created_by] = ['project_role' => 'owner'];
        $project->members()->sync($syncData);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projekt zaktualizowany.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $name = $project->name;
        $project->delete();
        return redirect()->route('projects.index')
            ->with('success', "Projekt „{$name}” usunięty.");
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'projekty_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($request) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Nazwa', 'Status', 'Deadline', 'Twórca', 'Zadań', 'Utworzono'], ';');

            $query = Project::query()->with('creator')->withCount('tasks');
            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }
            if ($search = $request->input('q')) {
                $query->where('name', 'like', "%{$search}%");
            }

            $query->orderBy('created_at', 'desc')->chunk(200, function ($chunk) use ($out) {
                foreach ($chunk as $p) {
                    fputcsv($out, [
                        $p->id, $p->name, $p->status,
                        $p->deadline?->format('Y-m-d') ?? '',
                        $p->creator?->name ?? '',
                        $p->tasks_count,
                        $p->created_at->format('Y-m-d H:i'),
                    ], ';');
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
