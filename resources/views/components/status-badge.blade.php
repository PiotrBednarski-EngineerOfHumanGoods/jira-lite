@props(['status'])
@php
    $map = [
        'active'      => ['#A8E063', 'aktywny'],
        'on_hold'     => ['#FFD93D', 'wstrzymany'],
        'archived'    => ['#E5E5E5', 'archiwum'],
        'todo'        => ['#FFFFFF', 'do zrobienia'],
        'in_progress' => ['#3D5AFE', 'w toku', '#FFFFFF'],
        'review'      => ['#FFD93D', 'review'],
        'done'        => ['#A8E063', 'gotowe'],
        'low'         => ['#E5E5E5', 'niski'],
        'medium'      => ['#FFD93D', 'średni'],
        'high'        => ['#FF5C38', 'wysoki', '#FFFFFF'],
        'urgent'      => ['#0A0A0A', 'pilny', '#FF5C38'],
    ];
    $entry = $map[$status] ?? ['#FFFFFF', $status];
    [$bg, $label] = [$entry[0], $entry[1]];
    $fg = $entry[2] ?? '#0A0A0A';
@endphp
<span class="pill" style="background:{{ $bg }}; color:{{ $fg }};">{{ $label }}</span>
