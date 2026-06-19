@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500']) }}>
