@props(['column', 'label', 'current', 'dir'])
@php
    $isActive = $current === $column;
    $newDir = $isActive && $dir === 'asc' ? 'desc' : 'asc';
    $query = array_merge(request()->query(), ['sort' => $column, 'dir' => $newDir]);
    $url = url()->current() . '?' . http_build_query($query);
@endphp
<th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">
    <a href="{{ $url }}" class="hover:text-gray-900">
        {{ $label }}
        @if($isActive)
            <span class="text-blue-600">{{ $dir === 'asc' ? '▲' : '▼' }}</span>
        @endif
    </a>
</th>
