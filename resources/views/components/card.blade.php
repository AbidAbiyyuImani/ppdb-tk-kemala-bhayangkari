@props([
    'headerClass' => 'mb-4',
    'footerClass' => 'mt-4',
])

<div {{ $attributes->merge(['class' => 'w-full overflow-x-auto bg-white rounded-lg shadow-sm p-4']) }}>
    @isset($header)
        <div class="{{ $headerClass }}">
            {{ $header }}
        </div>
    @endisset
    {{ $slot }}
    @isset($footer)
        <div class="{{ $footerClass }}">
            {{ $footer }}
        </div>
    @endisset
</div>