@blaze

@php
$classes = Flux::classes()
    ->add('relative w-full h-full overflow-hidden');
@endphp

<div {{ $attributes->class($classes) }} data-flux-carousel-slides>
    <div
        x-ref="slidesContainer"
        :class="transition === 'fade' ? 'relative' : 'flex'"
        class="transition-transform duration-500 ease-in-out h-full"
        :style="transition === 'slide' ? { transform: `translateX(-${(currentIndex * 100) / visibleSlides}%)` } : {}"
        role="list"
    >
        {{ $slot }}
    </div>
</div>
