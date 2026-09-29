/* Builds the pill-style "Go to Page" bar under every report's DataTable,
   re-using the real length/pagination controls so DataTables behaviour is untouched. */
(function ($) {
    if (!$ || !$.fn) return;

    $(document).on('init.dt', function (e, settings) {
        if (e.namespace !== 'dt') return;

        var api = new $.fn.dataTable.Api(settings);
        var $wrapper = $(api.table().container());

        if ($wrapper.find('.rp-pagebar').length) return;

        var $length = $wrapper.find('.dataTables_length');
        var $paginate = $wrapper.find('.dataTables_paginate');

        if (!$paginate.length) return;

        var $bar = $('<div class="rp-pagebar"></div>');

        var $pagesGroup = $('<div class="rp-pagebar-group rp-pagebar-pages"></div>');
        $pagesGroup.append($paginate.detach());
        $bar.append($pagesGroup);

        var hasLength = $length.length > 0;
        if (hasLength) {
            var $select = $length.find('select').detach();
            var $sizeGroup = $('<div class="rp-pagebar-group rp-pagebar-size"></div>');
            $sizeGroup.append($select);
            $sizeGroup.append('<span class="rp-pagebar-label">/ page</span>');
            $length.remove();

            $bar.append('<span class="rp-pagebar-divider"></span>');
            $bar.append($sizeGroup);
        }

        var $goGroup = $('<div class="rp-pagebar-group rp-pagebar-goto"></div>');
        $goGroup.append('<span class="rp-pagebar-label">Go to</span>');
        var $goInput = $('<input type="number" min="1" class="rp-pagebar-input" aria-label="Go to page">');
        $goGroup.append($goInput);
        $goGroup.append('<span class="rp-pagebar-label">Page</span>');

        $bar.append('<span class="rp-pagebar-divider"></span>');
        $bar.append($goGroup);

        function jumpToPage() {
            var pageCount = api.page.info().pages;
            var target = parseInt($goInput.val(), 10);
            if (!target || target < 1) target = 1;
            if (target > pageCount) target = pageCount;
            api.page(target - 1).draw('page');
            $goInput.val('');
        }

        $goInput.on('keydown', function (ev) {
            if (ev.key === 'Enter') {
                ev.preventDefault();
                jumpToPage();
            }
        });

        $wrapper.append($bar);
    });
})(window.jQuery);
