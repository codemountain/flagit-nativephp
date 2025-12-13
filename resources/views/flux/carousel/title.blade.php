@blaze

@props([
    'size' => 'lg',
])

@php
$classes = Flux::classes()
    ->add('font-semibold text-zinc-900 dark:text-zinc-100 mb-4')
    ->add(match ($size) {
        'sm' => 'text-base',
        'base' => 'text-lg',
        'lg' => 'text-xl',
        'xl' => 'text-2xl',
        '2xl' => 'text-3xl',
        default => 'text-xl',
    });
@endphp

<h3 {{ $attributes->class($classes) }} data-flux-carousel-title>
    {{ $slot }}
</h3>
