@php
    $isEdit = isset($task);
    $selectedTags = $selectedTags ?? [];
    $selectedProject = $selectedProject ?? ($task->project_id ?? null);
@endphp
<div class="bg-white p-6 rounded shadow-sm border border-slate-200 space-y-4">

    @unless($isEdit)
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Projekt *</label>
            <select name="project_id" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm @error('project_id') border-red-500 @enderror">
                <option value="">— wybierz projekt —</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected(old('project_id', $selectedProject) == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
            @error('project_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    @endunless

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Tytuł *</label>
        <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" required minlength="3" maxlength="150"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm @error('title') border-red-500 @enderror">
        @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Opis</label>
        <textarea name="description" rows="4" maxlength="3000" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">{{ old('description', $task->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
            <select name="status" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                @foreach(\App\Models\Task::STATUSES as $s)
                    <option value="{{ $s }}" @selected(old('status', $task->status ?? 'todo') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Priorytet</label>
            <select name="priority" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                @foreach(\App\Models\Task::PRIORITIES as $p)
                    <option value="{{ $p }}" @selected(old('priority', $task->priority ?? 'medium') === $p)>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Przypisane do</label>
            <select name="assigned_to" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                <option value="">— nieprzypisane —</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(old('assigned_to', $task->assigned_to ?? null) == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Termin</label>
            <input type="date" name="due_date" value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Tagi</label>
        <div class="flex flex-wrap gap-2">
            @foreach($tags as $tag)
                @php $checked = in_array($tag->id, old('tags', $selectedTags)); @endphp
                <label class="cursor-pointer">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked($checked) class="hidden peer">
                    <span class="inline-block text-xs px-3 py-1 border border-slate-300 rounded peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500">#{{ $tag->name }}</span>
                </label>
            @endforeach
            @if($tags->isEmpty())
                <span class="text-sm text-slate-400">Brak tagów. Manager może je dodać.</span>
            @endif
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Załączniki <span class="text-xs text-slate-500">(max 5 plików, do 5 MB)</span></label>
        <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip"
               class="block w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
        @error('attachments.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end gap-2 pt-2">
        <a href="{{ route('tasks.index') }}" class="px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded">Anuluj</a>
        <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">{{ $isEdit ? 'Zapisz' : 'Utwórz zadanie' }}</button>
    </div>
</div>
