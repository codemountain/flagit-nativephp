<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />


<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@fluxAppearance
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
<style>
    @font-face {
        font-family: 'URWDIN';
        font-style:normal;
        font-weight: 200;
        font-display: swap;
        src: local('URWDINSemiCond-Thin'), url({{asset('fonts/URWDINSemiCond-Thin.otf')}}) format('opentype');
    }
    @font-face {
        font-family: 'URWDIN';
        font-style:italic;
        font-weight: 200;
        font-display: swap;
        src: local('URWDINSemiCond-Thinitalic'), url({{asset('fonts/URWDINSemiCond-Thinitalic.otf')}}) format('opentype');
    }
    @font-face {
        font-family: 'URWDIN';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: local('URWDINSemiCond-Regular'), url({{asset('fonts/URWDIN-Regular.otf')}}) format('opentype');
    }
    @font-face {
        font-family: 'URWDIN';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: local('URWDINSemiCond-Light'), url({{asset('fonts/URWDINSemiCond-Light.otf')}}) format('opentype');
    }
    @font-face {
        font-family: 'URWDIN';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: local('URWDINSemiCond-Demi'), url({{asset('fonts/URWDINSemiCond-Demi.otf')}}) format('opentype');
    }
    @font-face {
        font-family: 'URWDIN';
        font-style: italic;
        font-weight: 400;
        font-display: swap;
        src: local('URWDINSemiCond-Italic'), url({{asset('fonts/URWDINSemiCond-Italic.otf')}}) format('opentype');
    }
    @font-face {
        font-family: 'URWDIN';
        font-style: italic;
        font-weight: 700;
        font-display: swap;
        src: local('URWDINSemiCond-Demiitalic'), url({{asset('fonts/URWDINSemiCond-Demiitalic.otf')}}) format('opentype');
    }

</style>


