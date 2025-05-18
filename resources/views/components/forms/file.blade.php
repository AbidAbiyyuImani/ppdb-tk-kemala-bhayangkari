@props([
    'name',
    'label' => null,
    'note' => null,
    'onChange' => null,
    'existingFilename' => null,
])

@if ($label)
    <label for="{{ $name }}" class="block font-semibold mb-1">
        {{ $label }}
        @if ($note)
            <span class="block text-gray-500 font-normal text-sm">({{ $note }})</span>
        @endif
    </label>
@endif

<div class="space-y-0.5">
    <input
        type="file"
        name="{{ $name }}"
        id="{{ $name }}"
        onchange="{{ $onChange ?? '' }}"
        {{ $attributes->class([
            'w-full border rounded-lg px-2 py-1 shadow-sm file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-600 transition',
            'border-red-500 ring-red-300' => $errors->has($name),
            'border-gray-300 focus:ring-blue-400' => !$errors->has($name),
        ]) }}
    />

    @if ($existingFilename)
        <div class="text-sm underline text-blue-600 hover:text-blue-800 italic">
            <a href="{{ asset('storage/' . $existingFilename) }}" target="_blank"><span class="font-medium">Lihat file saat ini.</span></a>
        </div>
    @endif
</div>

@error($name)
    <span class="block text-red-500 text-sm mt-1">{{ $message }}</span>
@enderror