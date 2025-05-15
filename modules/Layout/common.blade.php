<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{$html_class ?? ''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php event(new \Modules\Layout\Events\LayoutBeginHead()); @endphp
    @php
        $favicon = setting_item('site_favicon');
    @endphp
    @if($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if(!empty($file))
            <link rel="icon" type="{{$file['file_type']}}" href="{{asset('uploads/'.$file['file_path'])}}"/>
        @else:
        <link rel="icon" type="image/png" href="{{url('images/favicon.png')}}"/>
        @endif
    @endif
    @include('Layout::Home.parts.seo-meta')
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/reset.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate/animate.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}"/>
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">
    <link href="{{ asset('dist/frontend/css/app.css?_ver='.config('app.version')) }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/daterange/daterangepicker.css") }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href={{ asset("libs/fotorama/fotorama.css") }}">
    <link rel=" stylesheet
    " href="{{ asset('css/hotel.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/space.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-style.css') }}"/>
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/superslides.css') }}">
    <link rel="stylesheet" href="{{ asset('css/simpledatepicker/datepicker.css') }}" type="text/css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css"
          type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Josefin+Sans:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
    <link
        href="https://fonts.googleapis.com/css?family=Eczar:700|Work+Sans:300,400,500,600,700|Montserrat:300,400,500,600,700"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/jquery_ui.js') }}"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/iefix.css" type="text/css') }}">
    <link rel="stylesheet" href="{{ asset('css/sangaslider/ie8.css') }}" type="text/css">
    <script src="{{ asset('js/html5shiv.js') }}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('libs/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('libs/fullcalendar/main.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/toastr/toastr.min.css') }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel='stylesheet' id='google-font-css-css'
          href='https://fonts.googleapis.com/css?family=Poppins%3A300%2C400%2C500%2C600' type='text/css' media='all'/>
    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}
    <script>
        var bookingCore = {
            url: '{{url( app_get_locale() )}}',
            url_root: '{{ url('') }}',
            booking_decimals: {{(int)get_current_currency('currency_no_decimal',2)}},
            thousand_separator: '{{get_current_currency('currency_thousand')}}',
            decimal_separator: '{{get_current_currency('currency_decimal')}}',
            currency_position: '{{get_current_currency('currency_format')}}',
            currency_symbol: '{{currency_symbol()}}',
            currency_rate: '{{get_current_currency('rate',1)}}',
            date_format: '{{get_moment_date_format()}}',
            map_provider: '{{setting_item('map_provider')}}',
            map_gmap_key: '{{setting_item('map_gmap_key')}}',
            map_options: {
                map_lat_default: '{{setting_item('map_lat_default')}}',
                map_lng_default: '{{setting_item('map_lng_default')}}',
                map_clustering: '{{setting_item('map_clustering')}}',
                map_fit_bounds: '{{setting_item('map_fit_bounds')}}',
            },
            routes: {
                login: '{{route('auth.login')}}',
                register: '{{route('auth.register')}}',
                checkout: '{{is_api() ? route('api.booking.doCheckout') : route('booking.doCheckout')}}'
            },
            module: {
                hotel: '{{route('hotel.search')}}',
                car: '{{route('car.search')}}',
                tour: '{{route('tour.search')}}',
                space: '{{route('space.search')}}',
                flight: "{{route('flight.search')}}"
            },
            currentUser: {{(int)Auth::id()}},
            isAdmin: {{is_admin() ? 1 : 0}},
            rtl: {{ setting_item_with_lang('enable_rtl') ? "1" : "0" }},
            markAsRead: '{{route('core.notification.markAsRead')}}',
            markAllAsRead: '{{route('core.notification.markAllAsRead')}}',
            loadNotify: '{{route('core.notification.loadNotify')}}',
            pusher_api_key: '{{setting_item("pusher_api_key")}}',
            pusher_cluster: '{{setting_item("pusher_cluster")}}',
        };
        var i18n = {
            warning: "{{__("Warning")}}",
            success: "{{__("Success")}}",
        };
        var daterangepickerLocale = {
            "applyLabel": "{{__('Apply')}}",
            "cancelLabel": "{{__('Cancel')}}",
            "fromLabel": "{{__('From')}}",
            "toLabel": "{{__('To')}}",
            "customRangeLabel": "{{__('Custom')}}",
            "weekLabel": "{{__('W')}}",
            "first_day_of_week": {{ setting_item("site_first_day_of_the_weekin_calendar","1") }},
            "daysOfWeek": [
                "{{__('Su')}}",
                "{{__('Mo')}}",
                "{{__('Tu')}}",
                "{{__('We')}}",
                "{{__('Th')}}",
                "{{__('Fr')}}",
                "{{__('Sa')}}"
            ],
            "monthNames": [
                "{{__('January')}}",
                "{{__('February')}}",
                "{{__('March')}}",
                "{{__('April')}}",
                "{{__('May')}}",
                "{{__('June')}}",
                "{{__('July')}}",
                "{{__('August')}}",
                "{{__('September')}}",
                "{{__('October')}}",
                "{{__('November')}}",
                "{{__('December')}}"
            ],
        };


    </script>
    <!-- Styles -->
    @yield('head')
    {{--Custom Style--}}
    <script type="text/javascript">
        $(document).ready(function () {

            if (window.location.href.indexOf("rental/details") > -1) {

            } else {
                localStorage.removeItem('time_array');
            }

            google.maps.event.addDomListener(window, 'load', function () {
                var options = {
                    types: ['(regions)'],
                    componentRestrictions: {country: ["us", "ca"]}
                };
                var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'), options);
                google.maps.event.addListener(places, 'place_changed', function () {
                    var place = places.getPlace();
                    var address = place.formatted_address;
                    var latitude = place.geometry.location.lat();
                    var longitude = place.geometry.location.lng();
                    var fulllocation = document.getElementById('txtPlaces').value;
                    $('#location').val(fulllocation);
                    $('#address').val(address);
                    $('#lat').val(latitude);
                    $('#long').val(longitude);

                    if ($('#dpd1').val().trim() == "") {

                        $('#dpd1').focus();

                    } else if ($('#dpd2').val().trim() == "") {

                        //$('#dpd2').focus();

                    }

                    var geocoder = new google.maps.Geocoder;
                    geocoder.geocode({
                        'address': address
                    }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            result = results[0].address_components;
                            var info = [];
                            for (var i = 0; i < result.length; ++i) {
                                if (result[i].types[0] == "route") {
                                    var stas = result[i].long_name;
                                }
                                if (result[i].types[0] == "administrative_area_level_1") {
                                    var state = result[i].long_name;
                                    if (state != undefined && state != "") {
                                        state = state.trim();
                                    }
                                }
                                if (result[i].types[0] == "locality") {
                                    var city = result[i].long_name;
                                    if (city != undefined && city != "") {
                                        city = city.trim();
                                    }
                                }
                                if (result[i].types[0] == "country") {
                                    var country = result[i].long_name;
                                    if (country != undefined && country != "") {
                                        country = country.trim();
                                    }

                                }

                            }

                            if (city != undefined && city != "") {
                                $('#searchcity').val(city);
                            }
                            if (state != undefined && state != "") {
                                $('#searchstate').val(state);
                            }

                        }
                    });

                });
            });

            $('#txtPlaces').click(function () {

                $('#errordisp').hide();

                $('#txtPlaces').val("");

                $('#location').val("");
                $('#address').val("");
                $('#lat').val("");
                $('#long').val("");

            });

        });

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#errordisp').hide();
            $('#searchx').click(function () {
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
            $('.clrnn').click(function () {

                var value = "";
                $('#dpd1').datepicker('setValue', "");
                $('#dpd2').datepicker('setValue', "");
                $('#dpd1').val("");
                $('#dpd2').val("");

            });

            $('.boxcimage').click(function () {

                var loc = $(this).attr("id");

                var get = loc.split('##');
                var id = get[0];
                var place = get[1];

                if (id.trim() != "" && place.trim() != "") {

                    window.location = 'http://mofront.myoffice.ca/index.php?page=search/rentals/' + id + '/' + place + '';

                }

            });

            $('.middle').click(function () {

                var loc = $(this).attr("id");

                if (loc != undefined) {
                    var get = loc.split('##');
                    var id = get[0];
                    var place = get[1];

                    if (id.trim() != "" && place.trim() != "") {

                        window.location = 'http://mofront.myoffice.ca/index.php?page=search/rentals/' + id + '/' + place + '';

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

                if (month < 1 || month > 12) {// check month range
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

                if (month == 2) {// check for february 29th
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

                success: function (data) {
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

                setTimeout(function () {
                    $('#unameerror').removeClass('show');
                }, 1700);

            }
            if (password == "") {
                $('#passerror').html('Please Enter Your Password');
                $('#passerror').addClass('show');

                setTimeout(function () {
                    $('#passerror').removeClass('show');
                }, 1700);

                return false;
            }

            /**/
            $.ajax({

                type: "POST",
                cache: false,

                url: "/login",

                data: {
                    'email': email,
                    'password': password
                },

                success: function (data) {
                    console.log(data);
                    if (data == "1") {

                        $('#unameerror').html('Please Enter Your Email');

                        $('#unameerror').addClass('show');

                        setTimeout(function () {
                            $('#unameerror').removeClass('show');
                        }, 1700);

                        return false;

                    }
                    if (data == "2") {

                        $('#passerror').html('Please Enter Your Password');

                        $('#passerror').addClass('show');

                        setTimeout(function () {
                            $('#passerror').removeClass('show');
                        }, 1700);

                        return false;

                    }
                    if (data == "3") {
                        //location.reload(true);
                        window.location.href = "http://myoffice.mybackpocket.co/user/dashboard";
                    }
                    if (data.error) {

                        $('#passerror').html('Invalid Email or Password ');

                        $('#passerror').addClass('show');

                        setTimeout(function () {
                            $('#passerror').removeClass('show');
                        }, 1700);

                        return false;

                    }

                }
            });
        }

        $(document).ready(function () {
            $("#emailuser").blur(function () {

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

                    success: function (data) {
                        //alert(data);

                        if (data == "1") {

                            $('#emailerror').html('This Email already Exists ');

                            $('#emailerror').addClass('show');
                            setTimeout(function () {
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
            if (fname == "") {

                $('#fnameerror').html('Please Enter Your First Name');

                $('#fnameerror').addClass('show');
                setTimeout(function () {
                    $('#fnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (lname == "") {

                $('#lnameerror').html('Please Enter Your Last Name');

                $('#lnameerror').addClass('show');
                setTimeout(function () {
                    $('#lnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (/^[A-Za-z\s]+$/.test(fname) == false) {
                $('#fnameerror').html('First Name Must Contain only Letters');

                $('#fnameerror').addClass('show');
                setTimeout(function () {
                    $('#fnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (/^[A-Za-z\s]+$/.test(lname) == false) {
                $('#lnameerror').html('Last Name Must Contain only Letters');

                $('#lnameerror').addClass('show');
                setTimeout(function () {
                    $('#lnameerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (email == "") {

                $('#emailerror').html('Please Enter Your Email');

                $('#emailerror').addClass('show');
                setTimeout(function () {
                    $('#emailerror').removeClass('show');
                }, 1700);

                return false;
            }
            var regex = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;

            if (!regex.test(email)) {
                $('#emailerror').html('Invalid Email');

                $('#emailerror').addClass('show');
                setTimeout(function () {
                    $('#emailerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (country == "") {

                $('#countyerror').html('Please Select a Country');

                $('#countyerror').addClass('show');
                setTimeout(function () {
                    $('#countyerror').removeClass('show');
                }, 1700);

                return false;
            }
            if (password == "") {

                $('#passwrderror').html('Please Enter a Password');

                $('#passwrderror').addClass('show');
                setTimeout(function () {
                    $('#passwrderror').removeClass('show');
                }, 1700);

                return false;
            }
            if (cpassword == "") {

                $('#cpassworderror').html('Please Confirm Your Password');

                $('#cpassworderror').addClass('show');
                setTimeout(function () {
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
                setTimeout(function () {
                    $('#passwrderror').removeClass('show');
                }, 1700);

                return false;
            }

            if (cpass_len < min_password) {
                $('#cpassworderror').html('Minimum Password Length Must be ' + min_password);

                $('#cpassworderror').addClass('show');
                setTimeout(function () {
                    $('#cpassworderror').removeClass('show');
                }, 1700);

                return false;
            }
            if (password != cpassword) {
                $('#cpassworderror').html('Password and Confirm password Must be Same');

                $('#cpassworderror').addClass('show');
                setTimeout(function () {
                    $('#cpassworderror').removeClass('show');
                }, 1700);

                return false;
            }
            //var emailid=JSON.stringify(email);

            $.ajax({

                type: "POST",
                cache: false,

                url: "http://mofront.myoffice.ca/index.php?page=index/registerprocess",

                data: {
                    'pword': password,
                    'cpword': cpassword,
                    'country': country,
                    'fname': fname,
                    'lname': lname,
                    'email': email
                },

                success: function (data) {
                    //alert(data);

                    if (data == "1") {

                        $('#fnameerror').html('Please Enter Your First Name');

                        $('#fnameerror').addClass('show');
                        setTimeout(function () {
                            $('#fnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "2") {
                        $('#lnameerror').html('Please Enter Your Last Name');

                        $('#lnameerror').addClass('show');
                        setTimeout(function () {
                            $('#lnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "3") {
                        $('#emailerror').html('Please Enter Your Email');

                        $('#emailerror').addClass('show');
                        setTimeout(function () {
                            $('#emailerror').removeClass('show');
                        }, 1700);

                        return false;
                    } else if (data == "4") {
                        $('#countyerror').html('Please Select a Country');

                        $('#countyerror').addClass('show');
                        setTimeout(function () {
                            $('#countyerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "5") {

                        $('#passwrderror').html('Please Enter a Password');

                        $('#passwrderror').addClass('show');
                        setTimeout(function () {
                            $('#passwrderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "6") {

                        $('#passwrderror').html('Minimum Password Length Must be ' + min_password);

                        $('#passwrderror').addClass('show');
                        setTimeout(function () {
                            $('#passwrderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "7") {

                        $('#cpassworderror').html('Please Confirm Your Password');

                        $('#cpassworderror').addClass('show');
                        setTimeout(function () {
                            $('#cpassworderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "8") {

                        $('#cpassworderror').html('Password and Confirm password Must be Same');

                        $('#cpassworderror').addClass('show');
                        setTimeout(function () {
                            $('#cpassworderror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "9") {

                        $('#emailerror').html('Invalid Email');

                        $('#emailerror').addClass('show');
                        setTimeout(function () {
                            $('#emailerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "10") {

                        $('#fnameerror').html('First Name Must Contain only Letters');

                        $('#fnameerror').addClass('show');
                        setTimeout(function () {
                            $('#fnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == "11") {

                        $('#lnameerror').html('Last Name Must Contain only Letters');

                        $('#lnameerror').addClass('show');
                        setTimeout(function () {
                            $('#lnameerror').removeClass('show');
                        }, 1700);

                        return false;

                    } else if (data == 12) {
                        $('#cpassworderror').html('Minimum Password Length Must be ' + min_password);

                        $('#cpassworderror').addClass('show');
                        setTimeout(function () {
                            $('#cpassworderror').removeClass('show');
                        }, 1700);

                        return false;
                    } else if (data == 13) {
                        $('#emailerror').html('This Email already Exists ');

                        $('#emailerror').addClass('show');
                        setTimeout(function () {
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
                            $('#msg1').html('Successfully Registered <p class="robotoregular fontsize13 lgraytext">Account activation link is sent to your email. please check your email and confirm your account.</p> ');

                            //$('#msg2').html('Your account is currently in Pending. Admin will verify and approve it soon.');

                        } else {

                            $('#regs').hide();
                            $('#msg1').html('Successfully Registered <p class="robotoregular fontsize13 lgraytext">Thank You for Registering with us. Your account is now active.</p> ');
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
            window.location.href = "http://myoffice.mybackpocket.co/password/reset";
        }

        function howimage(id) {
            window.location.href = "http://myoffice.mybackpocket.co/index.php?page=help/section/" + id;
        }
    </script>
    <style>
        html, body {
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
    @if(setting_item_with_lang('enable_rtl'))
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif
    @if(!is_demo_mode())
        {!! setting_item('head_scripts') !!}
        {!! setting_item_with_lang_raw('head_scripts') !!}
    @endif
    @php event(new \Modules\Layout\Events\LayoutEndHead()); @endphp
</head>
<body>
<div class="loginwrperoverlay"></div>
<div class="loginbox">
    <div class="loouter">
        <div class="lomiddle">
            <div class="loinner col-md-5 col-sm-6 col-lg-4 col-center nopadding">
                <div class="lobox whitebg fulwidthm left pdg30 login-box">
                    <div class="registerclose lgraytext">
                        <i class="fa fa-times"></i>
                    </div>
                    <div class="loginrow fulwidthm left mgnB15 text-center">
                        <div class="indexlogo text-center">
                            <a href="index.html" class="fulwidthm left"><img src="{{asset('images/logo_white.png')}}"
                                                                             alt="MyOffice Logo"
                                                                             title="MyOffice"></a>
                        </div>
                    </div>
                    <form class="bravo-form-login" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="loginrow fulwidthm left josfinsanbold graytext mgnB15">
                            <label class="formlabel fontsize13 robotoregular graytext">Email</label><span
                                class=" required">*</span>
                            <input type="email" id="username_login" name="email"
                                   class="cmnfrminput fulwidthm inputiconpdng" placeholder="Email">
                            <span class="loginicons"><i class="fa fa-user"></i></span>
                            <div class="erorshow" id="unameerror"></div>
                        </div>
                        <div class="loginrow fulwidthm left josfinsanbold graytext mgnB15">
                            <label class="formlabel fontsize13 robotoregular graytext">Password</label><span
                                class=" required">*</span>
                            <input type="password" id="password_login" name="password"
                                   class="cmnfrminput  fulwidthm inputiconpdng"
                                   placeholder="Password">
                            <span class="loginicons"><i class="fa fa-lock"></i></span>
                            <div class="erorshow" id="passerror"></div>
                        </div>

                        <div class="loginrow fulwidthm left josfinsanbold graytext mgnB15">
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15 registerBtntx">
                                <button class=" btn btn-success whitetext fulwidthm font-size14 robotomedium"
                                        onClick="login()"
                                        type="submit">
                                    Log In
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="fulwidthm forget-signup">
                        <div class=" loginrow   robotoregular fontsize12 graytext mgnB15">
                            <a class="greentext frgtpaswrdclick" onClick="forgotpassword()">Forgot password </a>
                        </div>
                        <div class=" loginrow graytext mgnB15 icon-center">
                            <i class="greentext ">|</i>
                        </div>
                        <div class="loginrow   robotoregular fontsize12 graytext mgnB15">
                            <a class="greentext signupclick">Sign Up</a>
                        </div>
                    </div>
                    @if(setting_item('facebook_enable') or setting_item('google_enable'))
                        <div class="socialAuthStages">
                            <div
                                class="loginrow orPlx fulwidthm left josfinsanregular fontsize16 graytext mgnB15 text-center">
                                <span>or</span>
                            </div>
                            @if(setting_item('facebook_enable'))
                                <div
                                    class="loginrow fbLoginBtn fulwidthm left josfinsanregular fontsize16 graytext mgnB15 text-center">
                                    <a href="{{url('/social-login/facebook')}}">
                                        <div class="s-wrapper">
                                            <svg viewBox="0 0 32 32" role="presentation" aria-hidden="true"
                                                 focusable="false"
                                                 style="height: 18px; width: 18px; display: block; fill: currentcolor;">
                                                <path
                                                    d="m8 14.41v-4.17c0-.42.35-.81.77-.81h2.52v-2.08c0-4.84 2.48-7.31 7.42-7.35 1.65 0 3.22.21 4.69.64.46.14.63.42.6.88l-.56 4.06c-.04.18-.14.35-.32.53-.21.11-.42.18-.63.14-.88-.25-1.78-.35-2.8-.35-1.4 0-1.61.28-1.61 1.73v1.8h4.52c.42 0 .81.42.81.88l-.35 4.17c0 .42-.35.71-.77.71h-4.21v16c0 .42-.35.81-.77.81h-5.21c-.42 0-.8-.39-.8-.81v-16h-2.52a.78.78 0 0 1 -.78-.78"
                                                    fill-rule="evenodd"></path>
                                            </svg>
                                            <span>Log in With Facebook</span>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @if(setting_item('google_enable'))
                                <div
                                    class="loginrow gmailLoginBtn fulwidthm left josfinsanregular fontsize16 graytext mgnB15 text-center">
                                    <a href="{{url('social-login/google')}}">
                                        <div class="s-wrapper">
                                            <svg viewBox="0 0 18 18" role="presentation" aria-hidden="true"
                                                 focusable="false"
                                                 style="height: 18px; width: 18px; display: block;">
                                                <g fill="none" fill-rule="evenodd">
                                                    <path
                                                        d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z"
                                                        fill="#EA4335"></path>
                                                    <path
                                                        d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.1.83-.64 2.08-1.84 2.92l2.84 2.2c1.7-1.57 2.68-3.88 2.68-6.62z"
                                                        fill="#4285F4"></path>
                                                    <path
                                                        d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z"
                                                        fill="#FBBC05"></path>
                                                    <path
                                                        d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.84-2.2c-.76.53-1.78.9-3.12.9-2.38 0-4.4-1.57-5.12-3.74L.97 13.04C2.45 15.98 5.48 18 9 18z"
                                                        fill="#34A853"></path>
                                                    <path d="M0 0h18v18H0V0z"></path>
                                                </g>
                                            </svg>
                                            <span>Log in With Google</span>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
<div class="registerbox">
    <div class="loouter">
        <div class="lomiddle">
            <div class="loinner ">

                <div class="registerboxin whitebg fulwidthm left  ">

                    <div class="registerclose lgraytext">
                        <i class="fa fa-times"></i>
                    </div>
                    <div class="loginrow fulwidthm left mgnB15 text-center">
                        <div class="indexlogo text-center">
                            <a href="index.html" class="fulwidthm left"><img src="{{url('images/logo_white.png')}}"
                                                                             alt="MyOffice Logo"
                                                                             title="MyOffice"></a>
                        </div>
                    </div>
                    <div class="loginrow fulwidthm left mgnB15 text-center josfinsanbold blacktext fontsize24"
                         id="msg1">
                        Register Now <p class="robotoregular fontsize13 lgraytext">
                            <!--	Please Fill Out the Required Fields.--> In order to add your listing you need to
                            create an account first.
                        </p>
                    </div>
                    <div id="regs">
                        <div class="loginrow fulwidthm left josfinsanbold graytext ">
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15">
                                <label class="formlabel fontsize13 robotoregular graytext"> First Name <span
                                        class=" required">*</span></label>
                                <input id="fname" type="text" class="cmnfrminput fulwidthm ">
                                <!-- errorinput -->
                                <div class="erorshow" id="fnameerror"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15">
                                <label class="formlabel fontsize13 robotoregular graytext">Last Name <span
                                        class=" required">*</span></label>
                                <input type="text" id="lname" class="cmnfrminput fulwidthm ">

                                <div class="erorshow" id="lnameerror"></div>
                            </div>
                        </div>
                        <div class="loginrow fulwidthm left josfinsanbold graytext ">
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15">
                                <label class="formlabel fontsize13 robotoregular graytext"> Email <span
                                        class=" required">*</span></label>
                                <input type="email" id="emailuser" name="emailuser" class="cmnfrminput fulwidthm ">

                                <div class="erorshow" id="emailerror"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15">
                                <label class="formlabel fontsize13 robotoregular graytext">Country of Residence
                                    <span class=" required">*</span></label>
                                <select id="country" class="formselect" style="width:100%"
                                        data-placeholder="Select a Country">
                                        <option value="39" selected="selected">Canada</option>
                                        <option value="227">United States</option>
                                    <option value="1">Afghanistan</option>
                                    <option value="2">Albania</option>
                                    <option value="3">Algeria</option>
                                    <option value="4">American Samoa</option>
                                    <option value="5">Andorra</option>
                                    <option value="6">Angola</option>
                                    <option value="7">Anguilla</option>
                                    <option value="8">Antarctica</option>
                                    <option value="9">Antigua and Barbuda</option>
                                    <option value="10">Argentina</option>
                                    <option value="11">Armenia</option>
                                    <option value="12">Aruba</option>
                                    <option value="13">Australia</option>
                                    <option value="14">Austria</option>
                                    <option value="15">Azerbaijan</option>
                                    <option value="16">Bahamas</option>
                                    <option value="17">Bahrain</option>
                                    <option value="18">Bangladesh</option>
                                    <option value="19">Barbados</option>
                                    <option value="20">Belarus</option>
                                    <option value="21">Belgium</option>
                                    <option value="22">Belize</option>
                                    <option value="23">Benin</option>
                                    <option value="24">Bermuda</option>
                                    <option value="25">Bhutan</option>
                                    <option value="26">Bolivia</option>
                                    <option value="27">Bosnia and Herzegovina</option>
                                    <option value="28">Botswana</option>
                                    <option value="29">Bouvet Island</option>
                                    <option value="30">Brazil</option>
                                    <option value="31">British Indian Ocean Territory</option>
                                    <option value="32">British Virgin Islands</option>
                                    <option value="33">Brunei</option>
                                    <option value="34">Bulgaria</option>
                                    <option value="35">Burkina Faso</option>
                                    <option value="36">Burundi</option>
                                    <option value="37">Cambodia</option>
                                    <option value="38">Cameroon</option>
                                    <option value="40">Cape Verde</option>
                                    <option value="41">Cayman Islands</option>
                                    <option value="42">Central African Republic</option>
                                    <option value="43">Chad</option>
                                    <option value="44">Chile</option>
                                    <option value="45">China</option>
                                    <option value="46">Christmas Island</option>
                                    <option value="47">Cocos Islands</option>
                                    <option value="48">Colombia</option>
                                    <option value="49">Comoros</option>
                                    <option value="50">Cook Islands</option>
                                    <option value="51">Costa Rica</option>
                                    <option value="52">Croatia</option>
                                    <option value="53">Cuba</option>
                                    <option value="54">Cyprus</option>
                                    <option value="55">Czech Republic</option>
                                    <option value="56">Democratic Republic of the Congo</option>
                                    <option value="57">Denmark</option>
                                    <option value="58">Djibouti</option>
                                    <option value="59">Dominica</option>
                                    <option value="60">Dominican Republic</option>
                                    <option value="61">East Timor</option>
                                    <option value="62">Ecuador</option>
                                    <option value="63">Egypt</option>
                                    <option value="64">El Salvador</option>
                                    <option value="65">Equatorial Guinea</option>
                                    <option value="66">Eritrea</option>
                                    <option value="67">Estonia</option>
                                    <option value="68">Ethiopia</option>
                                    <option value="69">Falkland Islands</option>
                                    <option value="70">Faroe Islands</option>
                                    <option value="71">Fiji</option>
                                    <option value="72">Finland</option>
                                    <option value="73">France</option>
                                    <option value="74">French Guiana</option>
                                    <option value="75">French Polynesia</option>
                                    <option value="76">French Southern Territories</option>
                                    <option value="77">Gabon</option>
                                    <option value="78">Gambia</option>
                                    <option value="79">Georgia</option>
                                    <option value="80">Germany</option>
                                    <option value="81">Ghana</option>
                                    <option value="82">Gibraltar</option>
                                    <option value="83">Greece</option>
                                    <option value="84">Greenland</option>
                                    <option value="85">Grenada</option>
                                    <option value="86">Guadeloupe</option>
                                    <option value="87">Guam</option>
                                    <option value="88">Guatemala</option>
                                    <option value="89">Guinea</option>
                                    <option value="90">Guinea-Bissau</option>
                                    <option value="91">Guyana</option>
                                    <option value="92">Haiti</option>
                                    <option value="93">Heard Island and McDonald Islands</option>
                                    <option value="94">Honduras</option>
                                    <option value="95">Hong Kong</option>
                                    <option value="96">Hungary</option>
                                    <option value="97">Iceland</option>
                                    <option value="98">India</option>
                                    <option value="99">Indonesia</option>
                                    <option value="100">Iran</option>
                                    <option value="101">Iraq</option>
                                    <option value="102">Ireland</option>
                                    <option value="103">Israel</option>
                                    <option value="104">Italy</option>
                                    <option value="105">Ivory Coast</option>
                                    <option value="106">Jamaica</option>
                                    <option value="107">Japan</option>
                                    <option value="108">Jordan</option>
                                    <option value="109">Kazakhstan</option>
                                    <option value="110">Kenya</option>
                                    <option value="111">Kiribati</option>
                                    <option value="112">Kuwait</option>
                                    <option value="113">Kyrgyzstan</option>
                                    <option value="114">Laos</option>
                                    <option value="115">Latvia</option>
                                    <option value="116">Lebanon</option>
                                    <option value="117">Lesotho</option>
                                    <option value="118">Liberia</option>
                                    <option value="119">Libya</option>
                                    <option value="120">Liechtenstein</option>
                                    <option value="121">Lithuania</option>
                                    <option value="122">Luxembourg</option>
                                    <option value="123">Macao</option>
                                    <option value="124">Macedonia</option>
                                    <option value="125">Madagascar</option>
                                    <option value="126">Malawi</option>
                                    <option value="127">Malaysia</option>
                                    <option value="128">Maldives</option>
                                    <option value="129">Mali</option>
                                    <option value="130">Malta</option>
                                    <option value="131">Marshall Islands</option>
                                    <option value="132">Martinique</option>
                                    <option value="133">Mauritania</option>
                                    <option value="134">Mauritius</option>
                                    <option value="135">Mayotte</option>
                                    <option value="136">Mexico</option>
                                    <option value="137">Micronesia</option>
                                    <option value="138">Moldova</option>
                                    <option value="139">Monaco</option>
                                    <option value="140">Mongolia</option>
                                    <option value="141">Montserrat</option>
                                    <option value="142">Morocco</option>
                                    <option value="143">Mozambique</option>
                                    <option value="144">Myanmar</option>
                                    <option value="145">Namibia</option>
                                    <option value="146">Nauru</option>
                                    <option value="147">Nepal</option>
                                    <option value="148">Netherlands</option>
                                    <option value="149">Netherlands Antilles</option>
                                    <option value="150">New Caledonia</option>
                                    <option value="151">New Zealand</option>
                                    <option value="152">Nicaragua</option>
                                    <option value="153">Niger</option>
                                    <option value="154">Nigeria</option>
                                    <option value="155">Niue</option>
                                    <option value="156">Norfolk Island</option>
                                    <option value="157">North Korea</option>
                                    <option value="158">Northern Mariana Islands</option>
                                    <option value="159">Norway</option>
                                    <option value="160">Oman</option>
                                    <option value="161">Pakistan</option>
                                    <option value="162">Palau</option>
                                    <option value="163">Palestinian Territory</option>
                                    <option value="164">Panama</option>
                                    <option value="165">Papua New Guinea</option>
                                    <option value="166">Paraguay</option>
                                    <option value="167">Peru</option>
                                    <option value="168">Philippines</option>
                                    <option value="169">Pitcairn</option>
                                    <option value="170">Poland</option>
                                    <option value="171">Portugal</option>
                                    <option value="172">Puerto Rico</option>
                                    <option value="173">Qatar</option>
                                    <option value="174">Republic of the Congo</option>
                                    <option value="175">Reunion</option>
                                    <option value="176">Romania</option>
                                    <option value="177">Russia</option>
                                    <option value="178">Rwanda</option>
                                    <option value="179">Saint Helena</option>
                                    <option value="180">Saint Kitts and Nevis</option>
                                    <option value="181">Saint Lucia</option>
                                    <option value="182">Saint Pierre and Miquelon</option>
                                    <option value="183">Saint Vincent and the Grenadines</option>
                                    <option value="184">Samoa</option>
                                    <option value="185">San Marino</option>
                                    <option value="186">Sao Tome and Principe</option>
                                    <option value="187">Saudi Arabia</option>
                                    <option value="188">Senegal</option>
                                    <option value="189">Serbia and Montenegro</option>
                                    <option value="190">Seychelles</option>
                                    <option value="191">Sierra Leone</option>
                                    <option value="192">Singapore</option>
                                    <option value="193">Slovakia</option>
                                    <option value="194">Slovenia</option>
                                    <option value="195">Solomon Islands</option>
                                    <option value="196">Somalia</option>
                                    <option value="197">South Africa</option>
                                    <option value="198">South Georgia and the South Sandwich Islands</option>
                                    <option value="199">South Korea</option>
                                    <option value="200">Spain</option>
                                    <option value="201">Sri Lanka</option>
                                    <option value="202">Sudan</option>
                                    <option value="203">Suriname</option>
                                    <option value="204">Svalbard and Jan Mayen</option>
                                    <option value="205">Swaziland</option>
                                    <option value="206">Sweden</option>
                                    <option value="207">Switzerland</option>
                                    <option value="208">Syria</option>
                                    <option value="209">Taiwan</option>
                                    <option value="210">Tajikistan</option>
                                    <option value="211">Tanzania</option>
                                    <option value="212">Thailand</option>
                                    <option value="213">Togo</option>
                                    <option value="214">Tokelau</option>
                                    <option value="215">Tonga</option>
                                    <option value="216">Trinidad and Tobago</option>
                                    <option value="217">Tunisia</option>
                                    <option value="218">Turkey</option>
                                    <option value="219">Turkmenistan</option>
                                    <option value="220">Turks and Caicos Islands</option>
                                    <option value="221">Tuvalu</option>
                                    <option value="222">U.S. Virgin Islands</option>
                                    <option value="223">Uganda</option>
                                    <option value="224">Ukraine</option>
                                    <option value="225">United Arab Emirates</option>
                                    <option value="226">United Kingdom</option>
                                    <option value="228">United States Minor Outlying Islands</option>
                                    <option value="229">Uruguay</option>
                                    <option value="230">Uzbekistan</option>
                                    <option value="231">Vanuatu</option>
                                    <option value="232">Vatican</option>
                                    <option value="233">Venezuela</option>
                                    <option value="234">Vietnam</option>
                                    <option value="235">Wallis and Futuna</option>
                                    <option value="236">Western Sahara</option>
                                    <option value="237">Yemen</option>
                                    <option value="238">Zambia</option>
                                    <option value="239">Zimbabwe</option>
                                </select>

                                <div class="erorshow" id="countyerror"></div>
                            </div>
                        </div>
                        <div class="loginrow fulwidthm left josfinsanbold graytext ">
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15">
                                <label class="formlabel fontsize13 robotoregular graytext">Password <span
                                        class=" required">*</span></label>
                                <input id="password" type="password" class="cmnfrminput fulwidthm ">

                                <div class="erorshow" id="passwrderror"></div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15">
                                <label class="formlabel fontsize13 robotoregular graytext">Confirm Password <span
                                        class=" required">*</span></label>
                                <input id="cpassword" type="password" class="cmnfrminput fulwidthm ">

                                <div class="erorshow" id="cpassworderror"></div>
                            </div>
                        </div>
                        <div class="loginrow fulwidthm left josfinsanbold graytext mgnB15">
                            <div class="col-lg-6 col-md-6 col-sm-6 mgnB15 registerBtntx">
                                <button class=" btn btn-success whitetext fulwidthm font-size14 robotomedium"
                                        onClick="register()">
                                    Register
                                </button>
                            </div>
                        </div>
                        <div
                            class="loginrow fulwidthm left registerBtntxText josfinsanregular fontsize16 graytext mgnB15">
                            Already a MyOffice member? <a class="greentext signinclick">Log In </a>
                        </div>
                        <div class="socialAuthStages register">
                            <div
                                class="loginrow orPlx fulwidthm left josfinsanregular fontsize16 graytext mgnB15 text-center">
                                <span>or</span>
                            </div>
                            <div class="fontsize12 graytext text-center mb-3">Login Through</div>
                            <div class="text-center d-block m-auto w-25">
                                <a href="http://mofront.myoffice.ca/index.php?page=user/facebook">
                                    <div class="text left pr-3">
                                        <img src="images/facebook.png">
                                        <p class="robotoregular fontsize12 graytext text-uppercase">Facebook</p>
                                    </div>
                                </a>
                                <a href="http://mofront.myoffice.ca/index.php?page=user/google">
                                    <div class="text-center left">
                                        <img src="images/google.png">
                                        <p class="robotoregular fontsize12 graytext text-uppercase">Google+</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@if(!is_api())
    @include('Layout::Home.parts.header')
@endif
@yield('content')
@include('Layout::Home.parts.footer')
@if(!is_demo_mode())
    {!! setting_item('footer_scripts') !!}
    {!! setting_item_with_lang_raw('footer_scripts') !!}
@endif
@php event(new \Modules\Layout\Events\LayoutEndBody()); @endphp

</body>
</html>

