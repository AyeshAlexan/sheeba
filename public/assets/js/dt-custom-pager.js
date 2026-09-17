/*
 * Shared custom pagination bar for DataTables — renders a single rounded
 * pill (prev/next arrows, numbered pages, page-size dropdown, "Go to
 * page" input) instead of DataTables' own pagination footer, which was
 * prone to overflowing its container on narrower cards and forcing a
 * horizontal scrollbar.
 *
 * Usage (after `var table = $('#id').DataTable({...});`):
 *     DTCustomPager.init(table, '#myPagerContainer');
 */
(function (root, $) {
    function renderPager(table, $container) {
        var info = table.page.info();
        var currentPage = info.page;   // 0-based
        var totalPages = info.pages;
        var pageLen = info.length;

        if (totalPages <= 0) {
            $container.empty();
            return;
        }

        function pageBtn(i) {
            var active = i === currentPage ? 'active' : '';
            return '<button type="button" class="dt-pg-num ' + active + '" data-page="' + i + '">' + (i + 1) + '</button>';
        }

        var nums = [];
        var maxShown = 5;
        if (totalPages <= maxShown + 2) {
            for (var i = 0; i < totalPages; i++) nums.push(pageBtn(i));
        } else {
            var start = Math.max(0, currentPage - 2);
            var end = Math.min(totalPages - 1, start + 4);
            start = Math.max(0, end - 4);

            if (start > 0) {
                nums.push(pageBtn(0));
                if (start > 1) nums.push('<span class="dt-pg-ellipsis">…</span>');
            }
            for (var p = start; p <= end; p++) nums.push(pageBtn(p));
            if (end < totalPages - 1) {
                if (end < totalPages - 2) nums.push('<span class="dt-pg-ellipsis">…</span>');
                nums.push(pageBtn(totalPages - 1));
            }
        }

        var lenOptions = [10, 15, 25, 50, 100].map(function (n) {
            return '<option value="' + n + '"' + (n === pageLen ? ' selected' : '') + '>' + n + ' / page</option>';
        }).join('');

        var html = ''
            + '<div class="dt-custom-pager-row">'
            + '  <div class="dt-custom-pager">'
            + '    <button type="button" class="dt-pg-arrow dt-pg-prev" ' + (currentPage === 0 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>'
            + nums.join('')
            + '    <button type="button" class="dt-pg-arrow dt-pg-next" ' + (currentPage >= totalPages - 1 ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>'
            + '    <span class="dt-pg-sep"></span>'
            + '    <select class="dt-pg-len">' + lenOptions + '</select>'
            + '    <span class="dt-pg-sep"></span>'
            + '    <span class="dt-pg-goto">Go to <input type="number" min="1" max="' + totalPages + '" class="dt-pg-goto-input"> Page</span>'
            + '  </div>'
            + '</div>';

        $container.html(html);

        $container.find('.dt-pg-prev').on('click', function () {
            table.page('previous').draw('page');
        });
        $container.find('.dt-pg-next').on('click', function () {
            table.page('next').draw('page');
        });
        $container.find('.dt-pg-num').on('click', function () {
            table.page(parseInt($(this).data('page'), 10)).draw('page');
        });
        $container.find('.dt-pg-len').on('change', function () {
            table.page.len(parseInt($(this).val(), 10)).draw();
        });
        $container.find('.dt-pg-goto-input').on('keydown', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                var val = parseInt($(this).val(), 10);
                if (val >= 1 && val <= totalPages) {
                    table.page(val - 1).draw('page');
                }
                $(this).val('');
            }
        });
    }

    root.DTCustomPager = {
        init: function (table, containerSelector) {
            var $container = $(containerSelector);
            renderPager(table, $container);
            table.on('draw', function () {
                renderPager(table, $container);
            });
        }
    };
})(window, jQuery);
