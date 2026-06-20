@php
    $isEdit = isset($task);
    $selectedTags = $selectedTags ?? [];
    $selectedProject = $selectedProject ?? ($task->project_id ?? null);
@endphp
<div class="card p-6 space-y-5">

    @unless($isEdit)
        <div>
            <x-input-label value="Projekt *" />
            <select name="project_id" required class="input @error('project_id') border-tomato @enderror">
                <option value="">— wybierz projekt —</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected(old('project_id', $selectedProject) == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
            @error('project_id')<p class="mono text-xs font-bold text-tomato mt-1.5">— {{ $message }}</p>@enderror
        </div>
    @endunless

    <div>
        <x-input-label value="Tytuł *" />
        <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" required minlength="3" maxlength="150"
               class="input @error('title') border-tomato @enderror">
        @error('title')<p class="mono text-xs font-bold text-tomato mt-1.5">— {{ $message }}</p>@enderror
    </div>

    <div>
        <x-input-label value="Opis" />
        <textarea name="description" rows="4" maxlength="3000" class="input">{{ old('description', $task->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label value="Status" />
            <select name="status" class="input">
                @foreach(\App\Models\Task::STATUSES as $s)
                    <option value="{{ $s }}" @selected(old('status', $task->status ?? 'todo') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label value="Priorytet" />
            <select name="priority" class="input">
                @foreach(\App\Models\Task::PRIORITIES as $p)
                    <option value="{{ $p }}" @selected(old('priority', $task->priority ?? 'medium') === $p)>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label value="Przypisane do" />
            <select name="assigned_to" class="input">
                <option value="">— nieprzypisane —</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(old('assigned_to', $task->assigned_to ?? null) == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label value="Termin" />
            <input type="date" name="due_date" value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="input">
        </div>
    </div>

    <div>
        <x-input-label value="Tagi" />
        <div class="flex flex-wrap gap-2">
            @foreach($tags as $tag)
                @php $checked = in_array($tag->id, old('tags', $selectedTags)); @endphp
                <label class="cursor-pointer">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked($checked) class="hidden peer">
                    <span class="inline-block mono text-xs font-bold uppercase tracking-wider px-3 py-1.5 border-2 border-ink bg-white peer-checked:bg-ink peer-checked:text-cream">#{{ $tag->name }}</span>
                </label>
            @endforeach
            @if($tags->isEmpty())
                <span class="opacity-60 text-sm">Brak tagów. Manager może je dodać.</span>
            @endif
        </div>
    </div>

    <div>
        <x-input-label value="Załączniki (max 5 plików × 5 MB)" />
        <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip"
               class="block w-full text-sm border-2 border-ink p-2 bg-white file:mr-3 file:py-1.5 file:px-3 file:border-2 file:border-ink file:bg-sun file:font-bold file:text-xs file:uppercase hover:file:bg-ink hover:file:text-cream cursor-pointer">
        @error('attachments.*')<p class="mono text-xs font-bold text-tomato mt-1.5">— {{ $message }}</p>@enderror
    </div>

    <div class="flex justify-end gap-3 pt-3 border-t-2 border-ink">
        <a href="{{ route('tasks.index') }}" class="btn">Anuluj</a>
        <button class="btn btn-primary">{{ $isEdit ? 'Zapisz' : 'Utwórz zadanie' }} &rarr;</button>
    </div>
</div>
