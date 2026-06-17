<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $allowed = ['title', 'status', 'priority', 'due_date', 'created_at'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        $query = Task::query()->with(['project', 'assignee', 'tags']);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        foreach (['status', 'priority', 'project_id', 'assigned_to'] as $f) {
            if ($val = $request->input($f)) {
                $query->where($f, $val);
            }
        }

        if ($tagId = $request->input('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $tagId));
        }

        $tasks = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'projects', 'users', 'tags', 'sort', 'dir'));
    }

    public function create(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedProject = $request->input('project_id');
        return view('tasks.create', compact('projects', 'users', 'tags', 'selectedProject'));
    }

    public function store(TaskStoreRequest $request)
    {
        $data = $request->validated();
        $tagIds = $data['tags'] ?? [];
        $files = $request->file('attachments', []);
        unset($data['tags'], $data['attachments']);

        $data['created_by'] = $request->user()->id;
        $task = Task::create($data);
        $task->tags()->sync($tagIds);

        foreach ($files as $file) {
            $path = $file->store('attachments', 'public');
            $task->attachments()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', "Zadanie „{$task->title}” utworzone.");
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee', 'creator', 'tags', 'attachments.uploader']);
        $auditLogs = $task->auditLogs()->with('user')->orderByDesc('created_at')->limit(15)->get();
        return view('tasks.show', compact('task', 'auditLogs'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedTags = $task->tags->pluck('id')->toArray();
        return view('tasks.edit', compact('task', 'projects', 'users', 'tags', 'selectedTags'));
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $data = $request->validated();
        $tagIds = $data['tags'] ?? [];
        $files = $request->file('attachments', []);
        unset($data['tags'], $data['attachments']);

        $task->update($data);
        $task->tags()->sync($tagIds);

        foreach ($files as $file) {
            $path = $file->store('attachments', 'public');
            $task->attachments()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Zadanie zaktualizowane.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $title = $task->title;
        foreach ($task->attachments as $a) {
            $a->delete();
        }
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', "Zadanie „{$title}” usunięte.");
    }
}
