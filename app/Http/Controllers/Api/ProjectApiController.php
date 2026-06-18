<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Project::query()->with(['creator:id,name', 'members:id,name'])
            ->withCount('tasks');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($projects);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['creator:id,name', 'members:id,name', 'tasks:id,project_id,title,status,priority']);
        return response()->json($project);
    }
}
