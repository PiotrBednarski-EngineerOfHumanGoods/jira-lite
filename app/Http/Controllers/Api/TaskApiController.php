<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Task::query()->with(['project:id,name', 'assignee:id,name', 'tags:id,name,color']);

        foreach (['status', 'priority', 'project_id', 'assigned_to'] as $f) {
            if ($val = $request->input($f)) {
                $query->where($f, $val);
            }
        }
        if ($search = $request->input('q')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($tasks);
    }

    public function show(Task $task): JsonResponse
    {
        $task->load(['project:id,name', 'assignee:id,name', 'tags:id,name,color', 'attachments:id,task_id,original_name,mime_type,size']);
        return response()->json($task);
    }
}
