<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, Project $project): bool
    {
        return $user->isAdmin()
            || $project->created_by === $user->id
            || $project->members()->where('users.id', $user->id)->wherePivot('project_role', 'owner')->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isAdmin() || $project->created_by === $user->id;
    }
}
