@props([
    'variant' => 'submitted',
    'size' =>   5,
])

@php
    $variant = strtolower($variant);
    //get enums reportstatuses ans reportstatusessimple , loop and set classes below
    $classes = Flux::classes('shrink-0')
        ->add(match($variant) {
            'draft' => '[:where(&)]:size-'.$size,
            'submitted' => '[:where(&)]:size-'.$size,
            'validated' => '[:where(&)]:size-'.$size,
            'scheduled' => '[:where(&)]:size-'.$size,
            'in_progress' => '[:where(&)]:size-'.$size,
            'suspended' => '[:where(&)]:size-'.$size,
            'done' => '[:where(&)]:size-'.$size,
            'cancelled' => '[:where(&)]:size-'.$size,
            'assigned' => '[:where(&)]:size-'.$size,
            'hold' => '[:where(&)]:size-'.$size,
            'linked' => '[:where(&)]:size-'.$size,
            'submitted_urgent' => '[:where(&)]:size-'.$size,
            'validated_urgent' => '[:where(&)]:size-'.$size,
            'marker' => '[:where(&)]:size-'.$size,
            default => '[:where(&)]:size-'.$size
        });

@endphp

<?php switch ($variant): case ('draft'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #b7b7b7;"/>
    <g>
        <path d="M32,29.8h7.1v67.4h-7.1V29.8Z" style="fill: #fff;"/>
        <path d="M92.2,33.5c-2.5,4.2-7.1,7-12.3,7s-9.9-2.8-12.4-7.1c-2.5-4.2-7.1-7.1-12.4-7.1s-9.9,2.8-12.4,7.1h0v35.4c2.5-4.2,7.1-7,12.4-7s9.9,2.8,12.4,7.1c2.5,4.2,7.1,7.1,12.4,7.1s9.9-2.8,12.4-7.1h0v-35.3Z" style="fill: #fff;"/>
    </g>
</svg>
    <?php break; ?>
<?php case ('submitted'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #f26e36;"/>
    <g>
        <path d="M32,29.8h7.1v67.4h-7.1V29.8Z" style="fill: #fff;"/>
        <path d="M92.2,33.5c-2.5,4.2-7.1,7-12.3,7s-9.9-2.8-12.4-7.1c-2.5-4.2-7.1-7.1-12.4-7.1s-9.9,2.8-12.4,7.1h0v35.4c2.5-4.2,7.1-7,12.4-7s9.9,2.8,12.4,7.1c2.5,4.2,7.1,7.1,12.4,7.1s9.9-2.8,12.4-7.1h0v-35.3Z" style="fill: #fff;"/>
    </g>
</svg>

    <?php break; ?>
    <?php case ('submitted_urgent'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 147.9" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.4,0,61.1s60.5,86.8,60.5,86.8c0,0,60.5-40.4,60.5-86.8S93.9,0,60.5,0h0Z" style="fill: #b73726;"/>
    <g>
        <path d="M32,30.1h7.1v68.1h-7.1V30.1Z" style="fill: #fff;"/>
        <path d="M92.2,33.8c-2.5,4.2-7.1,7-12.3,7s-9.9-2.9-12.4-7.2c-2.5-4.3-7.1-7.2-12.4-7.2s-9.9,2.9-12.4,7.2h0v35.7c2.5-4.2,7.1-7.1,12.4-7.1s9.9,2.9,12.4,7.2c2.5,4.3,7.1,7.2,12.4,7.2s9.9-2.9,12.4-7.2h0v-35.7Z" style="fill: #fff;"/>
    </g>
</svg>

    <?php break; ?>
<?php case ('validated'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #3f8983;"/>
    <g>
        <path d="M62.1,85.9h26.2c2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-2.2-5-5-5-6.8,0-10.1,0c-2.1,0-6.2,0-9.1,0-1.5,0-2.6-1.5-2.1-2.9,1.5-4.4,4.1-12.4,4.5-15,1.2-6.1-8.4-8-10.3-3.2-1.8,4.5-8.8,18.2-17.1,23.3-1,.6-1.6,1.7-1.6,2.8v28c0,1.9,6.6,6.8,16,6.8h0Z" style="fill: #fff;"/>
        <path d="M42.1,51.5v28.2c0,2-1.6,3.7-3.7,3.7h-10.7c-2,0-3.7-1.6-3.7-3.7v-28.2c0-2,1.6-3.7,3.7-3.7h10.7c2,0,3.7,1.6,3.7,3.7Z" style="fill: #fff;"/>
    </g>
</svg>

<?php break; ?>
<?php case ('validated_urgent'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 147.9" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5.8C27.1.8,0,27.8,0,61.2s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9.8,60.5.8h0Z" style="fill: #b73726;"/>
    <g>
        <path d="M62.1,86.6h26.2c2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-2.2-5-5-5-6.8,0-10.1,0c-2.1,0-6.2,0-9.1,0-1.5,0-2.6-1.5-2.1-2.9,1.5-4.4,4.1-12.4,4.5-15,1.2-6.1-8.4-8-10.3-3.2-1.8,4.5-8.8,18.2-17.1,23.3-1,.6-1.6,1.7-1.6,2.8v28c0,1.9,6.6,6.8,16,6.8h0Z" style="fill: #fff;"/>
        <path d="M42.1,52.3v28.2c0,2-1.6,3.7-3.7,3.7h-10.7c-2,0-3.7-1.6-3.7-3.7v-28.2c0-2,1.6-3.7,3.7-3.7h10.7c2,0,3.7,1.6,3.7,3.7Z" style="fill: #fff;"/>
    </g>
</svg>

<?php case ('scheduled'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #3bf9ea;"/>
    <g>
        <path d="M62.1,85.9h26.2c2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-2.2-5-5-5-6.8,0-10.1,0c-2.1,0-6.2,0-9.1,0-1.5,0-2.6-1.5-2.1-2.9,1.5-4.4,4.1-12.4,4.5-15,1.2-6.1-8.4-8-10.3-3.2-1.8,4.5-8.8,18.2-17.1,23.3-1,.6-1.6,1.7-1.6,2.8v28c0,1.9,6.6,6.8,16,6.8h0Z" style="fill: #fff;"/>
        <path d="M42.1,51.5v28.2c0,2-1.6,3.7-3.7,3.7h-10.7c-2,0-3.7-1.6-3.7-3.7v-28.2c0-2,1.6-3.7,3.7-3.7h10.7c2,0,3.7,1.6,3.7,3.7Z" style="fill: #fff;"/>
    </g>
</svg>
    <?php break; ?>
<?php case ('ongoing'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #568dad;"/>
    <g>
        <path d="M62.1,85.9h26.2c2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-1.6-4.2-3.8-4.8c0,0,0-.2,0-.2,2.8,0,5-2.2,5-5s-2.2-5-5-5-6.8,0-10.1,0c-2.1,0-6.2,0-9.1,0-1.5,0-2.6-1.5-2.1-2.9,1.5-4.4,4.1-12.4,4.5-15,1.2-6.1-8.4-8-10.3-3.2-1.8,4.5-8.8,18.2-17.1,23.3-1,.6-1.6,1.7-1.6,2.8v28c0,1.9,6.6,6.8,16,6.8h0Z" style="fill: #fff;"/>
        <path d="M42.1,51.5v28.2c0,2-1.6,3.7-3.7,3.7h-10.7c-2,0-3.7-1.6-3.7-3.7v-28.2c0-2,1.6-3.7,3.7-3.7h10.7c2,0,3.7,1.6,3.7,3.7Z" style="fill: #fff;"/>
    </g>
</svg>
    <?php break; ?>
<?php case ('suspended'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #353535;"/>
    <circle cx="60.5" cy="60.6" r="26.1" style="fill: #fff;"/>
</svg>
    <?php break; ?>
<?php case ('done'): ?>
<?php case ('done_urgent'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #1d6131;"/>
    <polygon points="53.7 67.9 37.9 52.1 25.4 64.6 53.7 92.9 99.6 46.9 87.1 34.4 53.7 67.9" style="fill: #f2f2f2;"/>
</svg>

    <?php break; ?>

<?php case ('cancelled'): ?>
<?php case ('cancelled_urgent'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0Z" style="fill: #959483;"/>
    <path d="M30.6,74.9l17.9-17.9-17.9-17.9,12-12,17.9,17.9,17.9-17.9,12,12-17.9,17.9,17.9,17.9-12,12-17.9-17.9-17.9,17.9-12-12h0Z" style="fill: #fff;"/>
</svg>

    <?php break; ?>

<?php case ('assigned'): ?>
<?php case ('assigned_urgent'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #809fa4;"/>
    <g>
        <path d="M94.1,55.6c-2.1-14.8-13.6-26.2-28.4-28.4v-6.9h-10v6.9c-14.8,2.1-26.2,13.6-28.4,28.4h-7.2v10h6.9c2.1,14.8,13.6,26.2,28.4,28.4v6.9h10v-6.9c14.8-2.1,26.2-13.6,28.4-28.4h6.9v-10h-6.6ZM83.8,65.8c-2.1,9-9,16.3-18.1,18.1v-5.7h-10v5.7c-9-2.1-16.3-9-18.1-18.1h5.7v-10h-6c2.1-9,9-16.3,18.1-18.1v5.7h10v-6c9,2.1,16.3,9,18.1,18.1h-5.7v10h6v.3Z" style="fill: #fff;"/>
        <path d="M60.6,51c-5.4,0-9.7,4.2-9.7,9.7s4.2,9.7,9.7,9.7,9.7-4.2,9.7-9.7-4.2-9.7-9.7-9.7Z" style="fill: #fff;"/>
    </g>
</svg>

    <?php break; ?>

<?php case ('hold'): ?>
<?php case ('hold_urgent'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
    <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0Z" style="fill: #98792c;"/>
    <g>
        <path d="M32.4,27h23.4v59.8h-23.4V27Z" style="fill: #f2f2f2;"/>
        <path d="M65.2,27h23.4v59.8h-23.4V27Z" style="fill: #f2f2f2;"/>
    </g>
</svg>
<?php break; ?>

<?php case ('linked'): ?>
<?php case ('linked_urgent'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 150 181.5" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <style>
            .st50 {
                fill: #f2f2f2;
            }

            .st51 {
                fill: aqua;
            }
        </style>
    </defs>
    <path class="st51" d="M75,0C33.6,0,0,33.6,0,75s75,106.5,75,106.5c0,0,75-49.6,75-106.5S116.4,0,75,0h0Z"/>
    <g>
        <path class="st50" d="M116.7,68.9c-2.6-18.3-16.8-32.5-35.2-35.2v-8.6h-12.3v8.6c-18.3,2.6-32.5,16.8-35.2,35.2h-9v12.3h8.6c2.6,18.3,16.8,32.5,35.2,35.2v8.6h12.3v-8.6c18.3-2.6,32.5-16.8,35.2-35.2h8.6v-12.3h-8.2ZM104,81.6c-2.6,11.2-11.2,20.2-22.4,22.4v-7.1h-12.3v7.1c-11.2-2.6-20.2-11.2-22.4-22.4h7.1v-12.3h-7.5c2.6-11.2,11.2-20.2,22.4-22.4v7.1h12.3v-7.5c11.2,2.6,20.2,11.2,22.4,22.4h-7.1v12.3h7.5v.4Z"/>
        <path class="st50" d="M75.2,63.3c-6.7,0-12,5.2-12,12s5.2,12,12,12,12-5.2,12-12-5.2-12-12-12Z"/>
    </g>
</svg>
    <?php break; ?>
<?php case ('marker'): ?>

<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
        <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #841719;"/>
        <circle cx="60.5" cy="60.6" r="26.1" style="fill: #fff;"/>
    </svg>
    <?php break; ?>
<?php default: ?>
<svg {{ $attributes->class($classes) }} data-flux-icon viewBox="0 0 120.9 146.4" xmlns="http://www.w3.org/2000/svg">
        <path d="M60.5,0C27.1,0,0,27.1,0,60.5s60.5,85.9,60.5,85.9c0,0,60.5-40,60.5-85.9S93.9,0,60.5,0h0Z" style="fill: #841719;"/>
        <circle cx="60.5" cy="60.6" r="26.1" style="fill: #fff;"/>
    </svg>
<?php break; ?>
<?php endswitch; ?>
