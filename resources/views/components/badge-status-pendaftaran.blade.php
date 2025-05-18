@props([
    'data' => null
])
@php
    switch ($data) {
        case 'Diajukan':
            $badgeClass = 'bg-yellow-100 text-yellow-800';
            break;
        case 'Diterima':
            $badgeClass = 'bg-green-100 text-green-800';
            break;
        case 'Ditolak':
            $badgeClass = 'bg-red-100 text-red-800';
            break;
        default:
            $badgeClass = 'bg-gray-100 text-gray-800';
    }
@endphp

<span class="px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
    {{ $data }}
</span>