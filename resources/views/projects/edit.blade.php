<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Edytuj projekt: {{ $project->name }}</h2></x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-flash />
        <form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf @method('PUT')
            @include('projects._form', ['selectedMembers' => $selectedMembers])
        </form>
    </div>
</x-app-layout>
