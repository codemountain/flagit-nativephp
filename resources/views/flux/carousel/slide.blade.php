@blaze

@php
$classes = Flux::classes()
    ->add('flex-shrink-0 w-full h-full')
    ->add('px-2'); // Add some spacing between slides
@endphp

<div
    {{ $attributes->class($classes) }}
    data-flux-carousel-slide
    x-data="{ slideIndex: Array.from($el.parentElement.children).indexOf($el) }"
    :class="{
        'absolute inset-0 transition-opacity duration-500': transition === 'fade',
        'opacity-100': transition === 'fade' && slideIndex === currentIndex,
        'opacity-0 pointer-events-none': transition === 'fade' && slideIndex !== currentIndex
    }"
    :style="transition === 'slide' ? `width: ${100 / visibleSlides}%` : 'width: 100%'"
    role="listitem"
    :aria-hidden="slideIndex !== currentIndex ? 'true' : 'false'"
>
    <div class="w-full h-full bg-white dark:bg-zinc-800 rounded-lg overflow-hidden">
        {{ $slot }}
    </div>
</div>
