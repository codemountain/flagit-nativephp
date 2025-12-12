@php $attributes = $unescapedForwardedAttributes ?? $attributes; @endphp

@props([
    'variant' => 'outline',
])

@php
    $classes = Flux::classes('shrink-0')
        ->add(match($variant) {
            'outline' => '[:where(&)]:size-6',
            'solid' => '[:where(&)]:size-6',
            'mini' => '[:where(&)]:size-5',
            'micro' => '[:where(&)]:size-4',
        });
@endphp

{{-- Your SVG code here: --}}
<svg {{ $attributes->class($classes) }} data-flux-icon aria-hidden="true" viewBox="0 0 543.7 470.86" >
    <defs><style>.cls-1{fill:#ffcd00;}</style></defs><g id="Layer_1-2"><g><path class="cls-1" d="M0,470.86L271.82,0l271.88,470.86H0Z"/><path d="M271.82,298.62l-39.71,28.55v105.25h-49.91v-126.55l60.35-43.45V129.09h58.54v133.31l60.35,43.45v126.55h-49.91v-105.25l-39.71-28.55h0Z"/></g></g>
</svg>
