@props([
    'type' => 'button',
    'cursor' => 'cursor-pointer',
    'id' => null,
    'color' => 'bg-gray-900',
    'onlick' => null,
    'disabled' => false,
])

<button type="{{ $type }}"
        @if($id) id="{{ $id }}" @endif
        @if ($onlick) onclick="{{ $onlick }}" @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => "$cursor disabled:cursor-not-allowed disabled:brightness-75 disabled:opacity-50 $color text-white rounded-md px-2 py-1 mb-2"]) }}
>
    {{ $slot }}
</button>