@props([
    'name',
    'label' => null,
    'note' => null,
    'options' => [],
    'placeholder' => '-- Pilih salah satu --',
    'selected' => old($name)
])

@if($label)
    <label for="{{ $name }}" class="block font-semibold mb-1">
        {{ $label }}
        @if ($note)
            <span class="block text-gray-500 font-normal text-sm">({{ $note }})</span>
        @endif
    </label>
@endif

<select name="{{ $name }}" id="{{ $name }}"
    {{ $attributes->class([
        'w-full border rounded-lg px-2 py-1 shadow-sm focus:outline-none focus:ring-2',
        'border-red-500 ring-red-300' => $errors->has($name),
        'border-gray-300 focus:ring-blue-400' => !$errors->has($name),
    ]) }}>
    @if($placeholder)
        <option value="" selected disabled>{{ $placeholder }}</option>
    @endif

    @foreach($options as $key => $value)
        <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach
</select>

@error($name)
    <span class="block text-red-500 text-sm mt-1">{{ $message }}</span>
@enderror