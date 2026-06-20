@php
    $isEdit = isset($project);
    $selectedMembers = $selectedMembers ?? [];
@endphp
<div class="card p-6 space-y-5">
    <div>
        <x-input-label value="Nazwa *" />
        <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}" required minlength="3" maxlength="120"
               class="input @error('name') border-tomato @enderror">
        @error('name')<p class="mono text-xs font-bold text-tomato mt-1.5">— {{ $message }}</p>@enderror
    </div>

    <div>
        <x-input-label value="Opis" />
        <textarea name="description" rows="4" maxlength="2000" class="input">{{ old('description', $project->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-input-label value="Status *" />
            <select name="status" class="input">
                @foreach(\App\Models\Project::STATUSES as $s)
                    <option value="{{ $s }}" @selected(old('status', $project->status ?? 'active') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label value="Deadline" />
            <input type="date" name="deadline" value="{{ old('deadline', isset($project) && $project->deadline ? $project->deadline->format('Y-m-d') : '') }}"
                   class="input @error('deadline') border-tomato @enderror">
            @error('deadline')<p class="mono text-xs font-bold text-tomato mt-1.5">— {{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <x-input-label value="Członkowie zespołu" />
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-48 overflow-y-auto border-2 border-ink p-3">
            @foreach($users as $u)
                @php $checked = in_array($u->id, old('members', $selectedMembers)); @endphp
                <label class="flex items-center gap-2 text-sm cursor-pointer hover:bg-sun px-1 py-0.5">
                    <input type="checkbox" name="members[]" value="{{ $u->id }}" @checked($checked) class="h-4 w-4 border-2 border-ink rounded-none accent-cobalt">
                    <span class="font-medium">{{ $u->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end gap-3 pt-3 border-t-2 border-ink">
        <a href="{{ route('projects.index') }}" class="btn">Anuluj</a>
        <button class="btn btn-primary">{{ $isEdit ? 'Zapisz zmiany' : 'Utwórz projekt' }} &rarr;</button>
    </div>
</div>
