@props(['title', 'subtitle' => null])
<div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
    <div>
        @if($subtitle)<span class="eyebrow mb-2">{{ $subtitle }}</span>@endif
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight leading-none mt-2">{{ $title }}</h1>
    </div>
    @isset($actions)<div class="flex gap-2">{{ $actions }}</div>@endisset
</div>
