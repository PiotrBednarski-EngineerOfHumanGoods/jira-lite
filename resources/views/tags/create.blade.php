<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-slate-800">Nowy tag</h2></x-slot>

    <div class="max-w-md mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />
        <form method="POST" action="{{ route('tags.store') }}" class="bg-white p-6 rounded shadow-sm border border-slate-200 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nazwa *</label>
                <input type="text" name="name" value="{{ old('name') }}" required minlength="2" maxlength="30"
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm @error('name') border-red-500 @enderror">
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kolor *</label>
                <input type="color" name="color" value="{{ old('color', '#6b7280') }}" class="h-10 w-20 border border-slate-300 rounded">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('tags.index') }}" class="px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded">Anuluj</a>
                <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">Utwórz</button>
            </div>
        </form>
    </div>
</x-app-layout>
