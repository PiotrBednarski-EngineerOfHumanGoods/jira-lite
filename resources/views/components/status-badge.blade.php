@props(['status'])
@php
    $map = [
        'active'      => ['bg-green-100 text-green-700', 'aktywny'],
        'on_hold'     => ['bg-yellow-100 text-yellow-700', 'wstrzymany'],
        'archived'    => ['bg-gray-100 text-gray-600', 'archiwum'],
        'todo'        => ['bg-gray-100 text-gray-700', 'do zrobienia'],
        'in_progress' => ['bg-blue-100 text-blue-700', 'w toku'],
        'review'      => ['bg-purple-100 text-purple-700', 'review'],
        'done'        => ['bg-green-100 text-green-700', 'gotowe'],
        'low'         => ['bg-gray-100 text-gray-600', 'niski'],
        'medium'      => ['bg-blue-100 text-blue-700', 'średni'],
        'high'        => ['bg-orange-100 text-orange-700', 'wysoki'],
        'urgent'      => ['bg-red-100 text-red-700', 'pilny'],
    ];
    [$cls, $label] = $map[$status] ?? ['bg-gray-100 text-gray-700', $status];
@endphp
<span class="inline-block text-xs px-2 py-0.5 rounded {{ $cls }}">{{ $label }}</span>
