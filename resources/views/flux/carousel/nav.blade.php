@blaze

@props([
    'action' => 'next', // next, prev, first, last, goto:N
    'icon' => null,
    'variant' => 'outline',
    'size' => 'sm',
])

@php
// Parse goto action if present
$gotoIndex = null;
if (str_starts_with($action, 'goto:')) {
    $gotoIndex = (int) substr($action, 5);
    $action = 'goto';
}

// Default icons based on action
$defaultIcons = [
    'next' => 'chevron-right',
    'prev' => 'chevron-left',
    'first' => 'chevron-double-left',
    'last' => 'chevron-double-right',
];

$icon = $icon ?? ($defaultIcons[$action] ?? null);

// Build click handler
$clickHandler = match($action) {
    'next' => 'next()',
    'prev' => 'prev()',
    'first' => 'goToFirst()',
    'last' => 'goToLast()',
    'goto' => "goTo($gotoIndex)",
    default => 'next()',
};

// Build disabled condition
$disabledCondition = match($action) {
    'next' => '!canGoNext()',
    'prev' => '!canGoPrev()',
    'first' => '!canGoPrev()',
    'last' => '!canGoNext()',
    default => 'false',
};

// Build aria label
$ariaLabel = match($action) {
    'next' => 'Next slide',
    'prev' => 'Previous slide',
    'first' => 'First slide',
    'last' => 'Last slide',
    'goto' => "Go to slide $gotoIndex",
    default => 'Navigate',
};

// Build attributes
$buttonAttributes = [
    'variant' => $variant,
    'size' => $size,
    'x-on:click' => $clickHandler,
    'x-bind:disabled' => $disabledCondition,
    'aria-label' => $ariaLabel,
    'data-flux-carousel-nav' => true,
    'data-carousel-action' => $action,
];

if ($icon) {
    $buttonAttributes['icon'] = $icon;
}
@endphp

<flux:button {{ $attributes->merge($buttonAttributes) }}>
    {{ $slot }}
</flux:button>
