<!-- Booking ends -->
<div class="clearfix"></div>

<script type="text/javascript" src="{{ asset('js/cycle.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function (e) {

        setInterval(function () {
            $("#indate").val($('#dpd1').val());
            $("#outdate").val($('#dpd2').val());
        }, 500);


        $(document).on("change", "#timestart", function () {
            $("#in_start_time").val($(this).val());
            $('#start_ampm').select2('open');
        });

        $(document).on("change", "#timestart_am_pm", function () {
            $("#in_start_ampm").val($(this).val());
            $('#dpd2').focus();
        });

        $(document).on("change", "#timeend", function () {
            $("#out_start_time").val($(this).val());
            $('#end_ampm').select2('open');
        });

        $(document).on("change", "#timeend_am_pm", function () {
            $("#out_start_ampm").val($(this).val());
            $('#guest').focus();
            setTimeout(function () {
                $("input#guest")[0].focus();
            }, 100);
        });

        $(document).on("click", "#select2-start_time-results .select2-results__option", function () {
            ('#start_ampm').select2('open');
        });

        $('#cbox1').cycle({
            fx: 'scrollDown',
            speed: 500,
            timeout: 4000,
            pause: true,
        });
        $('#cbox2').cycle({
            fx: 'scrollLeft',
            speed: 500,
            timeout: 6000,
            pause: true,
        });
        $('#cbox3').cycle({
            fx: 'scrollUp',
            speed: 500,
            timeout: 8000,
            pause: true,
        });
        $('#cbox4').cycle({
            fx: 'scrollUp',
            speed: 500,
            timeout: 4000,
            pause: true,
        });
        $('#cbox5').cycle({
            fx: 'scrollRight',
            speed: 500,
            timeout: 6000,
            pause: true,
        });
        $('#cbox6').cycle({
            fx: 'scrollDown',
            speed: 500,
            timeout: 8000,
            pause: true,
        });

    });
</script>
<!-- Ends -->

<!-- Date range picker ends -->
<script type="text/javascript">
    $(document).ready(function (e) {
        //map mobile click
        function viewport() {
            var e = window, a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        }


        $(document).on('click', '.indexheaderwrper .menu li.has-submenu>a', function () {
            var w = viewport();
            if (w.width < 992) {
                $(this).closest('li').find('.sub-menu').slideToggle();
                $(this).closest('li').toggleClass('open');
            }
        });
        $(document).on('click', '.indexheaderwrper .responsivehomebtn', function () {
            $('.indexheaderwrper .menu').slideToggle();
        });
        $(document).on('click', '.tabsproperty .tabs a', function () {
            var id = $(this).attr('data-id');
            $('.tabsproperty .tabcontainer .tabwrapper').hide();
            $('.tabsproperty .tabcontainer #' + id).show();
            $('.tabsproperty .tabs a').removeClass('active')
            $(this).addClass('active');
        });
        var id = $('.tabsproperty .tabs a.active').attr('data-id');
        $('.tabsproperty .tabcontainer .tabwrapper').hide();
        $('.tabsproperty .tabcontainer #' + id).show();

        // footer fixed
        var windowwidth = $(window).width();
        var footerheight = $('.fulfooter').outerHeight(true);
        if (windowwidth > 1000) {
            $('.footergap').css({
                "height": footerheight
            });
        } else {
            $('.footergap').css({
                "height": 0
            });
        }
        $(window).resize(function () {

            var windowwidth = $(window).width();
            var footerheight = $('.fulfooter').outerHeight(true);
            if (windowwidth > 1000) {
                $('.footergap').css({
                    "height": footerheight
                });
            } else {
                $('.footergap').css({
                    "height": 0
                });
            }

        });
    });

</script>

<!-- Footer section -->
<script type="text/javascript" src="js/common.js?v={{time()}}"></script>

<div class="clearfix"></div>

<script type="text/javascript">
    $(document).ready(function (e) {

        $('.myacounthover').hover(function () {

            $(this).children('.myacountdropdown').stop().fadeIn(200);

        }, function () {
            $(this).children('.myacountdropdown ').stop().fadeOut(200);
        });

        // RESPONSIVE MENU Starts

        var menucontent = $('.rightmenu').html();
        // main menu's Html

        $('.responsive_menulist').html(menucontent);
        // adding main menu to responsive menu

        // if width > 1000px responsive menu and close btn Hide Starts

        // if width > 1000px responsive menu and close btn Hide Ends

        $('.responsivebtn').click(function (e) {
            $(this).toggleClass("active");
            $('.responsive_menulist').fadeToggle(200);
            //$('.responsive_menulist').toggle( "drop", { direction: "right" }, 200);
        });

        $('.responsivebtn, .responsive_menulist').click(function (event) {
            event.stopPropagation();

        });
        $(document).click(function (e) {
            $('.responsivebtn').removeClass("active");
            $('.responsive_menulist').fadeOut(200);
        });

        // Sub Menu showing in Responsive menu
        $(".responsive_menulist .myacounthover").click(function () {

            $(this).children('.responsive_menulist .myacountdropdown ').slideToggle(200);
        });

        // Sub Menu showing in Responsive menu	 Ends

        // RESPONSIVE MENU ENDS

    });

</script>
<script type="text/javascript" src="js/signinpopup.js?v={{config('app.version')}}"></script>

<!-- Menu ends -->


<script src="libs/lazy-load/intersection-observer.js"></script>
<script async src="libs/lazy-load/lazyload.min.js"></script>
<script>
    // Set the options to make LazyLoad self-initialize
    window.lazyLoadOptions = {
        elements_selector: ".lazy",
        // ... more custom settings?
    };

    // Listen to the initialization event and get the instance of LazyLoad
    window.addEventListener('LazyLoad::Initialized', function (event) {
        window.lazyLoadInstance = event.detail.instance;
    }, false);


</script>

<script type="text/javascript">
    $(document).ready(function (e) {

        $(".formselect").select2();

        $(".selectsearch, .footerselect").select2();

        wow = new WOW({
            animateClass: 'animated',
            mobile: false,

            offset: 100
        });
        wow.init();
        // slect style

    });
</script>


<script type="text/javascript">
    $(document).ready(function (e) {
        $(".detailschkin, .w_privcyslect").select2();

        $(".wishlistselect").select2();

        // create category

        $('.createnew_wclick').click(function (e) {
            $('.cratewinput').fadeToggle(200);
        });

        // wishlist fadeIn

        $('.wishlistclose').click(function (e) {
            $('.wishlistouter, .overlayb').fadeOut(200);
        });
        $(document).click(function (e) {
            $('.wishlistouter, .overlayb').fadeOut(200);
        });
        $('.lobox, .wishlistopen').click(function (event) {
            event.stopPropagation();
        });

        // contact host section

        $('.contacthostopen').click(function (e) {

            var userloginstat = '0';

            if (userloginstat == 0) {
                $(".signinbtn").click();
                return false;

            }
            $('.conacthostouter, .overlayContact').fadeIn(200);
        });
        $('.conacthostclose ').click(function (e) {
            $('.conacthostouter, .overlayContact').fadeOut(200);
        });


        $('#openCalendar').click(function (e) {
            $('.calendarview, .overlayContact').fadeIn(200);

            setTimeout(function () {
                if ($("#calendar").hasClass("isLoading")) {
                    $("#calendar").html("");
                    initCalendar();
                    $("#calendar").removeClass("isLoading");
                }
            }, 2000);

        });
        $('.openCalendarClose').click(function (e) {
            $('.calendarview, .overlayContact').fadeOut(200);
        });


    });

</script>
<script type="text/javascript" src="js/datepikernew.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('#tempsend').hide();
        $('#tempmsg').show();
        $('#tempeff').hide();
        $('#temperror').show();
        var today = new Date();
        var dd = today.getDate() + 1;
        var mm = today.getMonth() + 1;
        //January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }

        today = mm + '/' + dd + '/' + yyyy;
        var temp = '';
        // alert(temp);

        var blockedarray = temp.split('#');

        // alert(blockedarray);
        $('.input-daterange').datepicker({
            clearBtn: true,
            autoclose: true,
            startDate: '+0d'
        });

        $("#accounmentationGuests").on("keyup", function () {
            getPricingOfListing();
        });

        function getPricingOfListing() {

            // $("#temperror").show().html("");
            // //validate guest count
            // var maxiGuestAllo = 1;
            // maxiGuestAllo = maxiGuestAllo * 1;


            // var fromval = $('#dpd1x1').val();
            // var toval = $('#dpd2x2').val();
            // $('#listselprice').hide();
            // if (fromval == toval && fromval != "" && toval != "") {
            //     //$('#dpd2x2').focus();

            // }

            // var flag = 0;
            // if (fromval != "") {

            //     var fromtimestamp = new Date(fromval).getTime();

            // } else {

            //     flag = 4;
            // }
            // if (toval != "") {

            //     var totimestamp = new Date(toval).getTime();

            // } else {

            //     flag = 4;
            // }
            // var tdaytimestamp = new Date(today).getTime();

            // /*if (totimestamp == fromtimestamp) {
			// 		flag = 2;

			// 	}*/

            // var fromvalnew = $('#dpd1x1').val();
            // var tovalnew = $('#dpd2x2').val();
            // var newtimestampfrom = new Date(fromvalnew).getTime();
            // var newtimestampto = new Date(tovalnew).getTime();
            // var requestGutestor = $("#accounmentationGuests").val();
            // requestGutestor = requestGutestor * 1;
            // var guestselect = requestGutestor;
            // if (requestGutestor > maxiGuestAllo) {
            //     $("#tempeff").hide();
            //     $("#temperror").show().html("Maximum number of guests allowed are " + maxiGuestAllo + ".");
            //     return false;
            // } else if (flag == 1) {
            //     $('#tempeff').hide();
            //     $('#msgx').html('Those dates are not available');
            //     //alert('Those dates are not available');
            //     $('#temperror').show();
            //     //$('#dpd1x1').focus();
            // } else if (flag == 2) {
            //     $('#tempeff').hide();
            //     $('#msgx').html('Check In and Check Out Cannot be on Same Day');
            //     $('#temperror').show();
            //     $('#dpd2x2').trigger("click");

            // } else if (flag == 4) {
            //     $('#tempeff').hide();
            //     $('#msgx').html('Select a Check In and Check out date');
            //     $('#temperror').show();

            // } else {

            //     var listid = "12722";
            //     //	var loguserid="{$loguserid}";
            //     var hourprice = "0";
            //     var dayprice = "25";
            //     var weekprice = "55";
            //     var monthprice = "100";


            //     var start_time = $("#start_time").val();
            //     var start_ampm = $("#start_ampm").val();
            //     var end_time = $("#end_time").val();
            //     var end_ampm = $("#end_ampm").val();


            //     var time_array = [{
            //         url: window.location.href,
            //         'indate': fromvalnew,
            //         'outdate': tovalnew,
            //         'in_start_time': start_time,
            //         'in_start_ampm': start_ampm,
            //         'out_start_time': end_time,
            //         'out_start_ampm': end_ampm,
            //         'guestselect': guestselect
            //     }];
            //     localStorage.setItem('time_array', JSON.stringify(time_array));


            //     $('#tempeff').hide();
            //     $('#listselprice').hide();
            //     $('#loading-image').show();
            //     $.ajax({

            //         type: "POST",
            //         cache: false,

            //         url: bookingCore.url+"/index.php?page=rental/listpriceset",

            //         data: {
            //             'fromdate': fromvalnew,
            //             'todate': tovalnew,
            //             'listid': listid,
            //             'hourprice': hourprice,
            //             'dayprice': dayprice,
            //             'weekprice': weekprice,
            //             'monthprice': monthprice,
            //             'start_time': start_time,
            //             'start_ampm': start_ampm,
            //             'end_time': end_time,
            //             'end_ampm': end_ampm
            //         },

            //         success: function (data) {
            //             //alert(data);
            //             var requestGutestor = $("#accounmentationGuests").val();
            //             requestGutestor = requestGutestor * 1;
            //             if (requestGutestor > maxiGuestAllo) {
            //                 $("#tempeff").hide();
            //                 $("#temperror").show().html("Maximum number of guests allowed are " + maxiGuestAllo + ".");
            //                 return false;
            //             } else if ($.trim(data) == "dayblocked") {
            //                 $("#tempeff").hide();
            //                 $("#temperror").show().html("Selected timing is not Available. Check Availability Calendar for More Information");
            //             } else if ($.trim(data) == "pasttime") {
            //                 $("#tempeff").hide();
            //                 $("#temperror").show().html("Time should be greater than now.");
            //             } else if ($.trim(data) == "checktime") {
            //                 $("#tempeff").hide();
            //                 $("#temperror").show().html("In-Valid Timings");
            //             } else if ($.trim(data) == "sometimeblockbetween") {
            //                 $("#tempeff").hide();
            //                 $("#temperror").show().html("Some Hours are not Available in Selected Timing. Check Availability Calendar for More Information");
            //             } else if ($.trim(data) == "sometimeresevered") {
            //                 $("#tempeff").hide();
            //                 $("#temperror").show().html("Some Hours are already booked between Selected Timing. Check Availability Calendar for More Information");
            //             } else {

            //                 $('#listselprice').html("");
            //                 //alert(data);
            //                 $('#listselprice').html(data);
            //                 $('#listselprice').show();
            //                 $('#tempeff').show();
            //                 $("#temperror").hide().html("");
            //             }

            //         },
            //         complete: function () {
            //             $('#loading-image').hide();
            //         }
            //     });

            // }


        }


        $(document).on("change", "#start_time,#start_ampm,#end_time,#end_ampm", function () {
            getPricingOfListing();
        })


        $('.input-daterange input').each(function () {
            $(this).on('changeDate', function () {
                getPricingOfListing();
            });

            $(this).on('clearDate', function () {
                var id = $(this).parent('div').parent('div').attr('id');
                if (id == "datepicker") {
                    $('#listselprice').hide();
                    $('#tempeff').hide();
                    $('#msgx').html('Select a Check In and Check out date');
                    $('#temperror').show();
                }

                //$('#dpd2x2').focus();
                if (id == "datepickercontact") {
                    $('#tempsend').hide();
                    $('#msgy').html('Choose a Check In and Check Out date & type in the Message you want to Send! ');
                    $('#tempmsg').show();
                }

            });

        });

        //check local storage for already present values
        if (localStorage.getItem("time_array") !== null) {
            timesArray = JSON.parse(localStorage["time_array"]);
            timeArrayKey = timesArray[0];
            if (timeArrayKey['url'] == window.location.href) {
                if (timeArrayKey['indate'] != null) {
                    $("#dpd1x1").val(timeArrayKey['indate']);
                }
                if (timeArrayKey['outdate'] != null) {
                    $("#dpd2x2").val(timeArrayKey['outdate']);
                }

                if (timeArrayKey['in_start_time'] != '') {
                    var vald = timeArrayKey['in_start_time'];
                    $("#start_time").val(vald);
                    $("#select2-start_time-container").html(vald).attr("title", vald);
                } else {
                    $("#start_time").val("12:00");
                    $("#select2-start_time-container").html("12:00").attr("title", "12:00");
                }

                if (timeArrayKey['out_start_time'] != '') {
                    var vald = timeArrayKey['out_start_time'];
                    $("#end_time").val(vald);
                    $("#select2-end_time-container").html(vald).attr("title", vald);
                } else {
                    $("#end_time").val("11:30");
                    $("#select2-end_time-container").html("11:30").attr("title", "11:30");
                }

                if (timeArrayKey['in_start_ampm'] != '') {
                    var vald = timeArrayKey['in_start_ampm'];
                    $("#start_ampm").val(vald);
                    $("#select2-start_ampm-container").html(vald).attr("title", vald);
                } else {
                    $("#start_ampm").val("AM");
                    $("#select2-start_ampm-container").html("AM").attr("title", "AM");
                }

                if (timeArrayKey['out_start_ampm'] != '') {
                    var vald = timeArrayKey['out_start_ampm'];
                    $("#end_ampm").val(vald);
                    $("#select2-end_ampm-container").html(vald).attr("title", vald);
                } else {
                    $("#end_ampm").val("PM");
                    $("#select2-end_ampm-container").html("PM").attr("title", "PM");
                }

                if (timeArrayKey['guestselect'] != '') {
                    var vald = timeArrayKey['guestselect'];
                    $("#accounmentationGuests").val(vald);
                }


                getPricingOfListing();
            } else {
                //console.log("Fsdf");
                $("#start_time").val('10:00');
                $("#select2-start_time-container").html('10:00').attr("title", '10:00');
                $("#end_time").val('1:00');
                $("#select2-end_time-container").html('1:00').attr("title", '1:00');
                $("#start_ampm").val('AM');
                $("#select2-start_ampm-container").html('AM').attr("title", 'AM');
                $("#end_ampm").val('PM');
                $("#select2-end_ampm-container").html('PM').attr("title", 'PM');
            }

        }

    });

</script>

<script type="text/javascript"
        src="{{ asset('module/space/js/space-map.js?v=1&t='.time().'&_ver='.config('app.version')) }}"></script>

<script type="text/javascript">


    $("#range").ionRangeSlider({
        type: "double",
        min: 25,
        max: 1000,
        from: 25,
        to: 1000,
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
</script>


<script type="text/javascript">
    $(document).ready(function (e) {

        $(".wishlistselect").select2();

        // create category

        $('.createnew_wclick').click(function (e) {
            $('.cratewinput').fadeToggle(200);
        });

        // wishlist fadeIn
        $(document.body).on('click', '.wishlistopen', function () {

            //$('.wishlistselect').select2('data','');
            $(".wishlistselect").select2("val", "");

            if ($(this).parent().parent().parent().attr('id') != undefined) {

                var mid = $(this).parent().parent().parent().attr('id').split("-");

                var ind = lidarrasy.indexOf(mid[1]);
                //alert(ind);

                google.maps.event.trigger(markersArray[ind], "click");
            }
            var userloginstat = '0';

            //	alert(markersArray[0]); return false;
            if (userloginstat == 0) {

                $(".signinbtn").click();
                return false;

            }
            wishlistview();
            var lidstring = $(this).attr("id");
            var lid1 = lidstring.split("_");
            var lid = lid1[1];

            $.ajax({

                type: "POST",
                cache: false,

                url: "http://mofront.myoffice.ca/index.php?page=search/getwishlistdetail",

                data: {
                    'lid': lid
                },

                success: function (data) {
                    var details = data.split("??");
                    if (data != 0) {
                        $('#listaddresswish').html(details[0]);
                        $('#listheadingwish').html(details[1]);
                        $('#listimagewishdiv').html(details[2]);
                        $('#actuallistid').val(lid);

                    }

                }
            });
            $('.wishlistouter, .overlayb').fadeIn(200);
        });

        $('.wishlistopen').click(function (e) {
            $(".wishlistselect").select2("val", "");
            //$(document).on('click', '.wishlistopen', function(){
            var userloginstat = '0';
            var mid = $(this).parent().parent().parent().attr('id').split("-");
            var ind = lidarrasy.indexOf(mid[1]);
            //	var ind=mid[1];
            google.maps.event.trigger(markersArray[ind], "click");

            if (userloginstat == 0) {

                $(".signinbtn").click();
                return false;

            }
            wishlistview();
            var lidstring = $(this).attr("id");
            var lid = lidstring.replace("wishicon_", '');
            var lid = lid.trim();

            $.ajax({

                type: "POST",
                cache: false,

                url: "http://mofront.myoffice.ca/index.php?page=search/getwishlistdetail",

                data: {
                    'lid': lid
                },

                success: function (data) {
                    var details = data.split("??");
                    if (data != 0) {
                        $('#listaddresswish').html(details[0]);
                        $('#listheadingwish').html(details[1]);
                        $('#listimagewishdiv').html(details[2]);
                        $('#actuallistid').val(lid);

                    }

                }
            });
            $('.wishlistouter, .overlayb').fadeIn(200);
        });

        $('.wishlistclose').click(function (e) {
            $('.wishlistouter, .overlayb').fadeOut(200);
        });

        $('.lobox, .wishlistopen').click(function (event) {
            event.stopPropagation();
        });

    });

</script>


@if (!is_api())
    <footer class="mainfooter">
        <div class="container">
            <div class="footcol">
                <h4>COMPANY</h4>
                <ul>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/about-us">About Us</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/terms-of-service">Terms and Conditions</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/privacy-policy">Privacy Policy </a>
                    </li>
                </ul>


            </div>
            <div class="footcol">
                <h4>HOST SERVICES</h4>
                <ul>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/share-your-space">Share Your Space </a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/host-faqs">FAQs</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/register">Sign Up</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/contact">Support</a>
                    </li>
                </ul>
            </div>
            <div class="footcol">
                <h4>GUEST SERVICES</h4>
                <ul>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/page/guests-how-it-works">Be Our Guests</a>
                    </li>
                    <li>
                        <a href="http://myoffice.mybackpocket.co/contact">Support</a>
                    </li>
                </ul>
            </div>

            <div class="sociallinks">
                Join Us On
                <ul class="socialul text-center">
                    <li class="sociali left">
                        <a class="sociala" href="https://www.facebook.com/myofficeinc" target="_blank"><i
                                class="fa fa-facebook" style="padding: 7px;"></i></a>
                    </li>
                    <li class="sociali left">
                        <a class="sociala" href="https://www.instagram.com/myofficeinc/" target="_blank"><i
                                class="fa fa-instagram" style="padding: 7px;"></i></a>
                    </li>
                </ul>

            </div>
            <div class="clearfix"></div>
            <div class="copyright">
                Copyright &copy; {{date('Y')}} My Office Inc. All Rights Reserved. </div>
            <div style="display:none;">
            </div>
        </div>
        </div>
    </footer>
    <div class="coprightbtm fulwidthm left text-center footerbg">

    </div>
@endif

@include('Layout::Home.parts.login-register-modal')
@include('Layout::Home.parts.chat')
@if (Auth::id())
    @include('Media::browser')
@endif
<link rel="stylesheet" href="{{ asset('libs/flags/css/flag-icon.min.css') }}">
<script type="text/javascript" src="{{ asset('js/common.js') }}?v={{time()}}"></script>
{!! \App\Helpers\Assets::css(true) !!}

<script>
    $(document).on("click", ".service-wishlist", function () {
        var $this = $(this);
        $.ajax({
            url: bookingCore.url + '/user/wishlist',
            data: {
                object_id: $this.attr("data-id"),
                object_model: $this.attr("data-type"),
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function () {
                $this.addClass("loading");
            },
            success: function (res) {
                $this.attr('class', "service-wishlist " + res.class);
                $("#msg-success").html("Added To Wishlist");

            },
            error: function (e) {
                if (e.status === 401) {
                    $('#login').modal('show');
                }
            }
        })
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: bookingCore.url + '/space/add-to-favourite',
            data: {
                space_id: $this.attr("data-id"),
                _token: _token
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function () {
                $this.addClass("loading");
            },
            success: function (res) {
                $this.attr('class', "service-wishlist " + res.class);
                $("#msg-success").html("Added To Favourite");

            },
            error: function (e) {
                if (e.status === 401) {
                    $('#login').modal('show');
                }
            }
        })
    });
</script>

<script type="text/javascript">
    $(document).ready(function(e) {

        $('.myacounthover').hover(function() {

            $(this).children('.myacountdropdown').stop().fadeIn(200);

        }, function() {
            $(this).children('.myacountdropdown ').stop().fadeOut(200);
        });

        // RESPONSIVE MENU Starts

        var menucontent = $('.rightmenu').html();
        // main menu's Html

        $('.responsive_menulist').html(menucontent);
        // adding main menu to responsive menu

        // if width > 1000px responsive menu and close btn Hide Starts

        // if width > 1000px responsive menu and close btn Hide Ends

        $('.responsivebtn').click(function(e) {
            $(this).toggleClass("active");
            $('.responsive_menulist').fadeToggle(200);
            //$('.responsive_menulist').toggle( "drop", { direction: "right" }, 200);
        });

        $('.responsivebtn, .responsive_menulist').click(function(event) {
            event.stopPropagation();

        });
        $(document).click(function(e) {
            $('.responsivebtn').removeClass("active");
            $('.responsive_menulist').fadeOut(200);
        });

        // Sub Menu showing in Responsive menu
        $(".responsive_menulist .myacounthover").click(function() {

            $(this).children('.responsive_menulist .myacountdropdown ').slideToggle(200);
        });

        // Sub Menu showing in Responsive menu	 Ends

        // RESPONSIVE MENU ENDS

    });
</script>
<script type="text/javascript" src="{{ asset('js/signinpopup.js') }}?v={{config('app.version')}}"></script>
{{-- Lazy Load --}}
<script src="{{ asset('libs/lazy-load/intersection-observer.js') }}"></script>
<script async src="{{ asset('libs/lazy-load/lazyload.min.js') }}"></script>
<script>
    // Set the options to make LazyLoad self-initialize
    window.lazyLoadOptions = {
        elements_selector: ".lazy",
        // ... more custom settings?
    };

    // Listen to the initialization event and get the instance of LazyLoad
    window.addEventListener('LazyLoad::Initialized', function(event) {
        window.lazyLoadInstance = event.detail.instance;
    }, false);
</script>

<link rel="stylesheet" href="{{ asset('css/superslides.css') }}">

<script type="text/javascript" src="{{ asset('js/jquery.superslides.js') }}"></script>
<script type='text/javascript'>
    $(document).ready(function() {
        $('#slides').superslides({
            animation: 'fade',
            pagination: false,
            play: 3000
        });
    })
</script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/bootbox/bootbox.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('libs/daterange/daterangepicker.min.js') }}"></script>
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/functions.js?_ver=' . config('app.version')) }}"></script>
{{-- <script src="{{ asset('js/home.js?_ver=' . config('app.version')) }}"></script> --}}
<script src="{{ asset('libs/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('libs/lodash.min.js') }}"></script>
{{-- <script src="{{ asset('libs/vue/vue' . (!env('APP_DEBUG') ? '.min' : '') . '.js') }}"></script> --}}
<script src="{{ asset('libs/carousel-2/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/animate/animate.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/animate/wow.js') }}"></script>
<script src='https://unpkg.com/@google/markerclustererplus@4.0.1/dist/markerclustererplus.min.js'></script>
<script src='http://myoffice.mybackpocket.co/libs/infobox.js'></script>
<script src='http://myoffice.mybackpocket.co/module/core/js/map-engine.js?_ver=2.2.2'></script>
<script type="text/javascript" src="{{ asset('js/select2.js') }}"></script>
<script src="js/home.js?v={{config('app.version')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>









@if (Auth::id())
    <script src="{{ asset('module/media/js/browser.js?_ver=' . config('app.version')) }}"></script>
@endif





@if (setting_item('tour_location_search_style') == 'autocompletePlace' || setting_item('hotel_location_search_style') == 'autocompletePlace' || setting_item('car_location_search_style') == 'autocompletePlace' || setting_item('space_location_search_style') == 'autocompletePlace' || setting_item('hotel_location_search_style') == 'autocompletePlace' || setting_item('event_location_search_style') == 'autocompletePlace')
    {!! App\Helpers\MapEngine::scripts() !!}
@endif
<script src="{{ asset('libs/pusher.min.js') }}"></script>


@if (!empty($is_user_page))
    <script src="{{ asset('module/user/js/user.js?_ver=' . config('app.version')) }}"></script>
@endif
@if (setting_item('cookie_agreement_enable') == 1 and request()->cookie('booking_cookie_agreement_enable') != 1 and !is_api() and !isset($_COOKIE['booking_cookie_agreement_enable']))
    <div class="booking_cookie_agreement p-3 d-flex fixed-bottom">
        <div class="content-cookie">{!! clean(setting_item_with_lang('cookie_agreement_content')) !!}</div>
        <button class="btn save-cookie">{!! clean(setting_item_with_lang('cookie_agreement_button_text')) !!}</button>
    </div>
    <script>
        var save_cookie_url = '{{ route('core.cookie.check') }}';
    </script>
    <script src="{{ asset('js/cookie.js?_ver=' . config('app.version')) }}"></script>
@endif

{!! \App\Helpers\Assets::js(true) !!}
<script type="text/javascript">
    $(document).ready(function(e) {
        //map mobile click
        function viewport() {
            var e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        }


        $(document).on('click', '.indexheaderwrper .menu li.has-submenu>a', function() {
            var w = viewport();
            if (w.width < 992) {
                $(this).closest('li').find('.sub-menu').slideToggle();
                $(this).closest('li').toggleClass('open');
            }
        });
        $(document).on('click', '.indexheaderwrper .responsivehomebtn', function() {
            $('.indexheaderwrper .menu').slideToggle();
        });
        $(document).on('click', '.tabsproperty .tabs a', function() {
            var id = $(this).attr('data-id');
            $('.tabsproperty .tabcontainer .tabwrapper').hide();
            $('.tabsproperty .tabcontainer #' + id).show();
            $('.tabsproperty .tabs a').removeClass('active')
            $(this).addClass('active');
        });
        var id = $('.tabsproperty .tabs a.active').attr('data-id');
        $('.tabsproperty .tabcontainer .tabwrapper').hide();
        $('.tabsproperty .tabcontainer #' + id).show();

        // footer fixed
        var windowwidth = $(window).width();
        var footerheight = $('.fulfooter').outerHeight(true);
        if (windowwidth > 1000) {
            $('.footergap').css({
                "height": footerheight
            });
        } else {
            $('.footergap').css({
                "height": 0
            });
        }
        $(window).resize(function() {

            var windowwidth = $(window).width();
            var footerheight = $('.fulfooter').outerHeight(true);
            if (windowwidth > 1000) {
                $('.footergap').css({
                    "height": footerheight
                });
            } else {
                $('.footergap').css({
                    "height": 0
                });
            }

        });
    });
</script>
@yield('footer')

@php \App\Helpers\ReCaptchaEngine::scripts() @endphp

<script type="text/javascript">
    $(document).ready(function(e) {

        $(".formselect").select2();

        $(".selectsearch, .footerselect").select2();

        wow = new WOW({
            animateClass: 'animated',
            mobile: false,

            offset: 100
        });
        wow.init();
        // slect style

    });
</script>
<script type="text/javascript">
    (function(d, src, c) {
        var t = d.scripts[d.scripts.length - 1],
            s = d.createElement('script');
        s.id = 'la_x2s6df8d';
        s.async = true;
        s.src = src;
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs && (rs != 'complete') && (rs != 'loaded')) {
                return;
            }
            c(this);
        };
        t.parentElement.insertBefore(s, t.nextSibling);
    })(document, 'https://shredding.ladesk.com/scripts/track.js', function(e) {
        LiveAgent.createButton('af5fe7b7', e);
    });
</script>

<script type="text/javascript">
    (function(d, src, c) {
        var t = d.scripts[d.scripts.length - 1],
            s = d.createElement('script');
        s.id = 'la_x2s6df8d';
        s.async = true;
        s.src = src;
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs && (rs != 'complete') && (rs != 'loaded')) {
                return;
            }
            c(this);
        };
        t.parentElement.insertBefore(s, t.nextSibling);
    })(document, 'https://shredding.ladesk.com/scripts/track.js', function(e) {});
</script>
<script type="text/javascript">
    (function(d, src, c) {
        var t = d.scripts[d.scripts.length - 1],
            s = d.createElement('script');
        s.id = 'la_x2s6df8d';
        s.async = true;
        s.src = src;
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs && (rs != 'complete') && (rs != 'loaded')) {
                return;
            }
            c(this);
        };
        t.parentElement.insertBefore(s, t.nextSibling);
    })(document, 'https://shredding.ladesk.com/scripts/track.js', function(e) {});
</script>
