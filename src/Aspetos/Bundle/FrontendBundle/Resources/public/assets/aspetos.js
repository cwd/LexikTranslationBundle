var Aspetos = function () {

    return {
        initListFilterAndAjaxLoading: function (containerSelector, itemSelector, filterSelector, url) {
            var $filter = $(filterSelector);
            var $container = $(containerSelector);

            var filterIsotope = function() {
                allLoaded = false;
                var selectOptionClasses = '';
                $filter.find('select').each(function(){
                    console.log($(this));
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
                console.log(selectOptionClasses);
                $container.isotope({ filter: selectOptionClasses });
            };

            var matchItemHeight = function(){
                window.setTimeout(function() {
                    $container.find(itemSelector + ':visible').matchHeight();
                }, 500);

            };

            var onScroll = function() {
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
                            response = response.trim();
                            if(response == '') {
                                allLoaded = true;
                            }
                            else {
                                $container.isotope('insert', $(response), true);

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
            var allLoaded = false;
            $(document).bind('scroll.ajax-infinity-scrolling', onScroll);

            $container.isotope({
                itemSelector:   itemSelector,
                layoutMode:     'fitRows'
            });
            $container.imagesLoaded( function() {
                //matchItemHeight();
                $container.isotope('layout');
            });

            $container.on('layoutComplete', function(){
                currentlyLoading = false;
                $(document).trigger('scroll.ajax-infinity-scrolling');
                matchItemHeight();
            });

            $filter.find('select').multiselect({
                selectAllText:              translations.bootstrapMultiselect.selectAllText,
                filterPlaceholder:          translations.bootstrapMultiselect.filterPlaceholder,
                nonSelectedText:            translations.bootstrapMultiselect.nonSelectedText,
                nSelectedText:              translations.bootstrapMultiselect.nSelectedText,
                allSelectedText:            translations.bootstrapMultiselect.allSelectedText,
                enableClickableOptGroups:   true,
                enableFiltering:            true,
                maxHeight:                  400,
                buttonWidth:                '100%',
                includeSelectAllOption:     true,
                onChange:                   filterIsotope
            });

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
        }
    };
}();