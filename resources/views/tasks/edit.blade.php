<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-slate-800">Edytuj zadanie</h2></x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />
        <form method="POST" action="{{ route('tasks.update', $task) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('tasks._form', ['selectedTags' => $selectedTags])
        </form>
    </div>
</x-app-layout>
