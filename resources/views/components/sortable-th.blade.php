@props(['column', 'label', 'current', 'dir'])
@php
    $isActive = $current === $column;
    $newDir = $isActive && $dir === 'asc' ? 'desc' : 'asc';
    $query = array_merge(request()->query(), ['sort' => $column, 'dir' => $newDir]);
    $url = url()->current() . '?' . http_build_query($query);
@endphp
<th>
    <a href="{{ $url }}" class="inline-flex items-center gap-1.5 {{ $isActive ? 'text-sun' : '' }}">
        {{ $label }}
        @if($isActive)<span>{{ $dir === 'asc' ? '↑' : '↓' }}</span>@endif
    </a>
</th>
