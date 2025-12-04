@props([
    'images' => [],
])

<div class="relative">
    <div x-data="{
    slides: @js($images),
    currentSlideIndex: 1,
    previous() {
        if (this.currentSlideIndex > 1) {
            this.currentSlideIndex = this.currentSlideIndex - 1
        } else {
            this.currentSlideIndex = this.slides.length
        }
    },
    next() {
        if (this.currentSlideIndex < this.slides.length) {
            this.currentSlideIndex = this.currentSlideIndex + 1
        } else {
            this.currentSlideIndex = 1
        }
    }
}" class="relative w-full">

        <!-- slides first -->
        <div class="relative w-full h-[300px] md:h-[600px] overflow-hidden">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="currentSlideIndex == index + 1"
                     class="w-full h-full absolute inset-0 flex items-center justify-center"
                     x-transition.opacity.duration.500ms>
                    <img class="max-w-full max-h-full object-contain rounded-xl"
                         :src="slide.image"
                         alt="Report Image" />
                </div>
            </template>

            <!-- Navigation buttons positioned on top of the image -->
            <!-- previous button -->
            <button type="button"
                    x-show="slides.length > 1"
                    class="absolute bg-white/40 dark:bg-zinc-800/40 left-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center p-2 text-zinc-800 dark:text-white transition hover:bg-white/60 dark:hover:bg-zinc-800/60"
                    aria-label="previous slide"
                    x-on:click="previous()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pr-0.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- next button -->
            <button type="button"
                    x-show="slides.length > 1"
                    class="absolute bg-white/40 dark:bg-zinc-800/40 right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center p-2 text-zinc-800 dark:text-white transition hover:bg-white/60 dark:hover:bg-zinc-800/60"
                    aria-label="next slide"
                    x-on:click="next()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pl-0.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

        <!-- indicators -->
        <div x-show="slides.length > 1"
             class="absolute rounded-radius -bottom-5 md:-bottom-8 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 bg-white/75 dark:bg-zinc-800/75 px-1.5 py-1 md:px-2"
             role="group"
             aria-label="slides">
            <template x-for="(slide, index) in slides" :key="index">
                <button class="size-2 rounded-full transition bg-zinc-800 dark:bg-white"
                        x-on:click="currentSlideIndex = index + 1"
                        :class="[currentSlideIndex === index + 1 ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-400 dark:bg-zinc-400']"
                        :aria-label="'slide ' + (index + 1)"></button>
            </template>
        </div>
    </div>
</div>
