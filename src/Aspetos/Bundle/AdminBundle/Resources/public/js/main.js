function drawChart(parentPanel) {
    if ($(parentPanel).find('.chart').size() != 1) {
        return;
    }

    var $chart = $(parentPanel).find('.chart');
    var options = {

        lines: {
            show: true,
            lineWidth: 2,
            fill: true,
            fillColor: {
                colors: [{
                    opacity: 0.05
                }, {
                    opacity: 0.03
                }, {
                    opacity: 0.03
                }, {
                    opacity: 0.03
                }]
            }
        },
        points: {
            show: true,
            radius: 3,
            lineWidth: 1
        },
        shadowSize: 2,
        grid: {
            hoverable: true,
            clickable: true,
            tickColor: "#eee",
            borderColor: "#eee",
            borderWidth: 1
        },
        //colors: ["#d12610", "#37b7f3", "#52e136"],
        yaxis: {
            tickColor: "#eee"
        }
    };

    var plot = $.plot($chart, [], options);

    function plotAccordingToChoices() {
        var data = [];

        var interval = $(parentPanel).find('input[type="radio"]:checked', $('.intervalToggler')).val();
        switch (interval) {
            case 'day':
                var timeformat = '%d.%m.%Y';
                break;
            case 'quarter':
                var timeformat = 'Q%q %Y';
                break;
            default:
            case 'month':
                var timeformat = '%b %Y';
                interval = 'month';
                break;
        }
        options['xaxis'] = { mode: 'time', 'minTickSize': [1, interval], timeformat: timeformat, monthNames: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"] };


        $(parentPanel).find('input[type="checkbox"]', $('.columnToggler')).each(function (i) {
            if ($(this).is(':checked')) {

                var box = $(this);
                var dataurl = box.data("path") + '?groupby=' + interval;

                function onDataReceived(series) {
                    data.push(series);
                    $.plot($chart, data, options);
                }

                $.ajax({
                    url: dataurl,
                    type: "GET",
                    dataType: "json",
                    success: onDataReceived
                });
            }
        });
    }
    plotAccordingToChoices();
    $(parentPanel).find('input[type="checkbox"]', $('.columnToggler')).change(plotAccordingToChoices);
    $(parentPanel).find('input[type="radio"]', $('.intervalToggler')).change(plotAccordingToChoices);


    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 15,
            border: '1px solid #333',
            padding: '4px',
            color: '#fff',
            'border-radius': '3px',
            'background-color': '#333',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $chart.bind("plothover", function(event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0],
                    y = item.datapoint[1].toFixed(0);

                var d = new Date();
                d.setTime(x);

                showTooltip(item.pageX, item.pageY, item.series.label + " on " + d.toDateString() + ": <b>" + y + "</b>");
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
}

$(document).ajaxComplete(function(){
    $('input.help-icon').each(function(){
        var helpContent = $(this).data('help');
        var helpTitle = $(this).data('help-title');
        $(this).parents('div.checkbox label').append(' <span class="popover-icon" data-toggle="popover" title="'+ helpTitle.replace(/"/g, "'") +'" data-content="'+ helpContent.replace(/"/g, "'") +'"><i class="fa fa-question"></i></span>');
    });
    $('.popover-icon').popover({
        html:true,

    });
});

function filterOptGroups() {
    $('.form-control.optgroupfilter').each(function () {
        var field = $(this).data('filter-by');
        if (!!field && !!$('.form-control.' + field)) {
            var parentField = $('.form-control.' + field);
            var value = parentField.val();
            $(this).find('optgroup[label=' + value + ']').prop('disabled', false);

            $(this).find('optgroup:not([label=' + value + '])').prop('disabled', true).children().removeAttr("selected");
        }
    });
}

$('.form-control.filter').blur(function() {
    filterOptGroups();
});
filterOptGroups();


function addMediaForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div class="form-group addLink"></div>').append(newForm);

    $newLinkLi.before($newFormLi);

    addMediaFormDeleteLink($newFormLi);

    // handle the removal, just for this example
    $('.remove-tag').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

function addMediaFormDeleteLink($mediaFormLi) {
    var $removeFormA = $('<a class="btn btn-danger" href="#">Delete</a>');
    $mediaFormLi.find('.editArea').html($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $mediaFormLi.remove();
    });
}

$(document).ready(function() {
    // setup an "add a tag" link
    var $addMediaLink = $('<a class="btn btn-primary" href="#">Add</a>');
    var $newLinkLi = $('<div class="form-group addLink"><div class="form-group col-sm-2"></div></div>').append($addMediaLink);

    var $collectionHolder = $('.collection-holder');
    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addMediaLink.on('click', function(e) {
        e.preventDefault();
        addMediaForm($collectionHolder, $newLinkLi);
    });

    $collectionHolder.children(':not(".addLink")').each(function() {
        addMediaFormDeleteLink($(this));
    });

    // Lightbox
    $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
});



