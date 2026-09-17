{{--
    Cube loader

    Usage:
        <x-cube-loader />
        <x-cube-loader size="48" color="#2D7FF9" duration="1.8s" />
        <x-cube-loader class="my-8" />

    Background is transparent. The stroke defaults to currentColor,
    so it takes the text colour of whatever wraps it.
--}}

@props([
    'size'     => 96,              {{-- px --}}
    'color'    => 'currentColor',  {{-- any CSS colour --}}
    'duration' => '2.4s',          {{-- one full draw + clear cycle --}}
    'label'    => 'Loading',
])

@once
<style>
    .cube-loader{
        width: var(--cube-size, 96px);
        height: var(--cube-size, 96px);
        color: var(--cube-color, currentColor);
        display: block;
        overflow: visible;
        background: none;
        animation: cube-cycle var(--cube-dur, 2.4s) linear infinite;
    }
    .cube-loader path{
        fill: none;
        stroke: currentColor;
        stroke-width: 1.6;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 1;
        stroke-dashoffset: 1;
        animation-duration: var(--cube-dur, 2.4s);
        animation-iteration-count: infinite;
        animation-timing-function: cubic-bezier(.65,.05,.36,1);
        animation-fill-mode: backwards;
    }
    .cube-loader .cube-shell{ animation-name: cube-draw-shell }
    .cube-loader .cube-fold { animation-name: cube-draw-fold }
    .cube-loader .cube-stem { animation-name: cube-draw-stem }

    @keyframes cube-draw-shell{ 0%{stroke-dashoffset:1} 38%,100%{stroke-dashoffset:0} }
    @keyframes cube-draw-fold { 0%,34%{stroke-dashoffset:1} 58%,100%{stroke-dashoffset:0} }
    @keyframes cube-draw-stem { 0%,54%{stroke-dashoffset:1} 72%,100%{stroke-dashoffset:0} }
    @keyframes cube-cycle     { 0%,84%{opacity:1} 97%,100%{opacity:0} }

    @media (prefers-reduced-motion: reduce){
        .cube-loader, .cube-loader path{ animation: none }
        .cube-loader path{ stroke-dashoffset: 0 }
    }
</style>
@endonce

<svg
    {{ $attributes->merge(['class' => 'cube-loader']) }}
    style="--cube-size: {{ is_numeric($size) ? $size . 'px' : $size }}; --cube-color: {{ $color }}; --cube-dur: {{ $duration }};"
    viewBox="0 0 24 24"
    xmlns="http://www.w3.org/2000/svg"
    role="img"
    aria-label="{{ $label }}"
>
    <path class="cube-shell" pathLength="1" d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
    <path class="cube-fold" pathLength="1" d="m3.3 7 8.7 5 8.7-5"/>
    <path class="cube-stem" pathLength="1" d="M12 22V12"/>
</svg>
