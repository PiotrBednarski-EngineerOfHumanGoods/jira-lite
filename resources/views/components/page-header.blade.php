@props(['title', 'subtitle' => null])
<div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">{{ $title }}</h1>
        @if($subtitle)<p class="text-sm text-gray-500">{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)<div class="flex gap-2">{{ $actions }}</div>@endisset
</div>
