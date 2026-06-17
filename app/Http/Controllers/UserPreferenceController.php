<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'theme' => ['nullable', 'in:light,dark'],
            'projects_sort' => ['nullable', 'in:name,status,deadline,created_at'],
            'projects_dir' => ['nullable', 'in:asc,desc'],
        ]);
        $user = $request->user();
        if (array_key_exists('theme', $data) && $data['theme']) {
            $user->setPreference('theme', $data['theme']);
        }
        if (! empty($data['projects_sort'])) {
            $user->setPreference('projects.sort', $data['projects_sort']);
        }
        if (! empty($data['projects_dir'])) {
            $user->setPreference('projects.dir', $data['projects_dir']);
        }
        return back()->with('success', 'Preferencje zapisane.');
    }
}
