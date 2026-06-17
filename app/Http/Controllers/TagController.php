<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagStoreRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('tasks')->orderBy('name')->paginate(20);
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(TagStoreRequest $request)
    {
        Tag::create($request->validated());
        return redirect()->route('tags.index')->with('success', 'Tag utworzony.');
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(TagStoreRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return redirect()->route('tags.index')->with('success', 'Tag zaktualizowany.');
    }

    public function destroy(Tag $tag)
    {
        if (! request()->user()?->isManager()) {
            abort(403);
        }
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Tag usunięty.');
    }
}
