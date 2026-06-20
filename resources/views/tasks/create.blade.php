<x-app-layout>
    <x-slot name="header">
        <span class="eyebrow">/ nowe zadanie</span>
        <h2 class="text-3xl font-bold tracking-tight leading-none mt-2">Nowe zadanie</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <x-flash />
        <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
            @csrf
            @include('tasks._form')
        </form>
    </div>
</x-app-layout>
