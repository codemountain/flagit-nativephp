@props([
    'variant' => 'location',
    'size' =>   5,
])

@php
    $variant = strtolower($variant);
    $classes = Flux::classes('shrink-0')
        ->add(match($variant) {
            'location' => '[:where(&)]:size-'.$size,
            'areas' => '[:where(&)]:size-'.$size,
            'path' => '[:where(&)]:size-'.$size,
            'site' => '[:where(&)]:size-'.$size,
        });
@endphp

<?php switch ($variant): case ('location'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon width="1200pt" height="1200pt" version="1.1" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
    <path d="m932.68 167.53c-88.875-88.594-207.1-137.53-332.81-137.53-259.87 0-471.1 211.45-471.1 471.1 0 97.359 29.625 190.87 85.5 270.47 0.75 1.0312 1.5469 2.0625 2.2969 3.0938l270.98 341.53c27.047 33.984 67.5 53.578 111 53.812h0.23438c43.266 0 83.719-19.312 111-53.297l273.52-341.53c0.75-1.0312 1.5469-2.0625 2.2969-3.0938 56.156-79.828 85.781-173.86 85.5-271.74-0.375-125.95-49.594-244.22-138.42-332.81zm-51.516 528.79-272.26 339.98c-3.3281 4.125-7.4531 4.875-10.312 4.875-2.8125 0-7.2188-0.75-10.312-5.1562l-269.68-339.94c-39.938-57.422-61.031-124.92-61.031-195 0-188.81 153.52-342.32 342.28-342.32 91.453 0 177.47 35.531 241.87 99.938 64.641 64.406 100.17 150.14 100.45 241.6 0.23438 70.594-20.859 138.32-61.031 196.03z"/>
    <path d="m700.82 501.1c0 55.781-45.188 100.97-100.97 100.97-55.734 0-100.97-45.188-100.97-100.97s45.234-100.97 100.97-100.97c55.781 0 100.97 45.188 100.97 100.97"/>
</svg>

    <?php break; ?>
<?php case ('areas'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 1200 1200" width="1200pt" height="1200pt">
<path d="m1026 480c4.7734 0 9.3516-1.8945 12.727-5.2734 3.3789-3.375 5.2734-7.9531 5.2734-12.727v-288c0-4.7734-1.8945-9.3516-5.2734-12.727-3.375-3.3789-7.9531-5.2734-12.727-5.2734h-288c-4.7734 0-9.3516 1.8945-12.727 5.2734-3.3789 3.375-5.2734 7.9531-5.2734 12.727v264h-240v-264c0-4.7734-1.8945-9.3516-5.2734-12.727-3.375-3.3789-7.9531-5.2734-12.727-5.2734h-288c-4.7734 0-9.3516 1.8945-12.727 5.2734-3.3789 3.375-5.2734 7.9531-5.2734 12.727v288c0 4.7734 1.8945 9.3516 5.2734 12.727 3.375 3.3789 7.9531 5.2734 12.727 5.2734h264v240h-264c-4.7734 0-9.3516 1.8945-12.727 5.2734-3.3789 3.375-5.2734 7.9531-5.2734 12.727v288c0 4.7734 1.8945 9.3516 5.2734 12.727 3.375 3.3789 7.9531 5.2734 12.727 5.2734h288c4.7734 0 9.3516-1.8945 12.727-5.2734 3.3789-3.375 5.2734-7.9531 5.2734-12.727v-264h240v264c0 4.7734 1.8945 9.3516 5.2734 12.727 3.375 3.3789 7.9531 5.2734 12.727 5.2734h288c4.7734 0 9.3516-1.8945 12.727-5.2734 3.3789-3.375 5.2734-7.9531 5.2734-12.727v-288c0-4.7734-1.8945-9.3516-5.2734-12.727-3.375-3.3789-7.9531-5.2734-12.727-5.2734h-264v-240zm-582 528h-252v-252h250.68l1.3203 1.3203zm0-565.32-1.3203 1.3203h-250.68v-252h252zm282 282-1.3203 1.3203h-249.36l-1.3203-1.3203v-249.36l1.3203-1.3203h249.36l1.3203 1.3203zm282 31.32v252h-252v-250.68l1.3203-1.3203zm-250.68-312-1.3203-1.3203v-250.68h252v252z"/>
</svg>

<?php break; ?>

<?php case ('site'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 1200 1200" width="1200pt" height="1200pt">
    <path transform="scale(8.3333)" d="m26.89 37.505 81.439-21.471" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="8"/>
    <path transform="scale(8.3333)" d="m108.33 17.158 18.047 109.14" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="8"/>
    <path transform="scale(8.3333)" d="m126.38 127.39-100.61-21.406" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="8"/>
    <path transform="scale(8.3333)" d="m24.311 105.4 2.9062-66.921" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="8"/>
    <path transform="scale(8.3333)" d="m110.2 109.04h27.84v26.674h-27.84z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
    <path transform="scale(8.3333)" d="m115.97 113.89h17.306v16.052h-17.306z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
    <path transform="scale(8.3333)" d="m94.409 3.8208h27.84v26.674h-27.84z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
    <path transform="scale(8.3333)" d="m100.18 8.6752h17.306v16.052h-17.306z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
    <path transform="scale(8.3333)" d="m13.297 92.061h27.84v26.674h-27.84z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
    <path transform="scale(8.3333)" d="m19.065 96.915h17.306v16.052h-17.306z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
    <path transform="scale(8.3333)" d="m12.97 25.139h27.84v26.674h-27.84z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
    <path transform="scale(8.3333)" d="m18.738 29.994h17.306v16.052h-17.306z" stroke="currentColor" stroke-linejoin="bevel" stroke-miterlimit="10" stroke-width="2"/>
</svg>


    <?php break; ?>

<?php case ('path'): ?>
    <svg {{ $attributes->class($classes) }} width="1200pt" height="1200pt" version="1.1" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
        <path transform="scale(12)" d="m72 74.2 17.5-22.9" fill="currentColor" stroke="currentColor" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="7"/>
        <path transform="scale(12)" d="m27 64.8 31 22.2" fill="currentColor" stroke="currentColor" stroke-miterlimit="10" stroke-width="7"/>
        <path transform="scale(12)" d="m52.5 24.2-27.7 27.1" fill="currentColor" stroke="currentColor" stroke-miterlimit="10" stroke-width="7"/>
        <path d="m854.4 58.801v272.4h-272.4v-272.4zm-62.398 62.398h-146.4v146.4l146.4 0.003906z" fill="currentColor"/>
        <path d="m360 568.8v272.4h-272.4v-272.4zm-63.602 62.398h-145.2v146.4h146.4l0.003906-146.4z" fill="currentColor"/>
        <path d="m927.6 853.2v272.4l-272.4 0.003906v-272.4zm-63.602 63.602h-145.2v146.4h145.2z" fill="currentColor"/>
    </svg>


    <?php break; ?>


<?php endswitch; ?>
