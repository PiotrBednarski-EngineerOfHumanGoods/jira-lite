@php
    $isEdit = isset($project);
    $selectedMembers = $selectedMembers ?? [];
@endphp
<div class="bg-white p-6 rounded shadow-sm border border-gray-200 space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nazwa *</label>
        <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}" required minlength="3" maxlength="120"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm @error('name') border-red-500 @enderror">
        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Opis</label>
        <textarea name="description" rows="4" maxlength="2000"
                  class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('description', $project->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
            <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                @foreach(\App\Models\Project::STATUSES as $s)
                    <option value="{{ $s }}" @selected(old('status', $project->status ?? 'active') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
            <input type="date" name="deadline" value="{{ old('deadline', isset($project) && $project->deadline ? $project->deadline->format('Y-m-d') : '') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm @error('deadline') border-red-500 @enderror">
            @error('deadline')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Członkowie zespołu</label>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-48 overflow-y-auto border border-gray-200 p-3 rounded">
            @foreach($users as $u)
                @php $checked = in_array($u->id, old('members', $selectedMembers)); @endphp
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="members[]" value="{{ $u->id }}" @checked($checked) class="rounded">
                    <span>{{ $u->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end gap-2 pt-2">
        <a href="{{ route('projects.index') }}" class="px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Anuluj</a>
        <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">{{ $isEdit ? 'Zapisz zmiany' : 'Utwórz projekt' }}</button>
    </div>
</div>
