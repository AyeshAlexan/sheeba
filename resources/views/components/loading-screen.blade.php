{{--
    Full-screen loading overlay, shown while a page's assets/layout settle,
    then fades out to reveal the page underneath.

    Usage: place right after the opening <body> tag.
        <x-loading-screen />
        <x-loading-screen label="Loading dashboard..." />
--}}

@props([
    'label' => 'Loading',
    'size'  => 120,
])

{{--
    Styled inline (not from theme-redesign.css) so the overlay is
    guaranteed to render full-screen from the very first paint —
    it can't be caught unstyled by a race against the external
    stylesheet still loading, which would make it flash by as a
    small inline icon instead of a full-screen cover.
--}}
<style>
    #pageLoader.page-loader {
        position: fixed !important;
        inset: 0 !important;
        z-index: 99999 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 16px !important;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        background: #F6F8FC !important;
        opacity: 1;
        transition: opacity 0.5s ease;
    }
    #pageLoader .page-loader-label {
        margin: 0 !important;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        color: #667085 !important;
        letter-spacing: 0.02em !important;
    }
    #pageLoader.page-loader-hide {
        opacity: 0 !important;
        pointer-events: none !important;
    }
</style>

<div id="pageLoader" class="page-loader">
    <x-cube-loader :size="$size" color="#1677FF" duration="1.5s" />
    <p class="page-loader-label">{{ $label }}</p>
</div>

<script>
    (function () {
        var loader = document.getElementById('pageLoader');
        if (!loader) return;

        // One full draw-and-clear cycle of the cube animation is 1.5s
        // (see cube-loader's duration prop above) — wait for at least
        // one full cycle so it never cuts off mid-draw.
        var minDelay = 1500;
        var start = Date.now();

        function hideLoader() {
            var wait = Math.max(0, minDelay - (Date.now() - start));
            setTimeout(function () {
                loader.classList.add('page-loader-hide');
                setTimeout(function () {
                    loader.remove();
                }, 500);
            }, wait);
        }

        if (document.readyState === 'complete') {
            hideLoader();
        } else {
            window.addEventListener('load', hideLoader);
        }
    })();
</script>
