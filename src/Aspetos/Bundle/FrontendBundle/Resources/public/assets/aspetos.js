var Aspetos = function () {

    return {
        initListFilterAndAjaxLoading: function (containerSelector, itemSelector, paginationSelector, filterSelector, spinnerSrc) {
            var $filter = $(filterSelector);
            var $container = $(containerSelector);

            var ias = jQuery.ias({
                container:  containerSelector,
                item:       itemSelector,
                pagination: paginationSelector,
                next:       '.next'
            });

            ias.extension(new IASSpinnerExtension({
                src:    spinnerSrc, // optionally
                html:   '<div class="col-md-12 text-center ias-spinner"><div class=""><img src="{src}"/></div></div>'
            }));

            ias.on('load', function(event) {
                $spinner = $('.ias-spinner');
                $lastItem = $container.find(itemSelector + ':last');
                $spinner.css('top', $lastItem.position().top + $lastItem.outerHeight());

                var filter = $filter.val();
                if(filter == null) {
                    filter = '';
                } else {
                    filter = filter.join(',');
                }

                var ids = $container.find(itemSelector).map(function() {
                    return $(this).data('id');
                }).get();

                event.url += '?filter=' + filter + '&ids=' + ids.join(',');
            });

            ias.on('render', function(items) {
                $('.ias-spinner').remove();
                $container.isotope('insert', items, true);
                $container.find(itemSelector).matchHeight();
                ias.fire('rendered', items); //fire rendered event manually, because pagination is handled in rendered event

                return false; //prevent default rendering
            });

            $container.find(itemSelector).matchHeight();

            $container.isotope({
                itemSelector:   itemSelector,
                layoutMode:     'fitRows'
            });

            $filter.multiselect({
                selectAllText:              translations.bootstrapMultiselect.selectAllText,
                filterPlaceholder:          translations.bootstrapMultiselect.filterPlaceholder,
                nonSelectedText:            translations.bootstrapMultiselect.nonSelectedText,
                nSelectedText:              translations.bootstrapMultiselect.nSelectedText,
                allSelectedText:            translations.bootstrapMultiselect.allSelectedText,
                enableClickableOptGroups:   true,
                enableFiltering:            true,
                maxHeight:                  400,
                buttonWidth:                350,
                includeSelectAllOption:     true,
                onChange:                   function() {
                    var selectedOptions = $filter.val();
                    var selectOptionClasses = '*';
                    if(selectedOptions != null) {
                        selectOptionClasses = '.filter-' + selectedOptions.join(', .filter-');
                    }

                    $container.isotope({ filter: selectOptionClasses });
                    window.setTimeout(function() {
                        ias.reinitialize();
                    }, 500);
                }
            });
        }
    };
}();