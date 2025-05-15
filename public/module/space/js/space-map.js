window.listenForMapChange = false;
window.activeInfoBoxMarker = "";
jQuery(function ($) {

    let SEARCH_AJAX_REQUEST = null;
    let SEARCH_MAP_AJAX_REQUEST = null;
    let lastLat = null;

    let zoomVal = bravo_map_data.map_zoom_default;

    function showMapLoading(){
        $('.map-loading').removeClass("d-none");
    }

    function hideMapLoading(){
        $('.map-loading').addClass("d-none");
    }

    $(".bravo-filter-price").each(function () {
        var input_price = $(this).find(".filter-price");
        var min = input_price.data("min");
        var max = input_price.data("max");
        var from = input_price.data("from");
        var to = input_price.data("to");
        var symbol = input_price.data("symbol");
        input_price.ionRangeSlider({
            type: "double",
            grid: true,
            min: min,
            max: max,
            from: from,
            to: to,
            prefix: symbol
        });
    });

    function mapChanged(eventType) {
        // console.log("mapchanged", eventType, window.listenForMapChange);
        const mapObj = window.mapEngine.map;
        zoomVal = mapObj.zoom;
        if (window.listenForMapChange) {
            setTimeout(() => {
                lastLat = $('input[name="map_lat"]').val();
                let lat = mapObj.getBounds().getCenter().lat();
                let lng = mapObj.getBounds().getCenter().lng();
                if (lat > 0) {
                    lat = lat.toFixed(5);
                    lng = lng.toFixed(5);
                    if (lat != lastLat) {
                        $('input[name="map_lat"]').val(lat);
                        $('input[name="map_lgn"]').val(lng);
                        reloadForm();
                    }
                }
            }, 3000);
        }
    }

    function resetMapZoomAndPos(markers) {
        window.listenForMapChange = false;
        setTimeout(() => {
            window.listenForMapChange = true;
        }, 3000);
        for (var i = 0; i < window.mapEngine.infoboxs.length; i++) {
            window.mapEngine.infoboxs[i].close();
        }
        $("#bravo_results_map .infoBox").remove();
        mapEngine.clearMarkers();
        if (markers.length > 0) {
            console.log(markers);
            mapEngine.addMarkers2(markers);
            let firstMarker = markers[0];
            mapEngine.map.setCenter({ lat: firstMarker.lat, lng: firstMarker.lng });
            mapEngine.map.setZoom(zoomVal);
        }
    }

    if ($("#bravo_results_map").length > 0) {
        var mapEngine = new BravoMapEngine('bravo_results_map', {
            fitBounds: bookingCore.map_options.map_fit_bounds,
            center: [bravo_map_data.map_lat_default, bravo_map_data.map_lng_default],
            zoom: zoomVal,
            maxZoom: zoomVal,
            fitBounds: false,
            disableScripts: true,
            markerClustering: bookingCore.map_options.map_clustering,
            ready: function (engineMap) {
                if (bravo_map_data.markers) {
                    engineMap.addMarkers2(bravo_map_data.markers);
                }
            }
        });
        window.mapEngine = mapEngine;
        window.mapEngine.map.addListener("center_changed", () => {
            mapChanged("center_changed");
        });
        window.mapEngine.map.addListener("zoom_changed", () => {
            mapChanged("zoom_changed");
        });
    }

    $('.bravo_form_search_map .smart-search #id_label_single').change(function () {
        $(".bravo_form_search_map .smart-searc .child_id").val($(".bravo_form_search_map .smart-search #id_label_single").val());
        reloadForm();
    });

    $('.bravo_form_search_map .g-map-place input[name=map_place]').change(function () {
        // setTimeout(e => {
        //     reloadForm();
        // }, 1500);  
    });

    $('.bravo_form_search_map .input-filter').change(function () {
        reloadForm();
    });

    $('.bravo_form_search_map .btn-filter,.btn-apply-advances').click(function () {
        reloadForm();
    });

    $(document).on("click", "#spaceOrderBySelector a", function () {
        var obj = $(this);
        var val = obj.attr("data-value");
        var txtV = obj.text();
        $("#spaceOrderBy").val(val);
        $("#spaceOrderBySelectorMain span").html(txtV).click();
        reloadForm();
    });

    $('.btn-apply-advances').click(function () {
        $('#advance_filters').addClass('d-none');
    });

    $(document).on("submit", "#topHeadSearch form", function (e) {
        if ($("#spaceSearchQTerm").length > 0) {
            e.preventDefault();
            $("#spaceSearchQTerm").val($("#topHeadSearchInput").val());
            reloadForm();
        }
    });

    function loadFormData(searchType = "main") {
        var orderBY = $("#spaceOrderBy").val();
        var spaceSearchQTerm = encodeURI($("#spaceSearchQTerm").val());

        if (searchType === "main") {
            if (SEARCH_AJAX_REQUEST != null) {
                SEARCH_AJAX_REQUEST.abort();
                SEARCH_AJAX_REQUEST = null;
            }

            $("#space-search-listings").addClass("loading");

            SEARCH_AJAX_REQUEST = $.ajax({
                data: $('.bravo_form_search_map input,select').serialize() + '&userLat=' + window.userLat + '&userLng=' + window.userLng + '&q=' + spaceSearchQTerm + '&orderby=' + orderBY + '&_ajax=1&searchMethod=' + searchType,
                url: window.location.href.split('?')[0],
                dataType: 'json',
                type: 'get',
                success: function (json) {
                    if (json.status) {
                        $("#space-search-listings").removeClass("loading");
                        $('.bravo-list-item-ajax').replaceWith(json.html);
                        $('.listing_items').animate({
                            scrollTop: 0
                        }, 'fast');
                        var base_url = $(location).attr('href');
                        var origin = window.location.origin + "/space";
                        $(".listing_items .item .dropdown-menu a[href^='" + origin + "']")
                            .each(function () {
                                this.href = this.href.replace(origin, base_url);
                                this.href = this.href.replace("&_ajax=1", "");
                            });
                        if (window.lazyLoadInstance) {
                            window.lazyLoadInstance.update();
                        }
                    }
                },
                error: function (e) {
                    $("#space-search-listings").removeClass("loading");
                    if (e.responseText) {
                        $('.bravo-list-item-ajax').html('<p class="alert-text danger">' + e.responseText + '</p>')
                    }
                }
            });
        } else if (searchType === "map") {
            if (SEARCH_MAP_AJAX_REQUEST != null) {
                SEARCH_MAP_AJAX_REQUEST.abort();
                SEARCH_MAP_AJAX_REQUEST = null;
            }

            showMapLoading();

            SEARCH_MAP_AJAX_REQUEST = $.ajax({
                data: $('.bravo_form_search_map input,select').serialize() + '&userLat=' + window.userLat + '&userLng=' + window.userLng + '&q=' + spaceSearchQTerm + '&orderby=' + orderBY + '&_ajax=1&searchMethod=' + searchType,
                url: window.location.href.split('?')[0],
                dataType: 'json',
                type: 'get',
                success: function (json) { 
                    hideMapLoading();
                    if (json.status) {
                        resetMapZoomAndPos(json.markers);
                        if (window.lazyLoadInstance) {
                            window.lazyLoadInstance.update();
                        }
                    }
                },
                error: function (e) {
                    hideMapLoading();
                }
            });
        }
    }

    function reloadForm() {
        loadFormData();
        loadFormData("map");
    }


    const locsuccessCallback = (position) => {
        let latitude = position.coords.latitude;
        let longitude = position.coords.longitude;
        window.userLat = latitude;
        window.userLng = longitude;
        reloadForm();
    };

    const locerrorCallback = (error) => {
        console.log(error);
    };

    navigator.geolocation.getCurrentPosition(
        locsuccessCallback,
        locerrorCallback,
        {
            enableHighAccuracy: true,
            maximumAge: Infinity
        }
    );

    function reloadFormByUrl(url) {
        // showMapLoading();
        $("#space-search-listings").addClass("loading");
        $.ajax({
            url: url,
            dataType: 'json',
            type: 'get',
            success: function (json) {
                // hideMapLoading();
                if (json.status) {
                    // mapEngine.clearMarkers();
                    // mapEngine.addMarkers2(json.markers);

                    $("#space-search-listings").removeClass("loading");
                    $('.bravo-list-item-ajax').replaceWith(json.html);

                    $('.listing_items').animate({
                        scrollTop: 0
                    }, 'fast');

                    if (window.lazyLoadInstance) {
                        window.lazyLoadInstance.update();
                    }
                }

            },
            error: function (e) {
                $("#space-search-listings").removeClass("loading");
                // hideMapLoading();
                if (e.responseText) {
                    $('.bravo-list-item-ajax').html('<p class="alert-text danger">' + e.responseText + '</p>')
                }
            }
        })
    }

    $('.toggle-advance-filter').click(function () {
        var id = $(this).data('target');
        $(id).toggleClass('d-none');
    });

    $(document).on('click', '.filter-item .dropdown-menu', function (e) {

        if (!$(e.target).hasClass('btn-apply-advances')) {
            e.stopPropagation();
        }
    })
        .on('click', '.bravo-pagination a', function (e) {
            e.preventDefault();
            reloadFormByUrl($(this).attr('href'));
        });

    if ($('#reportrange').length > 0) {

        function cbDatePicker(start, end) {
            if (start == '' && end == '') {
                $('#reportrange span').html('Dates');
                $("#startDateVal").val("");
                $("#endDateVal").val("");
            } else {
                $('#reportrange span').html(start.format('D MMM hh:mm A') + ' - ' + end.format('D MMM hh:mm A'));

                $("#startDateVal").val(start.format("MM/DD/YYYY"));
                $("#endDateVal").val(end.format("MM/DD/YYYY"));

                $("#startTimeVal").val(start.format("HH:mm"));
                $("#endTimeVal").val(end.format("HH:mm"));

                $("#startTimeFull").val(start.format('YYYY-MM-DD HH:mm'));
                $("#endTimeFull").val(end.format('YYYY-MM-DD HH:mm'));
            }
            $('#reportrange').addClass('selected');
            reloadForm();
        }

        let start = moment($("#startTimeFull").val());
        let end = moment($("#endTimeFull").val());
        cbDatePicker(start, end);

        $('#reportrange').daterangepicker({
            "timePicker": true,
            "timePickerIncrement": 30,
            "timePickerSeconds": false,
            startDate: start,
            endDate: end,
            autoUpdateInput: false,
            ranges: {}
        }, cbDatePicker);

    }

    $(document).on('click', '.daterangepicker td', function () {
        var startDate = $('#reportrange').data('daterangepicker').startDate._d;
        var endDate = $('#reportrange').data('daterangepicker').endDate._d;
        cb(startDate, endDate)
    });

    $('#reportrange').on('show.daterangepicker', function (ev, picker) {
        //do something, like clearing an input
        $('#reportrange').addClass('active');
    });
    $('#reportrange').on('hide.daterangepicker', function (ev, picker) {
        //do something, like clearing an input
        $('#reportrange').removeClass('active');
    });

    function guest_text(o_g_adult = '', o_g_children = '', o_g_infants = '') {
        var filtersearch_desc = $('.guest_filter').find('.filtersearch_desc');
        if (o_g_adult != '') {
            var g_adult = o_g_adult;
            $('[name="g_adult"]').val(o_g_adult);
            $('.guest_filter .adult').find('.screen-reader-text').text(o_g_adult + '+');
        } else
            var g_adult = $('[name="g_adult"]').val();

        if (o_g_children != '') {
            var g_children = o_g_children;
            $('[name="g_children"]').val(o_g_children);
            $('.guest_filter .children').find('.screen-reader-text').text(o_g_children + '+');
        } else
            var g_children = $('[name="g_children"]').val();

        if (o_g_infants != '') {
            var g_infants = o_g_infants;
            $('[name="g_infants"]').val(o_g_infants);
            $('.guest_filter .infants').find('.screen-reader-text').text(o_g_infants + '+');
        } else
            var g_infants = $('[name="g_infants"]').val();

        var adult = parseInt(g_adult) + parseInt(g_children);
        var text = '';
        if (adult > 1) {
            text += adult + ' guests';
        } else {
            text += '1 guest';
        }

        if (g_infants > 1) {
            text += ',' + g_infants + ' infants';
        } else if (g_infants == 1) {
            text += ',' + g_infants + ' infant';
        }
        if (text == '') {
            $(filtersearch_desc).text('Guests');
            $(filtersearch_desc).removeClass('selected');
        } else {
            $(filtersearch_desc).text(text);
            $(filtersearch_desc).addClass('selected');
        }
    }

    $(document).on('click', '.plusminus_ul .plusminus .plus:not(.disabled)', function () {
        var curr_li = $(this).closest('li');
        var min_v = curr_li.attr('data-min');
        var guest_filter = $(this).closest('.guest_filter').length;
        var current_v = curr_li.find('[type="number"]').val();

        var new_v = parseInt(current_v) + parseInt(1);

        curr_li.find('[type="number"]').val(new_v);

        if (guest_filter > 0) {
            guest_text();
        }

        if (new_v <= min_v)
            curr_li.find('.minus').addClass('disabled');
        else
            curr_li.find('.minus').removeClass('disabled');

    });
    $(document).on('click', '.plusminus_ul .plusminus .minus:not(.disabled)', function () {
        var curr_li = $(this).closest('li');
        var min_v = curr_li.attr('data-min');
        var guest_filter = $(this).closest('.guest_filter').length;
        var current_v = curr_li.find('[type="number"]').val();

        var new_v = parseInt(current_v) - parseInt(1);

        curr_li.find('[type="number"]').val(new_v);

        if (guest_filter > 0) {
            guest_text()
        }

        if (new_v <= min_v)
            curr_li.find('.minus').addClass('disabled');
        else
            curr_li.find('.minus').removeClass('disabled');

    });

    $(document).on('click', '.filters .filterinp .filtersearch_desc', function () {
        $(".bottom_filter").hide();
        $(this).closest('.filterinp').find('.bottom_filter').show();
        $('.filtersearch_desc').removeClass('active')
        $(this).closest('.filterinp').find('.filtersearch_desc').addClass('active');
        o_g_adult = $('[name="g_adult"]').val();
        o_g_children = $('[name="g_children"]').val();
        o_g_infants = $('[name="g_infants"]').val();
    });

    $(document).click(function (e) {
        if ($('.filterinp').has(e.target).length === 0) {
            $(".bottom_filter").hide();
            $('.filtersearch_desc').removeClass('active');

        }
    });

    $(document).on('click', '.filters .filterinp .cancel', function () {
        var guest_filter = $(this).closest('.guest_filter').length;
        if (guest_filter > 0)
            guest_text(o_g_adult, o_g_children, o_g_infants);
        $(".bottom_filter").hide();
        $('.filtersearch_desc').removeClass('active');
    });

    mobile_filter_set();
    $(window).resize(function () {
        mobile_filter_set();
    });

    function mobile_filter_set() {
        var w = viewport();
        if (w.width < 768) {
            var officetype_filter = $('.filterinp.officetype_filter .bottom_filter .innerfilter').html();
            var price_fiter = $('.filterinp.price_fiter .bottom_filter .innerfilter').html();
            var instantbook_filter = $('.filterinp.instantbook_filter .bottom_filter .innerfilter').html();

            if (officetype_filter != '') {
                $('.more_filters .office_type_mobile .hereadd').html(officetype_filter);
                $('.filterinp.officetype_filter .bottom_filter .innerfilter').html('');
            }
            if (price_fiter != '') {
                $('.more_filters .price_mobile .hereadd').html('<input type="text" id="range" value="" name="range" />');
                $('.filterinp.price_fiter .bottom_filter .innerfilter').html('');
                $("#range").ionRangeSlider({
                    type: "double",
                    min: 25,
                    max: 1000,
                    from: $('#pstart').val(),
                    to: $('#pend').val(),
                    type: 'double',
                    prefix: "$",
                    grid: false,
                    grid_num: 10,
                    max_postfix: "<b>+<b>",
                    prettify_enabled: false,
                    onFinish: function (data) {
                        $('#pstart').val(data.from);
                        $('#pend').val(data.to);
                        ajaxsearchplaces();

                    }
                });
            }
            if (instantbook_filter != '') {
                $('.more_filters .instantbook_mobile .hereadd').html(instantbook_filter);
                $('.filterinp.instantbook_filter .bottom_filter .innerfilter').html('');
            }
        } else {
            var officetype_filter = $('.more_filters .office_type_mobile .hereadd').html();
            var price_fiter = $('.more_filters .price_mobile .hereadd').html();
            var instantbook_filter = $('.more_filters .instantbook_mobile .hereadd').html();

            if (officetype_filter != '') {
                $('.filterinp.officetype_filter .bottom_filter .innerfilter').html(officetype_filter);
                $('.more_filters .office_type_mobile .hereadd').html('');
            }
            if (price_fiter != '') {
                $('.filterinp.price_fiter .bottom_filter .innerfilter').html('<input type="text" id="range" value="" name="range" />');
                $('.more_filters .price_mobile .hereadd').html('');
                $("#range").ionRangeSlider({
                    type: "double",
                    min: 25,
                    max: 1000,
                    from: $('#pstart').val(),
                    to: $('#pend').val(),
                    type: 'double',
                    prefix: "$",
                    grid: false,
                    grid_num: 10,
                    max_postfix: "<b>+<b>",
                    prettify_enabled: false,
                    onFinish: function (data) {
                        $('#pstart').val(data.from);
                        $('#pend').val(data.to);
                        ajaxsearchplaces();

                    }
                });
            }
            if (instantbook_filter != '') {
                $('.filterinp.instantbook_filter .bottom_filter .innerfilter').html(instantbook_filter);
                $('.more_filters .instantbook_mobile .hereadd').html('');
            }
        }
    }

    function viewport() {
        var e = window, a = 'inner';
        if (!('innerWidth' in window)) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        return { width: e[a + 'Width'], height: e[a + 'Height'] };
    }

    $(document).on('click', '.searchheader .responsivehomebtn', function () {
        $('.searchheader .menu').slideToggle();
    });

    $(document).on('click', '.searchheader .menu li.has-submenu>a', function () {
        var w = viewport();
        if (w.width < 1201) {
            $(this).closest('li').find('.sub-menu').slideToggle();
            $(this).closest('li').toggleClass('open');
        }
    });

});


var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

var checkin = $('#dpd1').datepicker({
    onRender: function (date) {
        return date.valueOf() < now.valueOf() ? 'disabled' : '';
    }
}).on('changeDate', function (ev) {
    if (ev.date.valueOf() >= checkout.date.valueOf()) {
        var newDate = new Date(ev.date)
        newDate.setDate(newDate.getDate() + 1);
        checkout.setValue(newDate);
        $('#dpd2')[0].focus();
    }
    checkin.hide();

}).data('datepicker');
var checkout = $('#dpd2').datepicker({
    onRender: function (date) {
        return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
    }
}).on('changeDate', function (ev) {

    var othercheck = $('#dpd1').val();
    if (othercheck.trim() == "") {
        var newDate = new Date(ev.date)
        newDate.setDate(newDate.getDate() - 1);
        checkin.setValue(newDate);
        $('#dpd1')[0].focus();
    }
    checkout.hide();

}).data('datepicker');

