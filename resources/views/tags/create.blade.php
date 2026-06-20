<x-app-layout>
    <x-slot name="header">
        <span class="eyebrow">/ nowy tag</span>
        <h2 class="text-3xl font-bold tracking-tight leading-none mt-2">Nowy tag</h2>
    </x-slot>

    <div class="max-w-md mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-flash />
        <form method="POST" action="{{ route('tags.store') }}" class="card p-5 space-y-4">
            @csrf
            <div>
                <x-input-label value="Nazwa *" />
                <input type="text" name="name" value="{{ old('name') }}" required minlength="2" maxlength="30"
                       class="input @error('name') border-tomato @enderror">
                @error('name')<p class="mono text-xs font-bold text-tomato mt-1.5">— {{ $message }}</p>@enderror
            </div>
            <div>
                <x-input-label value="Kolor *" />
                <input type="color" name="color" value="{{ old('color', '#3D5AFE') }}" class="h-12 w-24 border-2 border-ink cursor-pointer">
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t-2 border-ink">
                <a href="{{ route('tags.index') }}" class="btn">Anuluj</a>
                <button class="btn btn-primary">Utwórz &rarr;</button>
            </div>
        </form>
    </div>
</x-app-layout>
