<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $html_class ?? '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php event(new \Modules\Layout\Events\LayoutBeginHead()); @endphp

    {{-- @php
        $favicon = setting_item('site_favicon');
    @endphp
    @if ($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if (!empty($file))
            <link rel="icon" type="{{ $file['file_type'] }}" href="{{ asset('uploads/' . $file['file_path']) }}" />
        @else:
            <link rel="icon" type="image/png" href="{{ url('images/favicon.png') }}" />
        @endif
    @endif --}}

    <link rel="icon" type="image/png" href="{{ url('images/fav-main.png') }}" />

    @include('Layout::Home.parts.seo-meta')

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/reset.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.css') }}" />

    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}" />

    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notification.css') }}" rel="newest stylesheet">
    <link href="{{ asset('css/app.css?_ver=2.2.2') }}" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="{{ asset('libs/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/fotorama/fotorama.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/ion_rangeslider/css/ion.rangeSlider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hotel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/space.css') }}">

    <link href="{{ asset('css/custom.css') }}?v={{ time() }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-style.css') }}" />

    <link rel="stylesheet" href="{{ asset('libs/flags/css/flag-icon.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/superslides.css') }}">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css"
        type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Josefin+Sans:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
    <link
        href="https://fonts.googleapis.com/css?family=Eczar:700|Work+Sans:300,400,500,600,700|Montserrat:300,400,500,600,700"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/iefix.css" type="text/css') }}">
    <link rel="stylesheet" href="{{ asset('css/sangaslider/ie8.css') }}" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ asset('libs/daterange/daterangepicker.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

    <link rel="stylesheet" href="{{ asset('libs/toastr/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="libs/daterange/daterangepicker.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel='stylesheet' id='google-font-css-css'
        href='https://fonts.googleapis.com/css?family=Poppins%3A300%2C400%2C500%2C600' type='text/css' media='all' />

    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="{{ asset('css/icongc.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simpledatapiker/datepicker.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

    <link href='{{ \App\Helpers\CodeHelper::withAppUrl('libs/fullcalendar/main.css') }}' rel='stylesheet' />

    <script>
        window.userLat = localStorage.getItem("userLat");
        window.userLng = localStorage.getItem("userLng");

        const locMainsuccessCallback = (position) => {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            localStorage.setItem("userLat", latitude);
            localStorage.setItem("userLng", longitude);
            window.userLat = latitude;
            window.userLng = longitude;
        };

        const locMainerrorCallback = (error) => {
            console.log(error);
        };

        navigator.geolocation.getCurrentPosition(
            locMainsuccessCallback,
            locMainerrorCallback, {
                enableHighAccuracy: true,
                maximumAge: Infinity
            }
        );
    </script>

    <style>
        .dateInputC {
            width: 65%;
            float: left;
        }

        .detailformrow.non-time .dateInputC {
            width: 100%;
        }

        .timeInputC {
            width: 30%;
            float: left;
            margin: 0px 2.5%;
        }

        .whenInputC {
            width: 25%;
            float: left;
            display: none;
        }

        .calendarview {
            z-index: 9999999;
            display: none;
            position: fixed;
            height: 100%;
            width: 100%;
            overflow: auto;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
        }

        .calendarview .whitebg {

            background-color: #FFFFFF;
            box-shadow: 0px 15px 15px rgba(0, 0, 0, 0.5);

        }

        .btnGroups span {
            padding: 5px 11px;
            border-radius: 0px;
            margin-right: 5px;
        }

        .notAvailableBtn {
            background: #e07272;
            color: #fff;
        }

        .bookedBtn {
            background: #65aa5f;
            color: #fff;
        }

        .pendingAppBtn {
            background: #00bcd4;
            color: #fff;
        }

        .wishlistopen,
        .resultlove {
            position: relative !important;
        }
    </style>


    <script>
        var bookingCore = {
            url: '{{ url(app_get_locale()) }}',
            url_root: '{{ url('') }}',
            booking_decimals: {{ (int) get_current_currency('currency_no_decimal', 2) }},
            thousand_separator: '{{ get_current_currency('currency_thousand') }}',
            decimal_separator: '{{ get_current_currency('currency_decimal') }}',
            currency_position: '{{ get_current_currency('currency_format') }}',
            currency_symbol: '{{ currency_symbol() }}',
            currency_rate: '{{ get_current_currency('rate', 1) }}',
            date_format: '{{ get_moment_date_format() }}',
            map_provider: '{{ setting_item('map_provider') }}',
            map_gmap_key: '{{ setting_item('map_gmap_key') }}',
            map_options: {
                map_lat_default: '{{ setting_item('map_lat_default') }}',
                map_lng_default: '{{ setting_item('map_lng_default') }}',
                map_clustering: '{{ setting_item('map_clustering') }}',
                map_fit_bounds: '{{ setting_item('map_fit_bounds') }}',
            },
            routes: {
                login: '{{ route('auth.login') }}',
                register: '{{ route('auth.register') }}',
                contactHost: '{{ url('/contact-host') }}',
                checkout: '{{ is_api() ? route('api.booking.doCheckout') : route('booking.doCheckout') }}'
            },
            module: {
                hotel: '{{ route('hotel.search') }}',
                car: '{{ route('car.search') }}',
                tour: '{{ route('tour.search') }}',
                space: '{{ route('space.search') }}',
                flight: "{{ route('flight.search') }}"
            },
            currentUser: {{ (int) Auth::id() }},
            isAdmin: {{ is_admin() ? 1 : 0 }},
            rtl: {{ setting_item_with_lang('enable_rtl') ? '1' : '0' }},
            markAsRead: '{{ route('core.notification.markAsRead') }}',
            markAllAsRead: '{{ route('core.notification.markAllAsRead') }}',
            loadNotify: '{{ route('core.notification.loadNotify') }}',
            pusher_api_key: '{{ setting_item('pusher_api_key') }}',
            pusher_cluster: '{{ setting_item('pusher_cluster') }}',
        };
        var i18n = {
            warning: "{{ __('Warning') }}",
            success: "{{ __('Success') }}",
        };
        var daterangepickerLocale = {
            "applyLabel": "{{ __('Apply') }}",
            "cancelLabel": "{{ __('Cancel') }}",
            "fromLabel": "{{ __('From') }}",
            "toLabel": "{{ __('To') }}",
            "customRangeLabel": "{{ __('Custom') }}",
            "weekLabel": "{{ __('W') }}",
            "first_day_of_week": {{ setting_item('site_first_day_of_the_weekin_calendar', '1') }},
            "daysOfWeek": [
                "{{ __('Su') }}",
                "{{ __('Mo') }}",
                "{{ __('Tu') }}",
                "{{ __('We') }}",
                "{{ __('Th') }}",
                "{{ __('Fr') }}",
                "{{ __('Sa') }}"
            ],
            "monthNames": [
                "{{ __('January') }}",
                "{{ __('February') }}",
                "{{ __('March') }}",
                "{{ __('April') }}",
                "{{ __('May') }}",
                "{{ __('June') }}",
                "{{ __('July') }}",
                "{{ __('August') }}",
                "{{ __('September') }}",
                "{{ __('October') }}",
                "{{ __('November') }}",
                "{{ __('December') }}"
            ],
        };
    </script>
    <!-- Styles -->
    @yield('head')

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
        }

        .mainslider {
            width: 100%;
            height: 100%;
            background-color: #EBEFEF;
        }

        .slides-container {
            z-index: 1;
        }
    </style>

    <link href="{{ asset('libs/carousel-2/owl.carousel.css') }}" rel="stylesheet">

    @if (setting_item_with_lang('enable_rtl'))
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.0/jquery.min.js"></script>

    <script type="text/javascript" src="{{ \App\Helpers\CodeHelper::withAppUrl('js/jquery_ui.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.18/vue.min.js"></script>

    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>

    <script type="text/javascript" src="{{ \App\Helpers\CodeHelper::withAppUrl('js/jquery.superslides.js') }}"></script>

    <script type="text/javascript" src="{{ asset('libs/ion_rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script src="{{ \App\Helpers\CodeHelper::withAppUrl('libs/fullcalendar/main.js') }}"></script>


    @if (!is_demo_mode())
        {!! setting_item('head_scripts') !!}
        {!! setting_item_with_lang_raw('head_scripts') !!}
    @endif
    @php event(new \Modules\Layout\Events\LayoutEndHead()); @endphp


    <script>
        window.webAlerts = [];
    </script>
    <?php
    $getPageData = \App\Helpers\CodeHelper::getPageData($seo_meta ?? null);
    ?>
    <link href="{{ asset('css/web-alerts.css') }}?v=1.0.01" rel="stylesheet" type="text/css" />


    <link rel="stylesheet" href="{{ asset('css/web.css') }}?v={{ time() }}">

    <script type="text/javascript">
        $(document).ready(function() {
            $('#errordisp').hide();
            $('#searchx').click(function() {
                //alert("dbdbdb");

                if ($('#txtPlaces').val().trim() == "") {

                    $('#errordisp').html("Please Set a Location");
                    $('#errordisp').show();
                    $('#errordisp').fadeOut(5000);
                    return false;

                }

                if ($('#location').val() == "") {
                    $('#errordisp').html("Please Choose a Location from the List");
                    $('#errordisp').show();
                    $('#errordisp').fadeOut(5000);
                    return false;

                }

                if ($('#dpd1').val().trim() != "") {

                    var indate = $('#dpd1').val();
                    if (isDate(indate)) {
                        $('#indate').val(indate);
                    } else {
                        alert("Invalid Date!");
                        return false;
                    }
                }

                if ($('#dpd2').val().trim() != "") {

                    var outdate = $('#dpd2').val();
                    if (isDate(outdate)) {
                        $('#outdate').val(outdate);
                    } else {
                        alert("Invalid date format");
                        return false;
                    }

                }

                if (isNaN($('#guest').val())) {

                    $('#errordisp').html("Number of Guests can be in Numeric Only");
                    $('#errordisp').show();
                    $('#errordisp').fadeOut(5000);
                    return false;

                }

                var guestno = $('#guest').val();
                $('#guests').val(guestno);

                $('form#searchsubmit').submit();

            });
            /*
                 $('#dpd2').on('focus', function() {

                 var nowTemp = new Date();
                 var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

                 var newDate = new Date(now)
                 newDate.setDate(newDate.getDate() + 1);
                 checkout.setValue(newDate);

                 });
                 */
            $('.clrnn').click(function() {

                var value = "";
                $('#dpd1').datepicker('setValue', "");
                $('#dpd2').datepicker('setValue', "");
                $('#dpd1').val("");
                $('#dpd2').val("");

            });

            $('.boxcimage').click(function() {

                var loc = $(this).attr("id");

                var get = loc.split('##');
                var id = get[0];
                var place = get[1];

                if (id.trim() != "" && place.trim() != "") {

                    window.location = 'http://mofront.myoffice.ca/index.php?page=search/rentals/' + id +
                        '/' + place + '';

                }

            });

            $('.middle').click(function() {

                var loc = $(this).attr("id");

                if (loc != undefined) {
                    var get = loc.split('##');
                    var id = get[0];
                    var place = get[1];

                    if (id.trim() != "" && place.trim() != "") {

                        window.location = 'http://mofront.myoffice.ca/index.php?page=search/rentals/' + id +
                            '/' + place + '';

                    }

                }

            });

            function isDate(dateStr) {

                var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
                var matchArray = dateStr.match(datePat);
                // is the format ok?

                if (matchArray == null) {
                    // alert("Please enter date as either mm/dd/yyyy or mm-dd-yyyy.");
                    return false;
                }

                month = matchArray[1];
                // p@rse date into variables
                day = matchArray[3];
                year = matchArray[5];

                if (month < 1 || month > 12) { // check month range
                    // alert("Month must be between 1 and 12.");
                    return false;
                }

                if (day < 1 || day > 31) {
                    // alert("Day must be between 1 and 31.");
                    return false;
                }

                if ((month == 4 || month == 6 || month == 9 || month == 11) && day == 31) {
                    // alert("Month " + month + " doesn`t have 31 days!")
                return false;
            }

            if (month == 2) { // check for february 29th
                var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
                if (day > 29 || (day == 29 && !isleap)) {
                    // alert("February " + year + " doesn`t have " + day + " days!");
                        return false;
                    }
                }
                return true;
                // date is valid
            }

        });

        function logout() {

            $.ajax({

                type: "POST",
                cache: false,

                url: "/logout",

                success: function(data) {
                    //alert(data);
                    if (data == "1") {
                        location.reload(true);
                    }

                }
            });
        }

        function login() {

            var email = $('#username_login').val().trim();
            var password = $('#password_login').val();
            var pass_len = password.length;

            if (email == "") {
                $('#unameerror').html('Please Enter Your Email');

                $('#unameerror').addClass('show');

                setTimeout(function() {
                    $('#unameerror').removeClass('show');
                }, 1700);

            }
            if (password == "") {
                $('#passerror').html('Please Enter Your Password');
                $('#passerror').addClass('show');

                setTimeout(function() {
                    $('#passerror').removeClass('show');
                }, 1700);

                return false;
            }

            /**/
            $.ajax({

                type: "POST",
                cache: false,

                url: "{{ route('login') }}",

                data: {
                    'email': email,
                    'password': password
                },

                success: function(data) {
                    console.log(data);
                    if (data == "1") {

                        $('#unameerror').html('Please Enter Your Email');

                        $('#unameerror').addClass('show');

                        setTimeout(function() {
                            $('#unameerror').removeClass('show');
                        }, 1700);

                        return false;

                    }
                    if (data == "2") {

                        $('#passerror').html('Please Enter Your Password');

                        $('#passerror').addClass('show');

                        setTimeout(function() {
                            $('#passerror').removeClass('show');
                        }, 1700);

                        return false;

                    }
                    if (data.error) {

                        $('#passerror').html('Invalid Email or Password ');

                        $('#passerror').addClass('show');

                        setTimeout(function() {
                            $('#passerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else {
                        window.location.href = "/user/user-dashboard";
                    }

                }
            });
        }

        $(document).ready(function() {
            $("#emailuser").blur(function() {

                var email = $('#emailuser').val().trim();
                //	if(email!="" && isemail
                //alert(email);
                $.ajax({

                    type: "POST",
                    cache: false,

                    url: "http://mofront.myoffice.ca/index.php?page=index/emailexist",

                    data: {
                        'email': email
                    },

                    success: function(data) {
                        //alert(data);

                        if (data == "1") {

                            $('#emailerror').html('This Email already Exists ');

                            $('#emailerror').addClass('show');
                            setTimeout(function() {
                                $('#emailerror').removeClass('show');
                            }, 1700);

                            return false;

                        }

                    }
                });

            })
        });

        function register() {

            var fname = $('#fname').val().trim();

            var lname = $('#lname').val().trim();
            //var email=$('#emailuser').val().trim();
            var email = document.getElementById("emailuser").value;
            var country = $('#country').val().trim();
            var cpassword = $('#cpassword').val();
            var password = $('#password').val();
            var register_as = $('#register_as').val();
            if (fname == "") {

                $('#fnameerror').html('Please Enter Your First Name');

                $('#fnameerror').addClass('show');
                setTimeout(function() {
                    $('#fnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (lname == "") {

                $('#lnameerror').html('Please Enter Your Last Name');

                $('#lnameerror').addClass('show');
                setTimeout(function() {
                    $('#lnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (/^[A-Za-z\s]+$/.test(fname) == false) {
                $('#fnameerror').html('First Name Must Contain only Letters');

                $('#fnameerror').addClass('show');
                setTimeout(function() {
                    $('#fnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (/^[A-Za-z\s]+$/.test(lname) == false) {
                $('#lnameerror').html('Last Name Must Contain only Letters');

                $('#lnameerror').addClass('show');
                setTimeout(function() {
                    $('#lnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (email == "") {

                $('#emailerror').html('Please Enter Your Email');

                $('#emailerror').addClass('show');
                setTimeout(function() {
                    $('#emailerror').removeClass('show');
                }, 1700);

                return false;
            }
            var regex = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;

            if (!regex.test(email)) {
                $('#emailerror').html('Invalid Email');

                $('#emailerror').addClass('show');
                setTimeout(function() {
                    $('#emailerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (country == "") {

                $('#countyerror').html('Please Select a Country');

                $('#countyerror').addClass('show');
                setTimeout(function() {
                    $('#countyerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (password == "") {

                $('#passwrderror').html('Please Enter a Password');

                $('#passwrderror').addClass('show');
                setTimeout(function() {
                    $('#passwrderror').removeClass('show');
                }, 1700);

                return false;
            }
            if (cpassword == "") {

                $('#cpassworderror').html('Please Confirm Your Password');

                $('#cpassworderror').addClass('show');
                setTimeout(function() {
                    $('#cpassworderror').removeClass('show');
                }, 1700);

                return false;
            }
            var min_password = '4';
            var pass_len = password.length;
            var cpass_len = cpassword.length;
            if (pass_len < min_password) {
                $('#passwrderror').html('Minimum Password Length Must be ' + min_password);

                $('#passwrderror').addClass('show');
                setTimeout(function() {
                    $('#passwrderror').removeClass('show');
                }, 1700);

                return false;
            }

            if (cpass_len < min_password) {
                $('#cpassworderror').html('Minimum Password Length Must be ' + min_password);

                $('#cpassworderror').addClass('show');
                setTimeout(function() {
                    $('#cpassworderror').removeClass('show');
                }, 1700);

                return false;
            }
            if (password != cpassword) {
                $('#cpassworderror').html('Password and Confirm password Must be Same');

                $('#cpassworderror').addClass('show');
                setTimeout(function() {
                    $('#cpassworderror').removeClass('show');
                }, 1700);

                return false;
            }
            //var emailid=JSON.stringify(email);

            $.ajax({

                type: "POST",
                cache: false,

                url: bookingCore.routes.register,

                data: {
                    'password': password,
                    'country': country,
                    'first_name': fname,
                    'last_name': lname,
                    'email': email,
                    'register_as': register_as
                },

                success: function(data) {
                    //alert(data);

                    if (data == "1") {

                        $('#fnameerror').html('Please Enter Your First Name');

                        $('#fnameerror').addClass('show');
                        setTimeout(function() {
                            $('#fnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "2") {
                        $('#lnameerror').html('Please Enter Your Last Name');

                        $('#lnameerror').addClass('show');
                        setTimeout(function() {
                            $('#lnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "3") {
                        $('#emailerror').html('Please Enter Your Email');

                        $('#emailerror').addClass('show');
                        setTimeout(function() {
                            $('#emailerror').removeClass('show');
                        }, 1700);

                        return false;
                    } else if (data == "4") {
                        $('#countyerror').html('Please Select a Country');

                        $('#countyerror').addClass('show');
                        setTimeout(function() {
                            $('#countyerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "5") {

                        $('#passwrderror').html('Please Enter a Password');

                        $('#passwrderror').addClass('show');
                        setTimeout(function() {
                            $('#passwrderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "6") {

                        $('#passwrderror').html('Minimum Password Length Must be ' + min_password);

                        $('#passwrderror').addClass('show');
                        setTimeout(function() {
                            $('#passwrderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "7") {

                        $('#cpassworderror').html('Please Confirm Your Password');

                        $('#cpassworderror').addClass('show');
                        setTimeout(function() {
                            $('#cpassworderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "8") {

                        $('#cpassworderror').html('Password and Confirm password Must be Same');

                        $('#cpassworderror').addClass('show');
                        setTimeout(function() {
                            $('#cpassworderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "9") {

                        $('#emailerror').html('Invalid Email');

                        $('#emailerror').addClass('show');
                        setTimeout(function() {
                            $('#emailerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "10") {

                        $('#fnameerror').html('First Name Must Contain only Letters');

                        $('#fnameerror').addClass('show');
                        setTimeout(function() {
                            $('#fnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "11") {

                        $('#lnameerror').html('Last Name Must Contain only Letters');

                        $('#lnameerror').addClass('show');
                        setTimeout(function() {
                            $('#lnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == 12) {
                        $('#cpassworderror').html('Minimum Password Length Must be ' + min_password);

                        $('#cpassworderror').addClass('show');
                        setTimeout(function() {
                            $('#cpassworderror').removeClass('show');
                        }, 1700);

                        return false;
                    } else if (data == 13) {
                        $('#emailerror').html('This Email already Exists ');

                        $('#emailerror').addClass('show');
                        setTimeout(function() {
                            $('#emailerror').removeClass('show');
                        }, 1700);

                        return false;
                    } else {
                        var cx = data.split("-");
                        var id = cx[0];
                        var defaultsatys = cx[1];

                        if (defaultsatys == 2) {

                            $('#regs').hide();
                            //$('#msg1').html('Successfully Registered <p class="robotoregular fontsize13 lgraytext">Your account is currently in Pending. Admin will verify and approve it soon.</p> ');
                            $('#msg1').html(
                                'Successfully Registered <p class="robotoregular fontsize13 lgraytext">Account activation link is sent to your email. please check your email and confirm your account.</p> '
                            );

                            //$('#msg2').html('Your account is currently in Pending. Admin will verify and approve it soon.');

                        } else {

                            $('#regs').hide();
                            $('#msg1').html(
                                'Successfully Registered <p class="robotoregular fontsize13 lgraytext">Thank You for Registering with us. Your account is now active.</p> '
                            );
                            //$('#msg2').html('Thank You for Registering with us. Your account is now active.');

                        }

                        $.ajax({

                            type: "POST",
                            cache: false,
                            url: "http://mofront.myoffice.ca/index.php?page=index/verifyemailaddress",

                            data: {
                                'id': id
                            }

                        });

                        if (defaultsatys == 1) {

                            location.reload(true);
                        }
                    }

                }
            });

        }

        function registerHost() {

            var fname = $('#fnameHost').val().trim();
            var lname = $('#lnameHost').val().trim();
            var email = document.getElementById("emailuserHost").value;
            var country = $('#countryHost').val().trim();
            var cpassword = $('#cpasswordHost').val();
            var password = $('#passwordHost').val();
            var register_as = $('#register_asHost').val();
            if (fname == "") {

                $('#fnameerror').html('Please Enter Your First Name');

                $('#fnameerror').addClass('show');
                setTimeout(function() {
                    $('#fnameerrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (lname == "") {

                $('#lnameerrorHost').html('Please Enter Your Last Name');

                $('#lnameerrorHost').addClass('show');
                setTimeout(function() {
                    $('#lnameerrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (/^[A-Za-z\s]+$/.test(fname) == false) {
                $('#fnameerrorHost').html('First Name Must Contain only Letters');

                $('#fnameerrorHost').addClass('show');
                setTimeout(function() {
                    $('#fnameerrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (/^[A-Za-z\s]+$/.test(lname) == false) {
                $('#lnameerrorHost').html('Last Name Must Contain only Letters');

                $('#lnameerrorHost').addClass('show');
                setTimeout(function() {
                    $('#lnameerrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (email == "") {

                $('#emailerrorHost').html('Please Enter Your Email');

                $('#emailerrorHost').addClass('show');
                setTimeout(function() {
                    $('#emailerrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            var regex = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;

            if (!regex.test(email)) {
                $('#emailerrorHost').html('Invalid Email');

                $('#emailerrorHost').addClass('show');
                setTimeout(function() {
                    $('#emailerrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (country == "") {

                $('#countyerrorHost').html('Please Select a Country');

                $('#countyerrorHost').addClass('show');
                setTimeout(function() {
                    $('#countyerrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (password == "") {

                $('#passwrderrorHost').html('Please Enter a Password');

                $('#passwrderrorHost').addClass('show');
                setTimeout(function() {
                    $('#passwrderrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (cpassword == "") {

                $('#cpassworderrorHost').html('Please Confirm Your Password');

                $('#cpassworderrorHost').addClass('show');
                setTimeout(function() {
                    $('#cpassworderrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            var min_password = '4';
            var pass_len = password.length;
            var cpass_len = cpassword.length;
            if (pass_len < min_password) {
                $('#passwrderrorHost').html('Minimum Password Length Must be ' + min_password);

                $('#passwrderrorHost').addClass('show');
                setTimeout(function() {
                    $('#passwrderrorHost').removeClass('show');
                }, 1700);

                return false;
            }

            if (cpass_len < min_password) {
                $('#cpassworderrorHost').html('Minimum Password Length Must be ' + min_password);

                $('#cpassworderrorHost').addClass('show');
                setTimeout(function() {
                    $('#cpassworderrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            if (password != cpassword) {
                $('#cpassworderrorHost').html('Password and Confirm password Must be Same');

                $('#cpassworderrorHost').addClass('show');
                setTimeout(function() {
                    $('#cpassworderrorHost').removeClass('show');
                }, 1700);

                return false;
            }
            //var emailid=JSON.stringify(email);

            $.ajax({

                type: "POST",
                cache: false,

                url: "{{ \App\Helpers\CodeHelper::withAppUrl('register') }}",

                data: {
                    'password': password,
                    'country': country,
                    'first_name': fname,
                    'last_name': lname,
                    'email': email,
                    'register_as': register_as
                },

                success: function(data) {
                    //alert(data);

                    if (data == "1") {

                        $('#fnameerrorHost').html('Please Enter Your First Name');

                        $('#fnameerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#fnameerrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "2") {
                        $('#lnameerrorHost').html('Please Enter Your Last Name');

                        $('#lnameerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#lnameerrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "3") {
                        $('#emailerrorHost').html('Please Enter Your Email');

                        $('#emailerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#emailerrorHost').removeClass('show');
                        }, 1700);

                        return false;
                    } else if (data == "4") {
                        $('#countyerrorHost').html('Please Select a Country');

                        $('#countyerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#countyerrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "5") {

                        $('#passwrderrorHost').html('Please Enter a Password');

                        $('#passwrderrorHost').addClass('show');
                        setTimeout(function() {
                            $('#passwrderrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "6") {

                        $('#passwrderrorHost').html('Minimum Password Length Must be ' + min_password);

                        $('#passwrderrorHost').addClass('show');
                        setTimeout(function() {
                            $('#passwrderrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "7") {

                        $('#cpassworderrorHost').html('Please Confirm Your Password');

                        $('#cpassworderrorHost').addClass('show');
                        setTimeout(function() {
                            $('#cpassworderrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "8") {

                        $('#cpassworderrorHost').html('Password and Confirm password Must be Same');

                        $('#cpassworderrorHost').addClass('show');
                        setTimeout(function() {
                            $('#cpassworderrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "9") {

                        $('#emailerrorHost').html('Invalid Email');

                        $('#emailerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#emailerrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "10") {

                        $('#fnameerrorHost').html('First Name Must Contain only Letters');

                        $('#fnameerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#fnameerrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "11") {

                        $('#lnameerrorHost').html('Last Name Must Contain only Letters');

                        $('#lnameerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#lnameerrorHost').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == 12) {
                        $('#cpassworderrorHost').html('Minimum Password Length Must be ' + min_password);

                        $('#cpassworderrorHost').addClass('show');
                        setTimeout(function() {
                            $('#cpassworderrorHost').removeClass('show');
                        }, 1700);

                        return false;
                    } else if (data == 13) {
                        $('#emailerrorHost').html('This Email already Exists ');

                        $('#emailerrorHost').addClass('show');
                        setTimeout(function() {
                            $('#emailerrorHost').removeClass('show');
                        }, 1700);

                        return false;
                    } else {
                        var cx = data.split("-");
                        var id = cx[0];
                        var defaultsatys = cx[1];

                        if (defaultsatys == 2) {

                            $('#regs').hide();
                            //$('#msg1').html('Successfully Registered <p class="robotoregular fontsize13 lgraytext">Your account is currently in Pending. Admin will verify and approve it soon.</p> ');
                            $('#msg1').html(
                                'Successfully Registered <p class="robotoregular fontsize13 lgraytext">Account activation link is sent to your email. please check your email and confirm your account.</p> '
                            );

                            //$('#msg2').html('Your account is currently in Pending. Admin will verify and approve it soon.');

                        } else {

                            $('#regs').hide();
                            $('#msg1').html(
                                'Successfully Registered <p class="robotoregular fontsize13 lgraytext">Thank You for Registering with us. Your account is now active.</p> '
                            );
                            //$('#msg2').html('Thank You for Registering with us. Your account is now active.');

                        }

                        $.ajax({

                            type: "POST",
                            cache: false,
                            url: "http://mofront.myoffice.ca/index.php?page=index/verifyemailaddress",

                            data: {
                                'id': id
                            }

                        });

                        if (defaultsatys == 1) {

                            location.reload(true);
                        }
                    }

                }
            });

        }

        function forgotpassword() {
            window.location.href = "{{ \App\Helpers\CodeHelper::withAppUrl('/password/reset') }}";
        }

        function howimage(id) {
            window.location.href = bookingCore.url_root + "?page=help/section/" + id;
        }
    </script>

    <script>
        var bravo_map_data = {
            markers: [],
            map_lat_default: {{ setting_item('space_map_lat_default', '0') }},
            map_lng_default: {{ setting_item('space_map_lng_default', '0') }},
            map_zoom_default: {{ setting_item('space_map_zoom_default', '15') }}
        };
    </script>

</head>

<body class="@yield('bodyClass')">

    <div class="loginwrperoverlay"></div>

    <div class="shareBoxModal modalBox commonModalBox">
        <div class="loouter">
            <div class="lomiddle">
                <div class="loinner col-md-5 col-sm-6 col-lg-4 col-center nopadding bravo-login-form-page">
                    <div class="logibox whitebg fulwidthm left pdg30 modalBox-box">
                        <div class="loginrow fulwidthm left mgnB15 text-center">
                            <div class="shareBoxModalClose closeBtn lgraytext">
                                <i class="fa fa-times"></i>
                            </div>
                            <div class="modalHeader text-center">
                                <h2>Share</h2>
                            </div>
                        </div>
                        <div class="modal-body overflow-hidden">
                            <ul class="share-options">
                                <li>
                                    <a class="facebook"
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ $getPageData['url'] }}&amp;title={{ urlencode($getPageData['title']) }}"
                                        target="_blank" rel="noopener" original-title="{{ __('Facebook') }}">
                                        <i class="fa fa-facebook fa-lg"></i>
                                        <span>{{ __('Facebook') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="twitter"
                                        href="https://twitter.com/share?url={{ $getPageData['url'] }}&amp;title={{ urlencode($getPageData['title']) }}"
                                        target="_blank" rel="noopener" original-title="{{ __('Twitter') }}">
                                        <i class="fa fa-twitter fa-lg"></i>
                                        <span>{{ __('Twitter') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="loginbox modalBox">
        <div class="loouter">
            <div class="lomiddle">
                <div class="loinner col-md-5 col-sm-6 col-lg-4 col-center nopadding bravo-login-form-page">
                    <div class="logibox whitebg fulwidthm left pdg30 login-box">

                        <div class="loginrow fulwidthm left mgnB15 text-center">
                            <div class="registerclose lgraytext">
                                <i class="fa fa-times"></i>
                            </div>
                            <div class="indexlogo text-center">
                                <a href="{{ route('home') }}" class="fulwidthm left"><img
                                        src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/logo_white.png') }}"
                                        alt="MyOffice Logo" title="MyOffice"></a>
                            </div>
                        </div>

                        <div class="modal-body overflow-hidden">
                            @include('Layout::auth/login-form')
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="registerbox modalBox">
        <div class="loouter">
            <div class="lomiddle">
                <div class="loinner ">
                    <div class="registerboxin whitebg fulwidthm left bravo-login-form-page">

                        <div class="registerclose lgraytext">
                            <i class="fa fa-times"></i>
                        </div>
                        <div class="loginrow fulwidthm left mgnB15 text-center">
                            <div class="indexlogo text-center">
                                <a href="{{ route('home') }}" class="fulwidthm left"><img
                                        src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/logo_white.png') }}"
                                        alt="MyOffice Logo" title="MyOffice"></a>
                            </div>
                        </div>

                        <div class="loginrow fulwidthm left mgnB15 text-center josfinsanbold blacktext fontsize24"
                            id="msg1">
                            Register Now <p class="robotoregular fontsize13 lgraytext">
                                <!--	Please Fill Out the Required Fields.--> In order to add your listing you need to
                                create an account first.
                            </p>
                        </div>

                        <div class="modal-body">
                            @include('Layout::auth/register-form')
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="registervendorbox modalBox">
        <div class="loouter">
            <div class="lomiddle">
                <div class="loinner ">
                    <div class="registerboxin whitebg fulwidthm left bravo-login-form-page">

                        <div class="registervendorclose lgraytext">
                            <i class="fa fa-times"></i>
                        </div>
                        <div class="loginrow fulwidthm left mgnB15 text-center bravo-login-form-page">
                            <div class="indexlogo text-center">
                                <a href="{{ route('home') }}" class="fulwidthm left">
                                    <img src="{{ \App\Helpers\CodeHelper::withAppUrl('/images/logo_white.png') }}"
                                        alt="MyOffice Logo" title="MyOffice"></a>
                            </div>
                        </div>

                        <div class="loginrow fulwidthm left mgnB15 text-center josfinsanbold blacktext fontsize24"
                            id="msg1">
                            Register as Host <p class="robotoregular fontsize13 lgraytext"> In order to add your
                                listing you
                                need to create an account first.
                            </p>
                        </div>

                        <div class="modal-body">
                            @include('Layout::auth/become-host-form')
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade login" id="contactHost" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content relative">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Contact Host') }}</h4>
                    <span class="c-pointer" data-dismiss="modal" aria-label="Close">
                        <i class="input-icon field-icon fa">
                            <img src="{{ url('images/ico_close.svg') }}" alt="close">
                        </i>
                    </span>
                </div>
                <div class="modal-body">
                    <form class="form bravo-contact-host themeForm" method="post">
                        @csrf
                        <input type="hidden" name="space" id="contactHostSpaceId">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Name
                                        <span class=" required">*</span></label>
                                    <input type="text" class="form-control" name="name" autocomplete="off"
                                        placeholder="{{ __('Name') }}">
                                    {{-- <i class="input-icon field-icon icofont-waiter-alt"></i> --}}
                                    <span class="invalid-feedback error error-name"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Phone<span
                                    class=" required">*</span></label>
                            <input type="text" class="form-control" name="phone" autocomplete="off"
                                placeholder="{{ __('Phone') }}">
                            {{-- <i class="input-icon field-icon icofont-ui-touch-phone"></i> --}}
                            <span class="invalid-feedback error error-phone"></span>
                        </div>

                        <div class="form-group">
                            <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Email Address
                                <span class=" required">*</span></label>
                            <input type="email" class="form-control" name="email" autocomplete="off"
                                placeholder="{{ __('Email address') }}">
                            {{-- <i class="input-icon field-icon icofont-mail"></i> --}}
                            <span class="invalid-feedback error error-email"></span>
                        </div>

                        <div class="form-group">
                            <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Subject
                                <span class=" required">*</span></label>
                            <input type="text" class="form-control" name="subject" autocomplete="off"
                                placeholder="{{ __('Subject') }}">
                            {{-- <i class="input-icon field-icon icofont-mail"></i> --}}
                            <span class="invalid-feedback error error-subject"></span>
                        </div>


                        <div class="form-group password">
                            <label class="formlabel fontsize13 robotoregular graytext font-weight-bold">Message
                                <span class=" required">*</span></label>
                            <textarea style="height: auto;" class="form-control" name="message" autocomplete="off"
                                placeholder="{{ __('Enter message') }}" cols="30" rows="5"></textarea>
                            {{-- <i class="input-icon field-icon icofont-ui-password"></i> --}}
                            {{-- <a href="javascript:;" class="togglePassField"><i class="input-icon icofont-eye"></i></a> --}}
                            <span class="invalid-feedback error error-message"></span>
                        </div>
                        <div class="error message-error invalid-feedback"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-submit btn-block">
                                {{ __('Submit') }}
                                <span style="display: none;" class="spinner-grow spinner-grow-sm icon-loading"
                                    role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (!is_api())
        @include('Layout::Home.parts.header')
    @endif

    @yield('content')

    @include('admin.message')

    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
    @include('Layout::Home.parts.footer-main')


    @php event(new \Modules\Layout\Events\LayoutEndBody()); @endphp

    @yield('footerLastExtra')

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>

    @yield('customJs')

    <script>
        $(document).on("click", ".contactHostModalTrigger", function() {
            $("#contactHostSpaceId").val($(this).attr("data-space"));
            $('#contactHost').modal("show");
        });
    </script>

    <script>
        @guest
        window.IS_LOGGED_IN = false;
        @else
            window.IS_LOGGED_IN = true;
        @endguest
    </script>
    <script src="{{ asset('js/web-alerts.js') }}?v={{ time() }}"></script>

</body>

</html>
