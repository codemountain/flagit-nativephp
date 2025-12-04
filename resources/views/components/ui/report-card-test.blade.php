@props([
    /** @var \mixed */
    'report',
    'parent' => null,
])

<div
        {{ $attributes->class(['relative w-full mb-4 h-30 md:h-40 lg:w-full lg:h-40 xl:w-80 xl:h-30 xl:w-full  xl:mb-0 border-zinc-200 border-1 rounded-xl shadow-sm overflow-hidden hover:bg-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 flex']) }}>

    <flux:icon.marker-status
        variant="submitted"
        class="absolute top-0 right-0 m-2 opacity-50"
        size="6"
    />



    <!-- Image on the Left -->
    <div class="w-2/5 h-full overflow-hidden relative">
        <img src="https://placehold.co/150x100/png"
             class="w-full h-full object-cover"
             alt="">

            <flux:icon.chat-bubble-bottom-center-text
                class="text-amber-600 shadow-2xl absolute top-2 left-2"/>

    </div>

    <!-- Content on the Right -->
    <div @class(["w-3/5 p-4 flex flex-col justify-between",
                "bg-error/10" => false
                ])>
        <h5 class="truncate text-md font-bold text-gray-900 dark:text-white"
            >
            Report title
        </h5>

        <p class="text-sm text-gray-700 dark:text-gray-400 line-clamp-3">
            Lorem ipsum report description
        </p>
        <div class="absolute bottom-2 left-2">
            <flux:badge size="2" class="!text-xs !bg-zinc-200 dark:!bg-zinc-700 opacity-75" >2 days ago</flux:badge>
        </div>


    </div>
</div>
