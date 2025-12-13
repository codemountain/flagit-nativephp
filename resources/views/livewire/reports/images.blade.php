<div class="relative">
    <div class="overflow-hidden relative">
        <x-ui.report-details-bottom-nav
            report-id="{{$report->report_id}}"  notes="{{$report->notes()->count()}}"/>
        <!-- Full-page carousel with swipe gestures -->
        <flux:carousel
            :auto-play="false"
            :visible-slides="1"
            transition="slide"
            :loop="true"
            class="h-[90vh] w-screen"
        >
            <flux:carousel.slides class="h-[90vh] bg-white dark:bg-zinc-900">
                @foreach($report->images as $img)
                    <flux:carousel.slide>
                        <div class="h-[90vh] w-full flex items-center justify-center bg-white dark:bg-zinc-900 rounded-lg overflow-hidden">
                            <img
                                src="{{ App\Helpers\ImageStorage::url($img) }}"
                                class="max-h-full max-w-full object-contain rounded-2xl block"
                                alt=""
                            />
                        </div>
                    </flux:carousel.slide>
                @endforeach
            </flux:carousel.slides>

            <!-- Fixed dots indicator at bottom -->
            <div class="fixed bottom-32 left-0 right-0 z-10 flex justify-center gap-2">
                <template x-for="(slide, index) in slideCount" :key="index">
                    <button
                        @click="goTo(index)"
                        :class="index === currentIndex ? 'bg-white w-8' : 'bg-white/40 w-2'"
                        class="h-2 rounded-full transition-all duration-300"
                    ></button>
                </template>
            </div>

            <!-- Last slide buttons (shown only on last slide) -->
            <div class="absolute top-1/2 w-full flex justify-between px-6 z-20 ">
                <flux:carousel.nav action="prev" icon="chevron-left" class="carousel-nav-button" />
                <flux:carousel.nav action="next" icon="chevron-right" class="carousel-nav-button" />
            </div>
            <div class="absolute bottom-1/4 left-0 w-full opacity-60 text-center">
                <flux:button
                    href="{{route('reports.details.imageadd', ['id'=>$report->report_id])}}"
                    variant="primary"
                    class="mobile"
                    wire:navigate>
                    {{__('Add Image')}}
                </flux:button>
            </div>
        </flux:carousel>
    </div>


</div>
