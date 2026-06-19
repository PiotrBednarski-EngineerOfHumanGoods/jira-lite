<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-800">Tagi</h2>
            <a href="{{ route('tags.create') }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">+ Nowy tag</a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />

        <div class="bg-white shadow-sm rounded border border-slate-200 overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-600 uppercase">Nazwa</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-600 uppercase">Kolor</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-600 uppercase">Użyć</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tags as $tag)
                        <tr>
                            <td class="px-4 py-3 flex items-center gap-2">
                                <span class="inline-block h-3 w-3 rounded-full" style="background-color: {{ $tag->color }};"></span>
                                <span>#{{ $tag->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600 font-mono">{{ $tag->color }}</td>
                            <td class="px-4 py-3 text-sm">{{ $tag->tasks_count }}</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('tags.edit', $tag) }}" class="text-sm text-blue-600 hover:underline">edytuj</a>
                                <form method="POST" action="{{ route('tags.destroy', $tag) }}" class="inline" onsubmit="return confirm('Usunąć tag „{{ $tag->name }}”?')">
                                    @csrf @method('DELETE')
                                    <button class="text-sm text-red-600 hover:underline">usuń</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-slate-400">Brak tagów.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $tags->links() }}</div>
    </div>
</x-app-layout>
