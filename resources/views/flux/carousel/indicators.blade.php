@blaze

@props([
    'variant' => 'dots', // dots or bars
])

@php
$containerClasses = Flux::classes()
    ->add('flex items-center justify-center gap-2 mt-4');

$indicatorBaseClasses = Flux::classes()
    ->add('transition-all duration-300 cursor-pointer')
    ->add(match ($variant) {
        'dots' => 'w-2.5 h-2.5 rounded-full',
        'bars' => 'h-1 rounded-full',
        default => 'w-2.5 h-2.5 rounded-full',
    });

$inactiveClasses = match ($variant) {
    'dots' => 'bg-zinc-300 dark:bg-zinc-600 hover:bg-zinc-400 dark:hover:bg-zinc-500',
    'bars' => 'w-8 bg-zinc-300 dark:bg-zinc-600 hover:bg-zinc-400 dark:hover:bg-zinc-500',
    default => 'bg-zinc-300 dark:bg-zinc-600',
};

$activeClasses = match ($variant) {
    'dots' => 'bg-zinc-900 dark:bg-zinc-100 scale-125',
    'bars' => 'w-12 bg-zinc-900 dark:bg-zinc-100',
    default => 'bg-zinc-900 dark:bg-zinc-100',
};
@endphp

<div {{ $attributes->class($containerClasses) }} data-flux-carousel-indicators role="tablist">
    <template x-for="(slide, index) in slideCount" :key="index">
        <button
            @click="goTo(index)"
            :class="[
                '{{ $indicatorBaseClasses }}',
                index === currentIndex ? '{{ $activeClasses }}' : '{{ $inactiveClasses }}'
            ]"
            :aria-label="`Go to slide ${index + 1}`"
            :aria-selected="index === currentIndex"
            role="tab"
            type="button"
        ></button>
    </template>
</div>
