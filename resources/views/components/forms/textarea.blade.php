@props([
    'name',
    'rows' => 2,
    'value' => null,
    'label' => null,
    'note' => null,
])

@if ($label)
    <label for="{{ $name }}" class="block font-semibold mb-1">
        {{ $label }}
        @if ($note)
            <span class="block text-gray-500 font-normal text-sm">({{ $note }})</span>
        @endif
    </label>
@endif

<textarea
    name="{{ $name }}"
    id="{{ $name }}"
    rows="{{ $rows }}"
    {{ $attributes->class([
        'w-full border rounded-lg px-2 py-1 shadow-sm focus:outline-none focus:ring-2',
        'border-red-500 ring-red-300' => $errors->has($name),
        'border-gray-300 focus:ring-blue-400' => !$errors->has($name),
    ]) }}
>{{ old($name, $value) }}</textarea>

@error($name)
    <span class="block text-red-500 text-sm mt-1">{{ $message }}</span>
@enderror