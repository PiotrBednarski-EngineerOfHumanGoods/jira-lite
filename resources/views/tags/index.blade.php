<x-app-layout>
    <x-slot name="header">
        <div class="flex items-end justify-between gap-4">
            <div>
                <span class="eyebrow">/ tagi</span>
                <h2 class="text-4xl font-bold tracking-tight leading-none mt-2">Tagi</h2>
            </div>
            <a href="{{ route('tags.create') }}" class="btn btn-accent">+ Nowy tag</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <div class="card-flat overflow-x-auto" style="box-shadow: 4px 4px 0 0 #0A0A0A;">
            <table class="brutal">
                <thead>
                    <tr><th>Nazwa</th><th>Kolor</th><th>Użyć</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block h-4 w-4 border-2 border-ink" style="background-color: {{ $tag->color }};"></span>
                                    <span class="font-bold">#{{ $tag->name }}</span>
                                </div>
                            </td>
                            <td class="mono text-sm">{{ $tag->color }}</td>
                            <td class="mono font-bold">{{ $tag->tasks_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('tags.edit', $tag) }}" class="link font-bold mr-3">edytuj</a>
                                <form method="POST" action="{{ route('tags.destroy', $tag) }}" class="inline" onsubmit="return confirm('Usunąć tag „{{ $tag->name }}”?')">
                                    @csrf @method('DELETE')
                                    <button class="font-bold text-tomato hover:bg-tomato hover:text-white px-2">usuń</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-10 opacity-50">Brak tagów.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $tags->links() }}</div>
    </div>
</x-app-layout>
