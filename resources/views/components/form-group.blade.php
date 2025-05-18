@props(['id' => null])
<div @if ($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => 'flex flex-col mb-2']) }}>
    {{ $slot }}
</div>