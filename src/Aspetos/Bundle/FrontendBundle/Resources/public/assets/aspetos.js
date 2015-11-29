
var Aspetos = function () {

    return {
        initListFilterAndAjaxLoading: function (containerSelector, itemSelector, filterSelector, url) {
            var $filter = $(filterSelector);
            var $container = $(containerSelector);
            var sortingEnabled = false;
            var getSortData = {};
            var sortDirections = {};
            var lastSortAscending = '';

            if($filter.find('select.sort').length == 1) {
                sortingEnabled = true;
                $filter.find('select.sort option').each(function () {
                    getSortData[$(this).val()] = $(this).data('isotope-selector');
                    sortDirections[$(this).val()] = $(this).data('isotope-sort-ascending');
                });

                lastSortAscending = sortDirections[$filter.find('select.sort').val()];
            }

            var filterIsotope = function() {
                checkSortDirection();

                allLoaded = false;
                var selectOptionClasses = '';
                $filter.find('select:not(.sort)').each(function(){
                    var selectedOptions = $(this).val();
                    if(selectedOptions != null) {
                        var name = $(this).attr('name').replace('[]', '');
                        if(selectOptionClasses != '') {
                            selectOptionClasses += ',';
                        }
                        selectOptionClasses += '.' + name +'-' + selectedOptions.join(', .' + name + '-');
                    }
                });
                if(selectOptionClasses == '') {
                    selectOptionClasses = '*';
                }

                var options = {
                    filter: selectOptionClasses
                }

                if(sortingEnabled) {
                    options.sortBy = $filter.find('select.sort').val();
                    options.sortAscending = sortDirections[options.sortBy];
                }

                $container.isotope(options);
            };

            var matchItemHeight = function(){
                window.setTimeout(function() {
                    $container.find(itemSelector + ':visible').matchHeight();
                }, 500);

            };

            var checkSortDirection = function() {
                if(sortingEnabled) {
                    var sortAscending = sortDirections[$filter.find('select.sort').val()];
                    if (sortAscending != lastSortAscending) {
                        $container.isotope('remove', $container.isotope('getItemElements'));
                    }
                    lastSortAscending = sortAscending;
                }
            }

            var onScroll = function() {
                checkSortDirection();

                if(allLoaded == false && currentlyLoading == false && $('.scroll-visibility-helper').visible()) {
                    currentlyLoading = true;
                    var data = {};

                    $filter.find('select').each(function(){
                        var $this = $(this);
                        var values = $this.val();
                        if (values != null) {
                            data[$this.attr('name')] = values;
                        }
                    });

                    data.exclude = $container.find(itemSelector).map(function() {
                        return $(this).data('id');
                    }).get();

                    $.post(
                        url,
                        data,
                        function(response){
                            resetAfterLayout = true;
                            response = response.trim();
                            if(response == '') {
                                allLoaded = true;
                            }
                            else {
                                $container.isotope('insert', $(response), true);
                                filterIsotope();

                                $container.imagesLoaded( function() {
                                    //$container.find(itemSelector).matchHeight();
                                    $container.isotope('layout');
                                });
                            }
                        },
                        'html'
                    );
                }
            };

            var currentlyLoading = false;
            var resetAfterLayout = false;
            var allLoaded = false;
            $(document).bind('scroll.ajax-infinity-scrolling', onScroll);

            $container.isotope({
                itemSelector:   itemSelector,
                layoutMode:     'fitRows',
                getSortData:    getSortData
            });
            $container.imagesLoaded( function() {
                $container.isotope('layout');
            });

            $container.on('layoutComplete', function(){
                if(resetAfterLayout == true && currentlyLoading == true) {
                    resetAfterLayout = false;
                    currentlyLoading = false;
                }
                matchItemHeight();
                // timeout needed because container height is not adjusted at the moment the layoutComplete event is fired
                window.setTimeout(function() {
                    $(document).trigger('scroll.ajax-infinity-scrolling');
                }, 100);
            });

            var multiselectOptions = {
                selectAllText: translations.bootstrapMultiselect.selectAllText,
                filterPlaceholder: translations.bootstrapMultiselect.filterPlaceholder,
                nonSelectedText: translations.bootstrapMultiselect.nonSelectedText,
                nSelectedText: translations.bootstrapMultiselect.nSelectedText,
                allSelectedText: translations.bootstrapMultiselect.allSelectedText,
                enableClickableOptGroups: true,
                enableFiltering: true,
                maxHeight: 400,
                buttonWidth: '100%',
                includeSelectAllOption: true,
                onChange: filterIsotope,
                enableCaseInsensitiveFiltering: true
            };

            $filter.find('select:not(.sort)').multiselect(multiselectOptions);
            multiselectOptions.enableFiltering = false;
            multiselectOptions.enableCaseInsensitiveFiltering = false;
            $filter.find('select.sort').multiselect(multiselectOptions);

            filterIsotope();
        },
        initWordpress: function() {
            $('.wp-post .attachment a').fancybox();
            $('.wp-post a[data-lightbox="on"]').fancybox();
        },
        initTabs: function() {
            // we use hashes prefixed with an underscore, to prevent the browser default scrolling to the element on hash change

            var hash = window.location.hash.replace('#_', '#');
            // show tab that matches the given hash
            if (hash != '') {
                $('.nav.tablist a[href=' + hash + ']').tab('show') ;
            }

            // Change hash for page-reload
            $('.nav.tablist a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash.replace('#', '#_');
            })
        },
        initSlick: function() {
            $('.slick-container').slick({
                infinite:       true,
                slidesToShow:   3,
                slidesToScroll: 3
            });
        }
    };
}();