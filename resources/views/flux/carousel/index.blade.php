@blaze

@props([
    'autoPlay' => false,
    'playDelay' => 5,
    'transition' => 'slide',
    'loop' => true,
    'visibleSlides' => 1,
    'indicators' => null,
])

@php
$classes = Flux::classes()
    ->add('relative w-full overflow-hidden');

$playDelayMs = $playDelay * 1000;
@endphp

<div
    {{ $attributes->class($classes) }}
    data-flux-carousel
    x-data="{
        currentIndex: 0,
        slideCount: 0,
        visibleSlides: {{ $visibleSlides }},
        autoPlay: {{ $autoPlay ? 'true' : 'false' }},
        isPlaying: false,
        playInterval: null,
        transition: '{{ $transition }}',
        loop: {{ $loop ? 'true' : 'false' }},

        init() {
            this.slideCount = this.$refs.slidesContainer?.children.length || 0;

            if (this.autoPlay) {
                this.startAutoPlay();
            }

            // Keyboard navigation
            this.$el.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    this.prev();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    this.next();
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    this.goTo(0);
                } else if (e.key === 'End') {
                    e.preventDefault();
                    this.goTo(this.slideCount - 1);
                }
            });
        },

        next() {
            if (this.currentIndex < this.slideCount - this.visibleSlides) {
                this.currentIndex++;
            } else if (this.loop) {
                this.currentIndex = 0;
            }
        },

        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else if (this.loop) {
                this.currentIndex = this.slideCount - this.visibleSlides;
            }
        },

        goTo(index) {
            if (index >= 0 && index < this.slideCount) {
                this.currentIndex = index;
            }
        },

        goToFirst() {
            this.currentIndex = 0;
        },

        goToLast() {
            this.currentIndex = Math.max(0, this.slideCount - this.visibleSlides);
        },

        canGoPrev() {
            return this.loop || this.currentIndex > 0;
        },

        canGoNext() {
            return this.loop || this.currentIndex < this.slideCount - this.visibleSlides;
        },

        startAutoPlay() {
            if (this.isPlaying) return;

            this.isPlaying = true;
            this.playInterval = setInterval(() => {
                this.next();
            }, {{ $playDelayMs }});
        },

        stopAutoPlay() {
            if (!this.isPlaying) return;

            this.isPlaying = false;
            if (this.playInterval) {
                clearInterval(this.playInterval);
                this.playInterval = null;
            }
        },

        toggleAutoPlay() {
            if (this.isPlaying) {
                this.stopAutoPlay();
            } else {
                this.startAutoPlay();
            }
        }
    }"
    @mouseenter="if (autoPlay) stopAutoPlay()"
    @mouseleave="if (autoPlay) startAutoPlay()"
    tabindex="0"
    role="region"
    aria-label="Carousel"
    aria-live="polite"
>
    {{ $slot }}
</div>
